<?php

use common\models\Product;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\search\ProductSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Product', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    
    <?php $form = \yii\widgets\ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['id' => 'grid-filter-form']
    ]); ?>
    
    <div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'attribute' => 'id',
                    'contentOptions' => [
                        'style' => 'width: 60px'
                    ]
                ],
                [
                    'attribute' => 'name',
                    'content' => function ($model) {
                        return \yii\helpers\StringHelper::truncateWords($model->name, 7);
                    }
                ],
                [
                    'attribute' => 'image',
                    'content' => function ($model) {
                        return Html::img($model->getImageUrl(), ['alt' => $model->name, 'width' => '150', 'height' => '150']);
                    }
                ],
                'price:currency',
                [
                    'attribute' => 'status',
                    'content' => function ($model) {
                        return Html::tag('span', $model->status ? 'Published' : 'Unpublished', [
                            'class' => $model->status ? 'badge bg-success' : 'badge bg-danger'
                        ]);
                    }
                ],
                [
                    'attribute' => 'created_at',
                    'format' => ['datetime', 'php:d-m-Y H:i:s'],
                    'contentOptions' => ['style' => 'white-space: nowrap']
                ],
                [
                    'attribute' => 'updated_at',
                    'format' => ['datetime', 'php:d-m-Y H:i:s'],
                    'contentOptions' => ['style' => 'white-space: nowrap']
                ],
                // 'created_at::datetime',
                // 'updated_at::datetime',
                //'created_by',
                //'updated_by',
                [
                    'class' => ActionColumn::className(),
                    /*
                    'urlCreator' => function ($action, Product $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id' => $model->id]);
                    },
                    */
                    'contentOptions' => ['class' => 'td-actions']
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
