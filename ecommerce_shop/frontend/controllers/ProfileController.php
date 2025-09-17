<?php

namespace frontend\controllers;

use yii\filters\AccessControl;
use Yii;
use yii\web\Controller;

class ProfileController extends \frontend\base\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'update-address', 'update-account'],
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
        ];
    }

    public function actionIndex() {
        $user = Yii::$app->user->identity;
        $userAddresses = $user->addresses;
        $userAddress = $user->getAddress(); // Get the first address or create a new one
        if (!empty($userAddresses)) {
            $userAddress = $userAddresses[0]; // Use the first address if exists
        }

        return $this->render('index', [
            'user' => $user,
            'userAddress' => $userAddress,
        ]);
    }

    public function actionUpdateAddress() {
        $user = Yii::$app->user->identity;
        $userAddress = $user->getAddress();
        $success = false;
        
        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post();
            
            // Load dữ liệu từ form
            $userAddress->address = $postData['UserAddress']['address'] ?? '';
            $userAddress->province_code = $postData['UserAddress']['province_code'] ?? '';
            $userAddress->district_code = $postData['UserAddress']['district_code'] ?? '';
            $userAddress->ward_code = $postData['UserAddress']['ward_code'] ?? '';
            
            // full_address sẽ được tự động tạo trong beforeSave()
            if ($userAddress->save()) {
                $success = true;
                Yii::$app->session->setFlash('success', 'Your address was updated successfully.');
                return $this->redirect(['index']);
            }
        }

        return $this->render('user_address', [
            'success' => $success,
            'userAddress' => $userAddress,
        ]);
    }

    public function actionUpdateAccount() {
        $user = Yii::$app->user->identity;
        $user->scenario = \common\models\User::SCENARIO_UPDATE; // Set scenario for update
        // $userAddress = $user->getAddress();
        $success = false;
        if ($user->load(Yii::$app->request->post()) && $user->save()) {
            $success = true;
            Yii::$app->session->setFlash('success', 'Your account was updated successfully.');
            return $this->redirect(['index']);
        }

        return $this->render('user_account', [
            'success' => $success,
            'user' => $user,
        ]);
    }
}