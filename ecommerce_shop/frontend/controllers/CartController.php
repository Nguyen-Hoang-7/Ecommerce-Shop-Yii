<?php

namespace frontend\controllers;
use common\models\CartItem;
use common\models\Product;
use common\models\User;
use common\models\OrderAddress;
use common\models\Order;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use PayPalCheckoutSdk\Payments\AuthorizationsCaptureRequest;
use Sample\PayPalClient;
use Yii;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\helpers\VarDumper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\httpclient\Client;


class CartController extends \frontend\base\Controller
{
    public function behaviors() {
        return [
            [
                'class' => ContentNegotiator::class,
                'only' => ['add', 'create-order', 'submit-payment'],
                'formats' => [
                    'application/json' => Response::FORMAT_JSON
                ]
            ],
            [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST', 'DELETE'],
                    'create-order' => ['POST'],
                ]
            ]
        ];
    }
    public function actionIndex()
    {
        
        $cartItems = CartItem::getCartItemsForUser(Yii::$app->user->id);
    
        return $this->render('index', ['items' => $cartItems]);
    }

    public function actionAdd()
    {
        $id = Yii::$app->request->post('id');
        $product = Product::find()->id($id)->published()->one();
        if (!$product) {
            throw new NotFoundHttpException('The product does not exist.');
        }

        if (Yii::$app->user->isGuest) {
            // Save in session
            return $this->redirect(['/site/login']);
//            $cartItem = [
//                'id' => $id,
//                'image' => $product->image,
//                'name' => $product->name,
//                'price' => $product->price,
//                'quantity' => 1,
//                'total_price' => $product->price,
//            ];
//            $cartItems = Yii::$app->session->get(CartItem::SESSION_KEY, []);
//            $found =  false;
//            foreach ($cartItems as $cartItem) {
//                if ($cartItem['id'] == $id) {
//                    $cartItem['quantity'] = $cartItem['quantity'] + 1;
//                    $found = true;
//                }
//            }
//
//            if (!$found) {
//                $cartItem = [
//                    'id' => $id,
//                    'image' => $product->image,
//                    'name' => $product->name,
//                    'price' => $product->price,
//                    'quantity' => 1,
//                    'total_price' => $product->price,
//                ];
//                $cartItems[]  = $cartItem;
//            }
//
//            $cartItems[] = $cartItem;
//            Yii::$app->session->set(CartItem::SESSION_KEY, $cartItems);
        }
        else {
            $user_id = Yii::$app->user->id;
            $cartItem = CartItem::find()->userId($user_id)->productId($id)->one();
            if (!$cartItem) {
                $cartItem = new CartItem();
                $cartItem->product_id = $id;
                $cartItem->created_by = Yii::$app->user->id;
                $cartItem->quantity = 1;
            }
            else {
                $cartItem->quantity = $cartItem->quantity + 1;
            }

            if ($cartItem->save()) {
                return[
                    'success' => true
                ];
            }
            else {
                return [
                    'success' => false,
                    'errors' => $cartItem->errors
                ];
            }
        }

    }

    public function actionDelete($id)
    {
        CartItem::deleteAll(['product_id' => $id, 'created_by' => Yii::$app->user->id]);
        return $this->redirect(['index']);
    }

    public function actionChangeQuantity() {
        $id = Yii::$app->request->post('id');
        $product = Product::find()->id($id)->published()->one();
        if (!$product) {
            throw new NotFoundHttpException('The product does not exist.');
        }
        $quantity = Yii::$app->request->post('quantity');
        $cartItem = CartItem::find()->userId(Yii::$app->user->id)->productId($id)->one();
        if ($cartItem) {
            $cartItem->quantity = $quantity;
            $cartItem->save();
        }

        return CartItem::getTotalQuantityForUser(Yii::$app->user->id);
    }

    public function actionCheckout()
    {
        // This is a placeholder for the checkout process
        // You can implement your checkout logic here
        $cartItems = CartItem::getCartItemsForUser(Yii::$app->user->id);
        $productQuantity = CartItem::getTotalQuantityForUser(Yii::$app->user->id);
        $totalPrice = CartItem::getTotalPriceForUser(Yii::$app->user->id);

        if (empty($cartItems)) {
            // Yii::$app->session->setFlash('error', 'Your cart is empty.');
            return $this->redirect([Yii::$app->homeUrl]);
        }
        $user = Yii::$app->user->identity;
        $useraddress = $user->getAddress();
        $order = new Order();

        $order->total_price = $totalPrice;
        $order->status = Order::STATUS_DRAFT;
        $order->created_by = Yii::$app->user->id;
        $order->created_at = time();

        $transaction = Yii::$app->db->beginTransaction();

        $exchangePrice = 0;
        $usdRate = 1; // Default to 1 if the API call fails

        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl('https://open.er-api.com/v6/latest/VND')
            ->send();

        if ($response->isOk) {
            $data = $response->data;
            if (isset($data['rates']['USD'])) {
                $usdRate = $data['rates']['USD'];
                $exchangePrice = $totalPrice * $usdRate;
                $exchangePrice = number_format($exchangePrice, 2, '.', '');
            }
        }

        if ($order->load(Yii::$app->request->post()) && $order->save() && $order->saveAddress(Yii::$app->request->post()) && $order->saveOrderItems()) {
            // Save order items
            $transaction->commit();
            CartItem::clearCartItems(Yii::$app->user->id);

            return $this->render('pay-now', [
                'order' => $order,
                'exchangePrice' => $exchangePrice
            ]);
        }

        $orderAddress = new OrderAddress();
        $order->firstName = $user->firstname;
        $order->lastName = $user->lastname;
        $order->email = $user->email;
        $order->status = Order::STATUS_DRAFT;

        $orderAddress->address = $useraddress->address;
        $orderAddress->city = $useraddress->city;
        $orderAddress->state = $useraddress->state;
        $orderAddress->country = $useraddress->country;
        $orderAddress->zipcode = $useraddress->zipcode;

        return $this->render('checkout', [
            'order' => $order,
            'orderAddress' => $orderAddress,
            'cartItems' => $cartItems,
            'productQuantity' => $productQuantity,
            'totalPrice' => $totalPrice,
            'exchangePrice' => $exchangePrice
        ]);
    }


    public function actionSubmitPayment($orderId)
    {
        $where = ['id' => $orderId, 'status' => Order::STATUS_DRAFT];
        $order = Order::findOne($where);
        if (!$order) {
            throw new NotFoundHttpException('The order does not exist.');
        }

        $req = Yii::$app->request;

        //BUG NẰM CHỖ NÀY
        $paypalOrderId = $req->post('orderID');
        // $order->transaction_id = Yii::$app->request->post('transaction_id');
        $order_exist = Order::find()->andWhere(['paypal_order_id' => $paypalOrderId])->exists();

       if ($order_exist) {
            throw new BadRequestHttpException();
       }

        // Secret Key : ECl4YfE0JJ37HStwM_eb9RmAia8-rMl0pC6Eq2p4jzXKKX1D0dZNBsecQmZhg7l6MkEyuCZ3qjicxRkm
        // Client ID : Aed_lId0dyVXC7LHaqNxbwjmrrBCedlkTGrlNCcHt0QLgvwcII7aS8Ih-fyRtlWNiMakttTFQ09r5LEC

//// Validate the transactionId. It must not be used and it must be valid transacttion ID in paypal
        $environment = new SandboxEnvironment(Yii::$app->params['paypalClientId'], Yii::$app->params['paypalSecret']);
        $client = new PayPalHttpClient($environment);
        $response = $client->execute(new OrdersGetRequest($paypalOrderId));
        /**
         * Enable below line to print complete response as JSON.
         */
        //print json_encode($response->result);

        if ($response->statusCode === 200) {
            $order->paypal_order_id =  $paypalOrderId;
            $order->status = $response->result->status === 'COMPLETED' ? Order::STATUS_COMPLETED : Order::STATUS_FAILED;
            $paidAmount = 0;
            foreach ($response->result->purchase_units as $unit) {
                if ($unit->amount->currency_code === 'USD') {
                    $paidAmount += $unit->amount->value;
                }
            }

            $client_exchange = new Client();
            $response_exchange = $client_exchange->createRequest()
                ->setMethod('GET')
                ->setUrl('https://open.er-api.com/v6/latest/VND')
                ->send();

            if ($response_exchange->isOk) {
                $data = $response_exchange->data;
                if (isset($data['rates']['USD'])) {
                    $usdRate = $data['rates']['USD'];
                    $exchangePrice = $order->total_price * $usdRate;
                    $exchangePrice = number_format($exchangePrice, 2, '.', '');
                }
            }

            if ($paidAmount == $exchangePrice && $response->result->status === 'COMPLETED') {
                $order->status = Order::STATUS_COMPLETED;
            }
            $order->transaction_id = $response->result->purchase_units[0]->payments->captures[0]->id;
            if ($order->save()) {
                if (!$order->sendEmailToVendor()){
                    Yii::error("Email to the vendor is not sent");
                }
                if (!$order->sendEmailToCustomer()){
                    Yii::error("Email to customer is not sent");
                }
                return [
                    'success' => true
                ];
            }
            else {
                Yii::error("Order was not saved. Data: ". VarDumper::dumpAsString($order->toArray())." Errors: ". VarDumper::dumpAsString($order->errors));
            }
        }
        throw new BadRequestHttpException();
    }

}