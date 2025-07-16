<?php

/** @var yii\web\View $this */

$this->title = 'My Yii Application';
?>
<div class="site-index">
    

    <div class="body-content">

        <div class="row">
            <?php echo \yii\widgets\ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => '_product_item', // Assuming you have a view file for each product item
                'layout' => "{summary}<div class='row'>{items}</div>{pager}",
                'options' => [
                    'class' => 'row',
                ],
                'itemOptions' => [
                    'class' => 'col-lg-4 col-md-6 mb-4 product-item', // Adjust the class for Bootstrap grid
                ],
                'pager' => [ 
                    'class' => \yii\bootstrap5\LinkPager::class,
                    // 'options' => ['class' => 'pagination justify-content-center'],
                ],
            ]); ?>
            
        </div>

    </div>
</div>
