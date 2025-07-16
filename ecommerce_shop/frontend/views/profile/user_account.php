<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
?>

<?php \yii\widgets\Pjax::begin(['id' => 'address-pjax', 'enablePushState' => false]); ?>

<?php if (isset($success) && $success): ?>
    <div class="alert alert-success">
        Your account was updated successfully.
    </div>
<?php endif; ?>

<?php $accountForm = ActiveForm::begin(['id' => 'form-account',
                    'action' => ['profile/update-account'],
                    'options' => [
                        'data-pjax' => true,
                    ]]); ?>
                    <div class="row">
                        <div class="col-lg-6">
                            <?= $accountForm->field($user, 'firstname')->textInput(['autofocus' => true]) ?>
                        </div>
                        <div class="col-lg-6">
                            <?= $accountForm->field($user, 'lastname')->textInput(['autofocus' => true]) ?>
                        </div>
                    </div>

                    <?= $accountForm->field($user, 'username')->textInput(['autofocus' => true]) ?>

                    <?= $accountForm->field($user, 'email') ?>

                    <div class="row">
                        <div class="col-lg-6">
                            <?= $accountForm->field($user, 'password')->passwordInput() ?>
                        </div>
                        <div class="col-lg-6">
                            <?= $accountForm->field($user, 'passwordConfirm')->passwordInput() ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
                    </div>

                <?php ActiveForm::end(); ?>
                <?php \yii\widgets\Pjax::end(); ?>