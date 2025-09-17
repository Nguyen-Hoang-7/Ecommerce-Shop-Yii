<?php

namespace common\models;

use Yii;
use common\models\Locality;

/**
 * This is the model class for table "{{%order_addresses}}".
 *
 * @property int $order_id
 * @property string $address
 * @property string $ward_code
 * @property string $district_code
 * @property string $province_code
 * @property string $full_address
 *
 * @property Orders $order
 */
class OrderAddress extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order_addresses}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //[['full_address'], 'default', 'value' => null],
            [['order_id', 'address', 'ward_code', 'province_code'], 'required'],
            [['district_code', 'full_address'], 'safe'], // district_code và full_address có thể null/auto-generated
            [['order_id'], 'default', 'value' => null],
            [['order_id'], 'integer'],
            [['address', 'ward_code', 'district_code', 'province_code', 'full_address'], 'string', 'max' => 255],
            [['order_id'], 'unique'],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::class, 'targetAttribute' => ['order_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'Order ID',
            'address' => 'Address',
            'ward_code' => 'Ward Code',
            'district_code' => 'District Code',
            'province_code' => 'Province Code',
            'full_address' => 'Full Address',
        ];
    }

    /**
     * Gets query for [[Order]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\OrdersQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::class, ['id' => 'order_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\OrderAddressQuery the active query used by this AR class.
     */
    /*
    public static function find()
    {
        return new \common\models\query\OrderAddressQuery(get_called_class());
    }
    */

    public function getFullAddress() {
        return Locality::getFullAddress($this->address, $this->ward_code, $this->district_code, $this->province_code);
    }

    /**
     * Tự động cập nhật full_address trước khi lưu vào database
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->full_address = $this->getFullAddress();
            return true;
        }
        return false;
    }

}