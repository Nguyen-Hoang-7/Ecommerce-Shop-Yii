<?php
?>


    <div class="card h-100 item-card">
        <a href="<?php echo \yii\helpers\Url::to(['/product/view', 'id' => $model->id]) ?>" class="img-wrapper">
            <img class="card-img-top item-card-img" src="<?php echo $model->getImageUrl() ?>" alt="">
        </a>
        <div class="card-body item-card-body">
            <h5 class="card-title item-card-title">
                <a href="<?php echo \yii\helpers\Url::to(['/product/view', 'id' => $model->id]) ?>" class="text-primary"><?php echo \yii\helpers\StringHelper::truncateWords($model->name, 7) ?></a>
            </h5>
            <h5><?php echo Yii::$app->formatter->asCurrency($model->price) ?></h5>
            <div class="card-text item-card-text">
                <?php echo $model->getShortDescription() ?>
            </div>
        </div>
        <div class="card-footer text-right item-card-footer">
            <a href="<?php echo \yii\helpers\Url::to(['/cart/add']) ?>" class="btn btn-primary btn-add-to-cart">
                Add to Cart
            </a>
        </div>
    </div>
