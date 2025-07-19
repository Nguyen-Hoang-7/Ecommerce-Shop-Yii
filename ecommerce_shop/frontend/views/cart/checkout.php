
<?php
/**
 * User: TheCodeholic
 * Date: 12/12/2020
 * Time: 8:12 PM
 */
/** @var \common\models\Order $order */
/** @var \common\models\OrderAddress $orderAddress */
/** @var array $cartItems */
/** @var int $productQuantity */

/** @var float $totalPrice */

use yii\bootstrap5\ActiveForm;
use common\models\Product;
?>


<?php $form = ActiveForm::begin([
    'id' => 'checkout-form'
]); ?>
<div class="row">
    <div class="col">

        <div class="card mb-3">
            <div class="card-header">
                <h5>Account information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($order, 'firstName')->textInput(['autofocus' => true]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($order, 'lastName')->textInput(['autofocus' => true]) ?>
                    </div>
                </div>
                <?= $form->field($order, 'email')->textInput(['autofocus' => true]) ?>

            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5>Address information</h5>
            </div>
            <div class="card-body">
                <?= $form->field($orderAddress, 'address') ?>
                <?= $form->field($orderAddress, 'city') ?>
                <?= $form->field($orderAddress, 'state') ?>
                <?= $form->field($orderAddress, 'country') ?>
                <?= $form->field($orderAddress, 'zipcode') ?>
            </div>
        </div>

    </div>
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h5>Order Summary</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($cartItems as $item): ?>
                        <tr data-id="<?php echo $item['id'] ?>" data-url="<?php echo \yii\helpers\Url::to(['/cart/change-quantity']) ?>">
                            <td><?php echo $item['name'] ?></td>
                            <td>
                                <img src="<?php echo Yii::$app->request->baseUrl . '/storage/products/'. $item['image'] ?>"
                                     alt="<?php echo $item['image'] ?>"
                                     style="width: 150px">
                            </td>
                            <td><?php echo Yii::$app->formatter->asCurrency($item['price']) ?></td>
                            <td>
                                <?php echo $item['quantity'] ?>
                            </td>
                            <td><?php echo Yii::$app->formatter->asCurrency($item['total_price']) ?></td>
                            <td>
                                <?php echo \yii\helpers\Html::a('Delete', ['/cart/delete', 'id' => $item['id']], [
                                    'class' => 'btn btn-outline-danger btn-sm',
                                    'data-method' => 'post',
                                    'data-confirm' => 'Are you sure you want to delete this product from your cart?',
                                ]) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <hr>
                <table class="table">
                    <tr>
                        <td>Total Items</td>
                        <td class="text-right"><?php echo $productQuantity ?></td>
                    </tr>
                    <tr>
                        <td>Total Price</td>
                        <td class="text-right">
                            <?php echo Yii::$app->formatter->asCurrency($totalPrice) ?>
                        </td>
                    </tr>
                </table>

                <p class="text-right mt-3">
                    <button class="btn btn-secondary">Checkout</button>
                </p>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

