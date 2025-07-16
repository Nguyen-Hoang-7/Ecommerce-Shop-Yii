<?php

namespace common\models;

use Yii;
use yii\base\Exception;

/**
 * This is the model class for table "{{%orders}}".
 *
 * @property int $id
 * @property float $total_price
 * @property int $status
 * @property string $firstName
 * @property string $lastName
 * @property string $email
 * @property string|null $transaction_id
 * @property int|null $created_at
 * @property int|null $created_by
 *
 * @property User $createdBy
 * @property OrderAddresses $orderAddresses
 * @property OrderItems[] $orderItems
 */
class Order extends \yii\db\ActiveRecord
{

    const STATUS_DRAFT = 0;
    const  STATUS_COMPLETED = 1;
    const STATUS_FAILED = 2;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%orders}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['transaction_id', 'created_at', 'created_by'], 'default', 'value' => null],
            [['total_price', 'status', 'firstName', 'lastName', 'email'], 'required'],
            [['total_price'], 'number'],
            [['status', 'created_at', 'created_by'], 'default', 'value' => null],
            [['status', 'created_at', 'created_by'], 'integer'],
            [['firstName', 'lastName'], 'string', 'max' => 45],
            [['email', 'transaction_id', 'paypal_order_id'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'total_price' => 'Total Price',
            'status' => 'Status',
            'firstName' => 'First Name',
            'lastName' => 'Last Name',
            'email' => 'Email',
            'transaction_id' => 'Transaction ID',
            'paypal_order_id' => 'Paypal Order ID',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * Gets query for [[OrderAddresses]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\OrderAddressesQuery
     */
    public function getOrderAddress()
    {
        return $this->hasOne(OrderAddress::class, ['order_id' => 'id']);
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\OrderItemsQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::class, ['order_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\OrderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\OrderQuery(get_called_class());
    }

    public function saveAddress($postData) {
        $orderAddress = new OrderAddress();
        $orderAddress->order_id = $this->id;
        if ($orderAddress->load($postData) && $orderAddress->save()) {
            
            return true;
        } 
        throw new Exception('Could not save order address: '. implode('<br>', $orderAddress->getFirstErrors()));
        
    }

    public function saveOrderItems() {
        // $transaction = Yii::$app->db->beginTransaction();
        $cartItems = CartItem::getCartItemsForUser(Yii::$app->user->id);
        foreach ($cartItems as $item) {
            $orderItem = new OrderItem();
            $orderItem->product_name = $item['name'];
            $orderItem->product_id = $item['id'];
            $orderItem->quantity = $item['quantity'];
            $orderItem->unit_price = $item['price'];
            $orderItem->order_id = $this->id;
            if (!$orderItem->save()) {
                // $transaction->rollBack();
                throw new Exception('Failed to save order item: '. implode('<br>', $orderItem->getFirstErrors()));
            }
        }

        // $transaction->commit();
        return true;
    }

    public function getItemsQuantity()
    {
        $sum = CartItem::findBySql("SELECT SUM(quantity) AS total_quantity from order_items where order_id = :order_id", ["order_id" => $this->id])->scalar();
        return $sum;
    }

    public function sendEmailToVendor()
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'order_completed_vendor-html', 'text' => 'order_completed_vendor-text'],
                ['order' => $this]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo(Yii::$app->params['vendorEmail'])
            ->setSubject('New order has been made at ' . Yii::$app->name)
            ->send();
    }
    public function sendEmailToCustomer()
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'order_completed_customer-html', 'text' => 'order_completed_customer-text'],
                ['order' => $this]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Your orders is confirmed at ' . Yii::$app->name)
            ->send();
    }
}
