<?php

namespace common\models;

use Yii;
use common\models\Locality;

/**
 * This is the model class for table "{{%user_addresses}}".
 *
 * @property int $id
 * @property int $user_id
 * @property string $address
 * @property string $ward_code
 * @property string $district_code
 * @property string $province_code
 * @property string $full_address
 *
 * @property User $user
 */
class UserAddress extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_addresses}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //[['full_address'], 'default', 'value' => null],
            [['user_id', 'address', 'ward_code', 'province_code', 'full_address'], 'required'],
            [['user_id'], 'default', 'value' => null],
            [['user_id'], 'integer'],
            [['address', 'ward_code', 'district_code', 'province_code', 'full_address'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'address' => 'Address',
            'ward_code' => 'Ward Code',
            'district_code' => 'District Code',
            'province_code' => 'Province Code',
            'full_address' => 'Full Address',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\UserAddressQuery the active query used by this AR class.
     */
    /*
    public static function find()
    {
        return new \common\models\query\UserAddressQuery(get_called_class());
    }
    */

    public function getFullAddress() {
        return Locality::getFullAddress($this->address, $this->ward_code, $this->district_code, $this->province_code);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->full_address = $this->getFullAddress();
            return true;
        }
        return false;
    }
}
