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
                    
                    <?= $addressForm->field($userAddress, 'address')->textInput(['autofocus' => true])->label('Address') ?>
                    
                    <!-- Checkbox để chọn loại địa chỉ -->
                    <div class="form-group mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="use-old-address-user" name="use_old_address_user">
                            <label class="form-check-label" for="use-old-address-user">
                                Use old address format
                            </label>
                        </div>
                    </div>
                    
                    <!-- Layout địa chỉ cũ (3 ô) -->
                    <div id="old-address-layout-user" class="address-layout" style="display: none;">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">City</label>
                                    <select class="form-control" id="province-select-old-user" name="province_code_old_user">
                                        <option value="">City</option>
                                        <?php foreach (\common\models\Locality::getNameAddressOptions(1, 'O') as $code => $name): ?>
                                            <option value="<?= $code ?>" <?= $userAddress->province_code == $code ? 'selected' : '' ?>><?= $name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">District</label>
                                    <select class="form-control" id="district-select-old-user" name="district_code_old_user">
                                        <option value="">District</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Ward</label>
                                    <select class="form-control" id="ward-select-old-user" name="ward_code_old_user">
                                        <option value="">Ward</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Layout địa chỉ mới (2 ô) -->
                    <div id="new-address-layout-user" class="address-layout">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">City</label>
                                    <select class="form-control" id="province-select-new-user" name="province_code_new_user">
                                        <option value="">City</option>
                                        <?php foreach (\common\models\Locality::getNameAddressOptions(1, 'N') as $code => $name): ?>
                                            <option value="<?= $code ?>" <?= $userAddress->province_code == $code ? 'selected' : '' ?>><?= $name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Ward</label>
                                    <select class="form-control" id="ward-select-new-user" name="ward_code_new_user">
                                        <option value="">Ward</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Hidden fields để lưu giá trị cuối cùng -->
                    <?= $addressForm->field($userAddress, 'province_code')->hiddenInput(['id' => 'final-province-code-user'])->label(false) ?>
                    <?= $addressForm->field($userAddress, 'district_code')->hiddenInput(['id' => 'final-district-code-user'])->label(false) ?>
                    <?= $addressForm->field($userAddress, 'ward_code')->hiddenInput(['id' => 'final-ward-code-user'])->label(false) ?>
                    
                    <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
                <?php ActiveForm::end(); ?>
                <?php \yii\widgets\Pjax::end(); ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('User address script loaded');
    
    // Toggle giữa 2 layout địa chỉ
    var checkbox = document.getElementById('use-old-address-user');
    var oldLayout = document.getElementById('old-address-layout-user');
    var newLayout = document.getElementById('new-address-layout-user');
    
    if (checkbox) {
        checkbox.addEventListener('change', function() {
            console.log('Checkbox changed:', this.checked);
            if (this.checked) {
                if (oldLayout) oldLayout.style.display = 'block';
                if (newLayout) newLayout.style.display = 'none';
                console.log('Showing old layout');
                syncOldLayoutValuesUser();
            } else {
                if (oldLayout) oldLayout.style.display = 'none';
                if (newLayout) newLayout.style.display = 'block';
                console.log('Showing new layout');
                syncNewLayoutValuesUser();
            }
        });
    }
    
    // Function to sync values from old layout to hidden fields
    function syncOldLayoutValuesUser() {
        var provinceOld = document.getElementById('province-select-old-user');
        var districtOld = document.getElementById('district-select-old-user');
        var wardOld = document.getElementById('ward-select-old-user');
        var finalProvince = document.getElementById('final-province-code-user');
        var finalDistrict = document.getElementById('final-district-code-user');
        var finalWard = document.getElementById('final-ward-code-user');
        
        if (finalProvince && provinceOld) finalProvince.value = provinceOld.value || '';
        if (finalDistrict && districtOld) finalDistrict.value = districtOld.value || '';
        if (finalWard && wardOld) finalWard.value = wardOld.value || '';
    }
    
    // Function to sync values from new layout to hidden fields
    function syncNewLayoutValuesUser() {
        var provinceNew = document.getElementById('province-select-new-user');
        var wardNew = document.getElementById('ward-select-new-user');
        var finalProvince = document.getElementById('final-province-code-user');
        var finalDistrict = document.getElementById('final-district-code-user');
        var finalWard = document.getElementById('final-ward-code-user');
        
        if (finalProvince && provinceNew) finalProvince.value = provinceNew.value || '';
        if (finalDistrict) finalDistrict.value = ''; // No district in new layout
        if (finalWard && wardNew) finalWard.value = wardNew.value || '';
    }

    // Xử lý cho layout địa chỉ cũ (3 ô)
    var provinceSelectOld = document.getElementById('province-select-old-user');
    if (provinceSelectOld) {
        provinceSelectOld.addEventListener('change', function() {
            var provinceCode = this.value;
            var districtSelect = document.getElementById('district-select-old-user');
            var wardSelect = document.getElementById('ward-select-old-user');
            
            if (districtSelect) districtSelect.innerHTML = '<option value="">District</option>';
            if (wardSelect) wardSelect.innerHTML = '<option value="">Ward</option>';
            
            if (provinceCode) {
                fetch('<?= \yii\helpers\Url::to(['/cart/get-address-children']) ?>?parent_code=' + provinceCode + '&locality_type=2&status=O')
                    .then(response => response.json())
                    .then(data => {
                        if (districtSelect) {
                            Object.keys(data).forEach(function(key) {
                                var option = document.createElement('option');
                                option.value = key;
                                option.textContent = data[key];
                                districtSelect.appendChild(option);
                            });
                        }
                        syncOldLayoutValuesUser();
                    })
                    .catch(error => console.error('Error:', error));
            }
        });
    }
    
    // Load wards khi chọn district trong layout cũ
    var districtSelectOld = document.getElementById('district-select-old-user');
    if (districtSelectOld) {
        districtSelectOld.addEventListener('change', function() {
            var districtCode = this.value;
            var wardSelect = document.getElementById('ward-select-old-user');
            
            if (wardSelect) wardSelect.innerHTML = '<option value="">Ward</option>';
            
            if (districtCode) {
                fetch('<?= \yii\helpers\Url::to(['/cart/get-address-children']) ?>?parent_code=' + districtCode + '&locality_type=3&status=O')
                    .then(response => response.json())
                    .then(data => {
                        if (wardSelect) {
                            Object.keys(data).forEach(function(key) {
                                var option = document.createElement('option');
                                option.value = key;
                                option.textContent = data[key];
                                wardSelect.appendChild(option);
                            });
                        }
                        syncOldLayoutValuesUser();
                    })
                    .catch(error => console.error('Error:', error));
            }
        });
    }

    // Xử lý cho layout địa chỉ mới (2 ô)
    var provinceSelectNew = document.getElementById('province-select-new-user');
    if (provinceSelectNew) {
        provinceSelectNew.addEventListener('change', function() {
            var provinceCode = this.value;
            var wardSelect = document.getElementById('ward-select-new-user');
            
            if (wardSelect) wardSelect.innerHTML = '<option value="">Ward</option>';
            
            if (provinceCode) {
                fetch('<?= \yii\helpers\Url::to(['/cart/get-address-children']) ?>?parent_code=' + provinceCode + '&locality_type=3&status=N')
                    .then(response => response.json())
                    .then(data => {
                        if (wardSelect) {
                            Object.keys(data).forEach(function(key) {
                                var option = document.createElement('option');
                                option.value = key;
                                option.textContent = data[key];
                                wardSelect.appendChild(option);
                            });
                        }
                        syncNewLayoutValuesUser();
                    })
                    .catch(error => console.error('Error:', error));
            }
        });
    }
    
    // Initialize sync based on current values
    syncNewLayoutValuesUser();
});
</script>