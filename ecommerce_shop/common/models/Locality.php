<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%locality}}".
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $parent_id
 * @property int $locality_type
 * @property int $status
 * @property string $parent_code
 */
class Locality extends \yii\db\ActiveRecord
{
    public static function tableName() {
        return '{{%locality}}';
    }

    public function rules() {
        return [
            [['code', 'name', 'locality_type', 'status'], 'required'],
            [['parent_id', 'locality_type'], 'integer'],
            [['code', 'name', 'parent_code', 'status'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'code' => Yii::t('app', 'Code'),
            'name' => Yii::t('app', 'Name'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'locality_type' => Yii::t('app', 'Locality Type'),
            'status' => Yii::t('app', 'Status'),
            'parent_code' => Yii::t('app', 'Parent Code'),
        ];
    }
    
    public function getParent() {
        return $this->hasOne(Locality::class, ['code' => 'parent_code']);
    }

    public function getChildren() {
        return $this->hasMany(Locality::class, ['parent_code' => 'code']);
    }

    public static function getFullAddress($address, $ward_code, $district_code, $province_code) {
        $fullAddress = $address . ", ";
        $ward = self::getCode($ward_code);
        $district = self::getCode($district_code);
        $province = self::getCode($province_code);
        
        if (empty($district)) { // Ward only
            if ($ward && $province) {
                $fullAddress .= $ward->name . ', ' . $province->name;
            }
        } else { // Ward and district
            if ($ward && $district && $province) {
                $fullAddress .= $ward->name . ', ' . $district->name . ', ' . $province->name;
            }
        }
        
        $result = trim($fullAddress, ', ');
        Yii::info("Locality::getFullAddress result: {$result}", 'locality');
        return $result;
    }

    public static function getCode($code) {
        if ($code) {
            return self::findOne(['code' => $code]);
        } 
        return null;
    }

    /**
     * Lấy danh sách các tỉnh/thành phố
     * @param int $status Status của locality (0 = old format, N = new format)
     */
    public static function getNameAddressOptions($locality_type, $status = 'N', $parent_code = null) {
        $nameAddressOptions = self::find()
            ->where(['locality_type' => $locality_type, 'status' => $status]) // 1 = tỉnh/thành phố, 2 = quận/huyện, 3 = phường/xã
            ->andWhere(['parent_code' => $parent_code])
            ->andWhere(['not like', 'name', '*'])
            ->all();
        
        return ArrayHelper::map($nameAddressOptions, 'code', 'name');
    }

}