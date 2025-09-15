<?php

namespace frontend\controllers;

use yii\filters\AccessControl;
use Yii;
use yii\web\Controller;
use common\models\Product;

class ProductController extends \frontend\base\Controller
{
    public function actionView($id)
    {
        $product = Product::findOne($id);
        return $this->render('view', [
            'product' => $product,
        ]);
    }

    
}