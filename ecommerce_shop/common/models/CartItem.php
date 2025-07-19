<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%cart_items}}".
 *
 * @property int $id
 * @property int $product_id
 * @property int $quantity
 * @property int|null $created_by
 *
 * @property User $createdBy
 * @property Products $product
 */
class CartItem extends \yii\db\ActiveRecord
{

    const SESSION_KEY = 'CART_ITEMS';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%cart_items}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_by'], 'default', 'value' => null],
            [['product_id', 'quantity'], 'required'],
            [['product_id', 'quantity', 'created_by'], 'default', 'value' => null],
            [['product_id', 'quantity', 'created_by'], 'integer'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::class, 'targetAttribute' => ['product_id' => 'id']],
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
            'product_id' => 'Product ID',
            'quantity' => 'Quantity',
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
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\ProductsQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\CartItemQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\CartItemQuery(get_called_class());
    }

    public static function getTotalQuantityForUser($currentUserId)
    {
        $sum = CartItem::findBySql("
            SELECT SUM(quantity) AS total_quantity from cart_items where created_by = :user_id
        ", ["user_id" => $currentUserId])->scalar();
        // $cartItems = CartItem::find()->where(['created_by' => Yii::$app->user->id])->all();
        // $sum = 0;
        // foreach ($cartItems as $cartItem) {
        //     $sum += $cartItem['quantity'];
        // }
        return $sum;
    }

    public static function getCartItemsForUser($currentUserId)
    {
        $cartItems = CartItem::findBySql("
            SELECT
                c.product_id as id,
                p.image,
                p.name,
                p.price,
                c.quantity,
                p.price * c.quantity as total_price
            FROM cart_items c
                    LEFT JOIN products p on p.id = c.product_id
            WHERE c.created_by = :user_id", ['user_id'=>$currentUserId])->asArray()->all();
        return $cartItems;
    }

    public static function getTotalPriceForItemForUser($productId, $currentUserId) {
        $totalPrice = CartItem::findBySql("
            SELECT
                SUM(p.price * c.quantity) as total_price
            FROM cart_items c
                    LEFT JOIN products p on p.id = c.product_id
            WHERE c.product_id = :id AND c.created_by = :user_id", ['id' => $productId, 'user_id'=>$currentUserId])->scalar();
        return $totalPrice;
    }

    public static function getTotalPriceForUser($currentUserId) {
        $totalPrice = CartItem::findBySql("
            SELECT
                SUM(p.price * c.quantity) as total_price
            FROM cart_items c
                    LEFT JOIN products p on p.id = c.product_id
            WHERE c.created_by = :user_id", ['user_id'=>$currentUserId])->scalar();
        return $totalPrice;
    }

    public static function clearCartItems($currUserId)
    {
        if (Yii::$app->user->isGuest)
        {
            Yii::$app->session->remove(CartItem::SESSION_KEY);
        } else {
            CartItem::deleteAll(['created_by' => $currUserId]);
        }
    }
}
