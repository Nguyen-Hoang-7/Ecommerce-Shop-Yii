<?php

namespace backend\controllers;

use common\models\LoginForm;
use common\models\Order;
use common\models\OrderItem;
use common\models\User;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;
use yii\web\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'forgot-password', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
        ];
    }


    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $totalEarnings = Order::find()->paid()->sum('total_price');
        $totalOrders = Order::find()->paid()->count();
        $totalProducts = OrderItem::find()
            ->alias('oi')
            ->innerJoin(Order::tableName(). 'o', 'o.id = oi.order_id')
            ->andWhere(['o.status' => Order::STATUS_COMPLETED])
            ->sum('quantity');
        $totalUsers = User::find()->andWhere(['status' => User::STATUS_ACTIVE])->count();

        $usdRate = 0;
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl('https://open.er-api.com/v6/latest/VND')
            ->send();

        if ($response->isOk) {
            $data = $response->data;
            if (isset($data['rates']['USD'])) {
                $usdRate = $data['rates']['USD'];
            }
        }

        $orders = Order::findBySql("
            SELECT
                TO_CHAR(TO_TIMESTAMP(o.created_at), 'DD-MM-YYYY HH24:MI:SS') AS date_time,
                SUM(o.total_price) AS total_price
            FROM orders o
            WHERE o.status = :status
            GROUP BY TO_CHAR(TO_TIMESTAMP(o.created_at), 'DD-MM-YYYY HH24:MI:SS')
            ORDER BY MIN(o.created_at)
        ", ['status' => Order::STATUS_COMPLETED])->asArray()->all();

        // Line Chart
        $earningsData = [];
        $labels = [];
        $labels_draft = [];

        if (!empty($orders)) {
            $minDate = $orders[0]['date_time'];
            $orderByPriceMap = ArrayHelper::map($orders, 'date_time', 'total_price');

            $d = new \DateTime($minDate);
            $nowDate = new \DateTime();
            $dates = [];
            $orderByPriceDailyMap = [];
            foreach ($orderByPriceMap as $dateTime => $amount) {
                $day = date('d-m-Y', strtotime($dateTime));
                if (!isset($orderByPriceDailyMap[$day])) {
                    $orderByPriceDailyMap[$day] = 0;
                }
                $orderByPriceDailyMap[$day] += (float)$amount;
            }


            while ($d->getTimestamp() <= $nowDate->getTimestamp()) {
                $label = $d->format('d-m-Y');
                $label_draft = $d->format('d-m-Y H:i:s');
                $labels_draft[] = $label_draft;
                $labels[] = $label;
                $earningsData[] = ($orderByPriceDailyMap[$d->format('d-m-Y')] ?? 0) * $usdRate;
                $d->setTimestamp($d->getTimestamp() + 86400);
            }
        }

        // Pie Chart
        $countriesData = Order::findBySql("
            SELECT country,
                SUM(total_price) AS total_price
            FROM orders o
            INNER JOIN order_addresses oa ON o.id = oa.order_id
            WHERE o.status = :status
            GROUP BY country
        ", ['status' => Order::STATUS_COMPLETED])->asArray()->all();

        // $length_labels = count($labels);
        // echo "<pre>";
        // var_dump($orders);
        // echo "</pre>";
        // exit;

        $countryLabels = ArrayHelper::getColumn($countriesData, 'country');
        $newCountries = [];
        $bgColors = [];
        $colorOptions = [
            '#4e73df',
            '#1cc88a',
            '#36b9cc',
            '#f6c23e',
            '#e74a3b',
            '#f8f9fc',
            '#6c757d'
        ];
        $hoverColors = [];
        foreach ($countryLabels as $i => $country) {
            /*
            $color = "rgb(".rand(0, 255).", ".rand(0, 255).", ".rand(0, 255).")";
            $bgColors[] = $color;
            */
            $bgColors[] = $colorOptions[$i % count($colorOptions)];
        }
        return $this->render('index', [
            'totalEarnings' => $totalEarnings,
            'totalOrders' => $totalOrders,
            'totalProducts' => $totalProducts,
            'totalUsers' => $totalUsers,
            'earningsData' => $earningsData,
            'labels' => $labels,
            'countries' => $countryLabels,
            'bgColors' => $bgColors,
            'countriesData' => ArrayHelper::getColumn($countriesData, 'total_price'),
        ]);
    }

    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionForgotPassword()
    {
        // This action can be implemented to handle password reset requests.
        // For now, it can redirect to the login page or render a view.
        return "Forgot Password";
    }
}
