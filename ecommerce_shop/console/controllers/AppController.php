<?php
/**
 * User: TheCodeholic
 * Date: 12/29/2020
 * Time: 3:08 PM
 */

namespace console\controllers;


use common\models\User;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * Class AppController
 *
 * @author  Zura Sekhniashvili <zurasekhniashvili@gmail.com>
 * @package console\controllers
 */
class AppController extends Controller
{
    public function actionCreateAdminUser($username, $email, $firstname = null, $lastname = null, $password = null)
    {
        $user = new User();
        $user->firstname = $firstname;
        $user->lastname = $lastname;
        $user->email = $email;
        $user->username = $username;
        $user->admin = 1;
        $user->status = User::STATUS_ACTIVE;
        $user->auth_key = \Yii::$app->security->generateRandomString();
        $user->password_hash = \Yii::$app->security->generatePasswordHash($password);
        // $user->setPassword($password);
        if ($user->save()) {
            Console::output("User has been created");
            Console::output("Username: ".$username);
            Console::output("Password: ".$password);
        } else {
            Console::error("User \"$username\" was not created");
            var_dump($user->errors);
        }

    }
}