<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Product $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
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
            [
                'attribute' => 'name',
                'options' => ['style' => 'white-space: nowrap']
            ],
            'description:html',
            [
                'attribute' => 'image',
                'format' => 'html',
                'value' => function ($model) {
                    return Html::img($model->getImageUrl(), ['alt' => $model->name, 'width' => '150', 'height' => '150']);
                }
            ],
            'price:currency',
            [
                'attribute' => 'status',
                'format' => 'html',
                'value' => function ($model) {
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
            // 'status',
            // 'created_at',
            // 'updated_at',
            'createdBy.username',
            'updatedBy.username',
        ],
    ]) ?>

</div>
