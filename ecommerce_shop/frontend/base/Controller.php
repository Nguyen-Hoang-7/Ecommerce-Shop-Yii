<?php

namespace frontend\base;

use common\models\CartItem;
use Yii;

class Controller extends \yii\web\Controller
{
    public function beforeAction($action)
    {
        $this->view->params['cartItemCount'] = CartItem::getTotalQuantityForUser(Yii::$app->user->id);
        //find()->userId(Yii::$app->user->id)->count();
        return parent::beforeAction($action);
    }
}