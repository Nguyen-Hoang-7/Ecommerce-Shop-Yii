<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "{{%products}}".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $image
 * @property float $price
 * @property int $status
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property CartItems[] $cartItems
 * @property User $createdBy
 * @property OrderItems[] $orderItems
 * @property User $updatedBy
 */
class Product extends \yii\db\ActiveRecord
{


    public $imageFile;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%products}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'price', 'status', 'image'], 'required'],
            [['description'], 'string'],
            [['price'], 'number'],
            [['status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['image'], 'string', 'max' => 2000],
            [['imageFile'], 'image', 'extensions' => 'png, jpg, jpeg, gif, webp', 'maxSize' => 1024 * 1024 *10],           [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'image' => 'Product Image',
            'imageFile' => 'Product Image',
            'price' => 'Price',
            'status' => 'Published',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * Gets query for [[CartItems]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\CartItemsQuery
     */
    public function getCartItems()
    {
        return $this->hasMany(CartItems::class, ['product_id' => 'id']);
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
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\OrderItemsQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItems::class, ['product_id' => 'id']);
    }

    /**
     * Gets query for [[UpdatedBy]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\ProductsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\ProductQuery(get_called_class());
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        if ($this->imageFile) {
            $this->image = $this->imageFile->baseName . '.' . $this->imageFile->extension;
        }
        else {
            // Nếu không có file hình ảnh, giữ nguyên giá trị cũ
            $this->image = $this->isNewRecord ? 'default-thumbnail.jpg' : $this->getOldAttribute('image');
        }
        
        $transaction = Yii::$app->db->beginTransaction();

        $ok = parent::save($runValidation, $attributeNames);

        if ($ok && $this->imageFile) {
            $frontendPath = Yii::getAlias('@frontend/web/storage/products/') . $this->image;
            $backendPath = Yii::getAlias('@backend/web/storage/products/') . $this->image;

            // Tạo thư mục nếu chưa có
            FileHelper::createDirectory(dirname($frontendPath));
            FileHelper::createDirectory(dirname($backendPath));

            // Lưu file vào cả frontend và backend
            if (!$this->imageFile->saveAs($frontendPath)) {
                $transaction->rollBack();
                return false;
            }

            // Copy file từ frontend sang backend (nếu muốn đảm bảo cả hai có file giống nhau)
            copy($frontendPath, $backendPath);
            // $transaction->commit();
        }
        if ($ok) {
            $transaction->commit();
        }
        return $ok;
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            BlameableBehavior::class
        ];
    }

    public function getImageUrl()
    {

        return Yii::getAlias('@frontendUrl') . '/storage/products/' . $this->image;
    }

    public function getShortDescription($length = 30)
    {
        return \yii\helpers\StringHelper::truncateWords(strip_tags($this->description), $length);
    }
}
