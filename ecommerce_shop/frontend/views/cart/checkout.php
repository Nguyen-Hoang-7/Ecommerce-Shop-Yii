
<?php
/**
 * User: TheCodeholic
 * Date: 12/12/2020
 * Time: 8:12 PM
 */
/** @var \common\models\Order $order */
/** @var \common\models\OrderAddress $orderAddress */
/** @var array $cartItems */
/** @var int $productQuantity */

/** @var float $totalPrice */

use yii\bootstrap5\ActiveForm;
use common\models\Product;
?>


<?php $form = ActiveForm::begin([
    'id' => 'checkout-form',
    'action' => ['/cart/checkout'],
    'method' => 'post'
]); ?>
<div class="row">
    <div class="col">

        <div class="card mb-3">
            <div class="card-header">
                <h5>Account information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($order, 'firstName')->textInput(['autofocus' => true]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($order, 'lastName')->textInput(['autofocus' => true]) ?>
                    </div>
                </div>
                <?= $form->field($order, 'email')->textInput(['autofocus' => true]) ?>

            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5>Address information</h5>
            </div>
            <div class="card-body">
                <?= $form->field($orderAddress, 'address')->label('Your address') ?>
                
                <!-- Checkbox để chọn loại địa chỉ -->
                <div class="form-group mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="use-old-address" name="use_old_address">
                        <label class="form-check-label" for="use-old-address">
                            Use old address format 
                        </label>
                    </div>
                </div>
                
                <!-- Layout địa chỉ cũ (3 ô) -->
                <div id="old-address-layout" class="address-layout" style="display: none;">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">City</label>
                                <select class="form-control" id="province-select-old" name="province_code_old">
                                    <option value="">Select City</option>
                                    <?php foreach (\common\models\Locality::getNameAddressOptions(1, 'O') as $code => $name): ?>
                                        <option value="<?= $code ?>"><?= $name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">District</label>
                                <select class="form-control" id="district-select-old" name="district_code_old">
                                    <option value="">Select District</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Ward</label>
                                <select class="form-control" id="ward-select-old" name="ward_code_old">
                                    <option value="">Select Ward</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Layout địa chỉ mới (2 ô) -->
                <div id="new-address-layout" class="address-layout">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">City</label>
                                <select class="form-control" id="province-select-new" name="province_code_new">
                                    <option value="">Select City</option>
                                    <?php foreach (\common\models\Locality::getNameAddressOptions(1, 'N') as $code => $name): ?>
                                        <option value="<?= $code ?>"><?= $name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Ward</label>
                                <select class="form-control" id="ward-select-new" name="ward_code_new">
                                    <option value="">Select Ward</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hidden fields để lưu giá trị cuối cùng -->
        <?= $form->field($orderAddress, 'province_code')->hiddenInput(['id' => 'final-province-code'])->label(false) ?>
        <?= $form->field($orderAddress, 'district_code')->hiddenInput(['id' => 'final-district-code'])->label(false) ?>
        <?= $form->field($orderAddress, 'ward_code')->hiddenInput(['id' => 'final-ward-code'])->label(false) ?>

    </div>
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h5>Order Summary</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($cartItems as $item): ?>
                        <tr data-id="<?php echo $item['id'] ?>" data-url="<?php echo \yii\helpers\Url::to(['/cart/change-quantity']) ?>">
                            <td><?php echo $item['name'] ?></td>
                            <td>
                                <img src="<?php echo Yii::$app->request->baseUrl . '/storage/products/'. $item['image'] ?>"
                                     alt="<?php echo $item['image'] ?>"
                                     style="width: 150px">
                            </td>
                            <td><?php echo Yii::$app->formatter->asCurrency($item['price']) ?></td>
                            <td>
                                <?php echo $item['quantity'] ?>
                            </td>
                            <td><?php echo Yii::$app->formatter->asCurrency($item['total_price']) ?></td>
                            <td>
                                <?php echo \yii\helpers\Html::a('Delete', ['/cart/delete', 'id' => $item['id']], [
                                    'class' => 'btn btn-outline-danger btn-sm',
                                    'data-method' => 'post',
                                    'data-confirm' => 'Are you sure you want to delete this product from your cart?',
                                ]) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <hr>
                <table class="table">
                    <tr>
                        <td>Total Items</td>
                        <td class="text-right"><?php echo $productQuantity ?></td>
                    </tr>
                    <tr>
                        <td>Total Price</td>
                        <td class="text-right">
                            <?php echo Yii::$app->formatter->asCurrency($totalPrice) ?>
                        </td>
                    </tr>
                </table>

                <p class="text-right mt-3">
                    <button type="submit" class="btn btn-primary" id="checkout-btn">Checkout</button>
                </p>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Checkout script loaded');
    
    // Toggle giữa 2 layout địa chỉ
    var checkbox = document.getElementById('use-old-address');
    var oldLayout = document.getElementById('old-address-layout');
    var newLayout = document.getElementById('new-address-layout');
    
    if (checkbox) {
        checkbox.addEventListener('change', function() {
            console.log('Checkbox changed:', this.checked);
            if (this.checked) {
                if (oldLayout) oldLayout.style.display = 'block';
                if (newLayout) newLayout.style.display = 'none';
                console.log('Showing old layout');
                syncOldLayoutValues();
            } else {
                if (oldLayout) oldLayout.style.display = 'none';
                if (newLayout) newLayout.style.display = 'block';
                console.log('Showing new layout');
                syncNewLayoutValues();
            }
        });
    }

    // Xử lý cho layout địa chỉ cũ (3 ô)
    // Load districts khi chọn province trong layout cũ
    var provinceSelectOld = document.getElementById('province-select-old');
    if (provinceSelectOld) {
        provinceSelectOld.addEventListener('change', function() {
            var provinceCode = this.value;
            var districtSelect = document.getElementById('district-select-old');
            var wardSelect = document.getElementById('ward-select-old');
            
            // Reset district and ward
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
                        syncOldLayoutValues();
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                syncOldLayoutValues();
            }
        });
    }
    
    // Load wards khi chọn district trong layout cũ
    var districtSelectOld = document.getElementById('district-select-old');
    if (districtSelectOld) {
        districtSelectOld.addEventListener('change', function() {
            var districtCode = this.value;
            var wardSelect = document.getElementById('ward-select-old');
            
            // Reset ward
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
                        syncOldLayoutValues();
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                syncOldLayoutValues();
            }
        });
    }

    // Xử lý cho layout địa chỉ mới (2 ô)
    // Load wards khi chọn province trong layout mới
    var provinceSelectNew = document.getElementById('province-select-new');
    if (provinceSelectNew) {
        provinceSelectNew.addEventListener('change', function() {
            var provinceCode = this.value;
            var wardSelect = document.getElementById('ward-select-new');
            
            // Reset ward
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
                        syncNewLayoutValues();
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                syncNewLayoutValues();
            }
        });
    }
    
    // Thêm event listeners riêng cho các dropdown để sync khi user chọn
    var wardSelectOld = document.getElementById('ward-select-old');
    if (wardSelectOld) {
        wardSelectOld.addEventListener('change', function() {
            syncOldLayoutValues();
        });
    }
    
    var wardSelectNew = document.getElementById('ward-select-new');
    if (wardSelectNew) {
        wardSelectNew.addEventListener('change', function() {
            syncNewLayoutValues();
        });
    }
    
    // Sync giá trị từ layout cũ vào hidden fields
    function syncOldLayoutValues() {
        var provinceOld = document.getElementById('province-select-old');
        var districtOld = document.getElementById('district-select-old');
        var wardOld = document.getElementById('ward-select-old');
        var finalProvince = document.getElementById('final-province-code');
        var finalDistrict = document.getElementById('final-district-code');
        var finalWard = document.getElementById('final-ward-code');
        
        if (finalProvince && provinceOld) finalProvince.value = provinceOld.value || '';
        if (finalDistrict && districtOld) finalDistrict.value = districtOld.value || '';
        if (finalWard && wardOld) finalWard.value = wardOld.value || '';
        
        console.log('Synced old layout values:', {
            province: finalProvince ? finalProvince.value : 'null',
            district: finalDistrict ? finalDistrict.value : 'null',
            ward: finalWard ? finalWard.value : 'null'
        });
    }

    // Sync giá trị từ layout mới vào hidden fields
    function syncNewLayoutValues() {
        var provinceNew = document.getElementById('province-select-new');
        var wardNew = document.getElementById('ward-select-new');
        var finalProvince = document.getElementById('final-province-code');
        var finalDistrict = document.getElementById('final-district-code');
        var finalWard = document.getElementById('final-ward-code');
        
        if (finalProvince && provinceNew) finalProvince.value = provinceNew.value || '';
        if (finalDistrict) finalDistrict.value = ''; // No district in new layout
        if (finalWard && wardNew) finalWard.value = wardNew.value || '';
        
        console.log('Synced new layout values:', {
            province: finalProvince ? finalProvince.value : 'null',
            district: finalDistrict ? finalDistrict.value : 'null',
            ward: finalWard ? finalWard.value : 'null'
        });
    }

    // Debug: Thêm event listener cho nút checkout
    var checkoutBtn = document.getElementById('checkout-btn');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', function(e) {
            console.log('Checkout button clicked!');
            // Sync values trước khi submit
            var checkbox = document.getElementById('use-old-address');
            if (checkbox && checkbox.checked) {
                syncOldLayoutValues();
            } else {
                syncNewLayoutValues();
            }
            console.log('Form data before submit:', new FormData(document.getElementById('checkout-form')));
        });
    }

    // Debug: Thêm event listener cho form submit
    var checkoutForm = document.getElementById('checkout-form');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            console.log('Form submit event triggered!');
            console.log('Form action:', this.action);
            console.log('Form method:', this.method);
            
            // Kiểm tra dữ liệu form
            var formData = new FormData(this);
            console.log('Form data:');
            for (var pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }
        });
    }
    
    // Debug: Kiểm tra hidden fields có tồn tại không
    console.log('Hidden fields check:');
    console.log('final-province-code:', document.getElementById('final-province-code'));
    console.log('final-district-code:', document.getElementById('final-district-code'));
    console.log('final-ward-code:', document.getElementById('final-ward-code'));
    
    // Sync values khi page load
    syncNewLayoutValues();
});
</script>

