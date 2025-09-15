<?php

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var string $searchTerm */

use yii\helpers\Html;
use yii\widgets\ListView;

$this->title = 'Kết quả tìm kiếm';
?>

<div class="search-results">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="text-center mb-4">
                    <?php if (!empty($searchTerm)): ?>
                        Kết quả tìm kiếm cho: "<strong><?= Html::encode($searchTerm) ?></strong>"
                    <?php else: ?>
                        Tìm kiếm sản phẩm
                    <?php endif; ?>
                </h3>
                
                <?php if (!empty($searchTerm) && $dataProvider->totalCount > 0): ?>
                    <div class="search-info mb-4">
                        <p class="text-muted">
                            Tìm thấy <strong><?= $dataProvider->totalCount ?></strong> sản phẩm
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if ($dataProvider->totalCount > 0): ?>
            <div class="row">
                <?php
                echo ListView::widget([
                    'dataProvider' => $dataProvider,
                    'itemView' => '_product_item',
                    'layout' => '<div class="row">{items}</div><div class="row"><div class="col-12">{pager}</div></div>',
                    'itemOptions' => [
                        'class' => 'col-lg-4 col-md-6 mb-4'
                    ],
                    'pager' => [
                        'class' => 'yii\bootstrap5\LinkPager',
                        'options' => ['class' => 'pagination justify-content-center'],
                    ]
                ]);
                ?>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-12 text-center">
                    <div class="no-results">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <?php if (!empty($searchTerm)): ?>
                            <h4 class="text-muted">Không tìm thấy sản phẩm nào</h4>
                            <p class="text-muted">
                                Hãy thử tìm kiếm với từ khóa khác hoặc 
                                <a href="<?= \yii\helpers\Url::to(['/site/index']) ?>">xem tất cả sản phẩm</a>
                            </p>
                        <?php else: ?>
                            <h4 class="text-muted">Vui lòng nhập từ khóa tìm kiếm</h4>
                            <p class="text-muted">
                                Bạn cần nhập từ khóa để tìm kiếm sản phẩm hoặc 
                                <a href="<?= \yii\helpers\Url::to(['/site/index']) ?>">xem tất cả sản phẩm</a>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.search-results {
    padding: 20px 0;
    min-height: 400px;
}

.search-info {
    border-bottom: 1px solid #dee2e6;
    padding-bottom: 15px;
}

.no-results {
    padding: 60px 20px;
}

.no-results i {
    opacity: 0.5;
}

@media (max-width: 768px) {
    .search-results {
        padding: 10px 0;
    }
    
    .no-results {
        padding: 40px 10px;
    }
}
</style>
