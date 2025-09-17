<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Order $model */

$this->title = 'Order #'. $model->id. ' Details';
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$orderAddress = $model->orderAddress;

// Lấy tên các địa phương với kiểm tra null
$ward = \common\models\Locality::getCode($orderAddress->ward_code);
$ward_name = $ward ? $ward->name : '';

$district = \common\models\Locality::getCode($orderAddress->district_code);
$district_name = $district ? $district->name : '';

$province = \common\models\Locality::getCode($orderAddress->province_code);
$province_name = $province ? $province->name : '';
?>
<div class="order-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
<!--        --><?php //= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'total_price:currency',
            'status:orderStatus',
            'firstName',
            'lastName',
            'email:email',
            'transaction_id',
            'created_at:datetime',
            'paypal_order_id',
        ],
    ]) ?>

    <h4>Order Address</h4>
    <?= DetailView::widget([
        'model' => $orderAddress,
        'attributes' => array_filter([
            'address',
            [
                'label' => 'Ward name',
                'value' => $ward_name,
            ],
            // Chỉ hiển thị District name nếu không null
            !empty($district_name) ? [
                'label' => 'District name',
                'value' => $district_name,
            ] : null,
            [
                'label' => 'Province name',
                'value' => $province_name,
            ],
            'full_address',
        ]),
    ]) ?>

    <h4>Order Items</h4>
    <table class="table table-sm">
        <thead>
        <tr>
            <th>Image</th>
            <th>Name</th>
            <th>Quantity</th>
            <th>Unit Price</th>
            <th>Total Price</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($model->orderItems as $item): ?>
            <tr>
                <td>
                    <img src="<?php echo $item->product ? $item->product->getImageUrl() : \common\models\Product::formatImageUrl(null) ?>"
                         style="width: 50px;"
                         alt="<?php echo $item->product_name ?>">
                </td>
                <td><?php echo $item->product_name ?></td>
                <td><?php echo $item->quantity ?></td>
                <td><?php echo Yii::$app->formatter->asCurrency($item->unit_price) ?></td>
                <td><?php echo Yii::$app->formatter->asCurrency($item->quantity * $item->unit_price) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

