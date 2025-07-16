<?php
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
?>

<?php \yii\widgets\Pjax::begin(['id' => 'address-pjax', 'enablePushState' => false]); ?>

<?php if (isset($success) && $success): ?>
    <div class="alert alert-success">
        Your address was updated successfully.
    </div>
<?php endif; ?>

                <?php $addressForm = ActiveForm::begin(['id' => 'form-address',
                    'action' => ['profile/update-address'],
                    'options' => [
                        'data-pjax' => true,
                    ]]); ?>
                    <?= $addressForm->field($userAddress, 'address')->textInput(['autofocus' => true]) ?>
                    <?= $addressForm->field($userAddress, 'city')->textInput(['autofocus' => true]) ?>
                    <?= $addressForm->field($userAddress, 'state')->textInput(['autofocus' => true]) ?>
                    <?= $addressForm->field($userAddress, 'country')->textInput(['autofocus' => true]) ?>
                    <?= $addressForm->field($userAddress, 'zipcode')->textInput(['autofocus' => true]) ?>
                    <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
                <?php ActiveForm::end(); ?>
                <?php \yii\widgets\Pjax::end(); ?>