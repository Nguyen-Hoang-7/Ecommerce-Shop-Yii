<?php

use common\models\Order;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\bootstrap5;

/** @var yii\web\View $this */
/** @var backend\models\search\OrderSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Order', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <i class="fas fa-chevron-down"></i>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php $form = \yii\widgets\ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['id' => 'grid-filter-form']
    ]); ?>

    <div class="table-responsive">
        <?= GridView::widget([
            'id' => 'ordersTable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pager' => [
                    'class' => \yii\bootstrap5\LinkPager::class,
            ],
            'columns' => [
                [
                        'attribute' => 'id',
                    'contentOptions' => ['style' => 'width: 80px;'],
                ],
                [
                        'attribute' => 'fullname',
                    'content' => function  ($model) {
                        return $model->firstName . ' ' . $model->lastName;
                    },
                ],
                'total_price:currency',
                [
                    'attribute'=> 'status',
                    'filter' => Html::activeDropDownList($searchModel, 'status', Order::getStatusLabels(), [
                        'class' => 'form-control',
                        'prompt' => 'All'
                    ]),
                    'format' => ['orderStatus'],
                ],
                //'email:email',
                //'transaction_id',
                [
                    'attribute' => 'created_at',
                    'format' => ['datetime', 'php:d-m-Y H:i:s'],
                    'contentOptions' => ['style' => 'white-space: nowrap']
                ],
                //'created_by',
                //'paypal_order_id',
                [
                    'class' => ActionColumn::className(),
                    'urlCreator' => function ($action, Order $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id' => $model->id]);
                    }
                ],
            ],
        ]); ?>
    </div>
    
    <?php \yii\widgets\ActiveForm::end(); ?>

    <script>
    // Auto submit form when filter values change
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('grid-filter-form');
        const inputs = form.querySelectorAll('input, select');
        
        inputs.forEach(function(input) {
            input.addEventListener('change', function() {
                form.submit();
            });
            
            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    form.submit();
                }
            });
        });
    });
    </script>

</div>
