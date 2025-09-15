<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Product $product */

$this->title = "Product Detail";
?>

<style>
.product-view {
    padding: 20px 0;
}

.product-description {
    line-height: 1.6;
    margin: 20px 0;
    padding: 0 15px;
}

.product-description p {
    margin-bottom: 15px;
    text-align: justify;
}

.product-description h1, 
.product-description h2, 
.product-description h3 {
    margin-top: 20px;
    margin-bottom: 10px;
    color: #333;
}

.product-description ul, 
.product-description ol {
    margin: 15px 0;
    padding-left: 30px;
}

.product-description li {
    margin-bottom: 5px;
}

.card {
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    overflow: hidden;
    background-color: #f2f7f5;
}

.wrapper {
    padding: 30px;
    align-items: center;
}

.wrapper .col-xl-6 {
    padding: 20px;
}

.product-image {
    text-align: center;
    padding: 20px;
}

.product-image img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.product-info {
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    height: 100%;
}

.product-info h2 {
    margin-bottom: 20px;
    color: #333;
    font-weight: 600;
    font-size: 1.8em;
}

.product-price {
    padding: 0 15px;
}

.product-price p {
    font-size: 1.5em;
    font-weight: bold;
    color:rgb(99, 126, 201);
    margin-bottom: 0;
    display: inline-block;
}

.product-price h4 {
    display: inline-block;
    margin-right: 10px;
}

@media (max-width: 768px) {
    .wrapper {
        padding: 15px;
    }
    
    .wrapper .col-xl-6 {
        padding: 10px;
    }
    
    .product-image,
    .product-info {
        padding: 15px;
    }
    
    .product-info h2 {
        font-size: 1.5em;
    }
    
    .product-price p {
        font-size: 1.3em;
    }
}
</style>


<div class="product-view">
    <div style="text-align: center">
        <h1 class="text-info"><?= Html::encode($this->title) ?></h1>
    </div>
    
    <div class="card" style="margin-top: 30px">
        <div class="container-fluid">
            <div class="wrapper row">
                <div class="col-xl-6 col-lg-6 col-12">
                    <div class="product-image">
                        <img src="<?php echo $product->getImageUrl() ?>" alt="<?php echo $product->name ?>" class="img-fluid">
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-12">
                    <div class="product-info">
                        <h2><?= Html::encode($product->name) ?></h2>

                        <div class="product-description">
                            <h4 class="text-warning">Product Description</h4>
                            <?= html_entity_decode($product->description, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                        <div class="product-price">
                            <h4 class="text-warning"><span>Product Price: </span></h4>
                            <p><?= Html::encode(Yii::$app->formatter->asCurrency($product->price)) ?></p>
                        </div>

                        <div class="product-add-to-cart product-item" style="margin-top: 20px" data-key="<?= $product->id ?>">
                            <a href="<?php echo \yii\helpers\Url::to(['/cart/add']) ?>" class="btn btn-primary btn-add-to-cart">
                                Add to Cart
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>