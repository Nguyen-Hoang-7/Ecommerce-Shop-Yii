<?php

namespace common\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends \common\models\PasswordResetRequestForm
{
    public function rules() {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\common\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE, 'admin' => 1],
                'message' => 'There is no user with this email address.'
            ],
        ];
    }
    protected function findUser() {
        return User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $user,
            'admin' => 1,
        ]);
    }
}
