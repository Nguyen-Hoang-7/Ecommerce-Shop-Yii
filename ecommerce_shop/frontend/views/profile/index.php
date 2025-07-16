<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
?>

<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                Address Information
            </div>
            <div class="card-body">
                <?php echo $this->render('user_address', [
                    'userAddress' => $userAddress,
                ]); ?>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card">
            <div class="card-header">
                Account Information
            </div>
            <div class="card-body">
                <?php echo $this->render('user_account', [
                    'user' => $user,
                ]); ?>
            </div>
        </div>
    </div>
</div>