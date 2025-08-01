<?php
/**
 *
 */
?>

<div class="card">
    <div class="card-header">
        <h2>Your Cart Items</h2>
    </div>
    <div class="card-body">
        <?php if (!empty($items)): ?>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Product</th>
                <th>Image</th>
                <th>Unit Price</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($items as $item): ?>
            <tr data-id="<?php echo $item['id'] ?>" data-url="<?php echo \yii\helpers\Url::to(['/cart/change-quantity']) ?>">
                <td><?php echo $item['name'] ?></td>
                <td>
                    <img src="<?php echo Yii::$app->request->baseUrl . '/storage/products/'. $item['image'] ?>"
                         alt="<?php echo $item['image'] ?>"
                         style="width: 150px">
                </td>
                <td><?php echo Yii::$app->formatter->asCurrency($item['price']) ?></td>
                <td>
                    <input type="number" min="1" class="form-control item-quantity" style="width: 80px" value="<?php echo $item['quantity'] ?>">
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
        <div class="card-body text-end">
            <a href="<?php echo \yii\helpers\Url::to(['/cart/checkout']) ?>" class="btn btn-primary">Check out</a>
        </div>
        <?php else: ?>

            <p class="text-muted text-center p-5">There are no items in the cart</p>

        <?php endif; ?>

    </div>
</div>

