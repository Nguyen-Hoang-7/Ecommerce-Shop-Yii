<?php

$orderAddress = $order->orderAddress;
$ward = \common\models\Locality::getCode($orderAddress->ward_code);
$ward_name = $ward ? $ward->name : '';
$district = \common\models\Locality::getCode($orderAddress->district_code);
$district_name = $district ? $district->name : '';
$province = \common\models\Locality::getCode($orderAddress->province_code);
$province_name = $province ? $province->name : '';
?>

Order #<?php echo $order->id ?> Summary:

Account Information
    First Name: <?php echo $order->firstName ?>
    Last Name: <?php echo $order->lastName ?>
    Email: <?php echo $order->email ?>

Address Information
    Address: <?php echo $orderAddress->address ?>
    Ward: <?php echo $ward_name ?>
    <?php if (!empty($district_name)): ?>
    District: <?php echo $district_name ?>
    <?php endif; ?>
    Province: <?php echo $province_name ?>
    Full Address: <?php echo $orderAddress->full_address ?>

Products
     Name       Quantity        Price
<?php foreach ($order->orderItems as $item): ?>
    <img src="<?php echo $item->product->getImageUrl() ?>"
         style="width: 50px;"
         alt="<?php echo $item->product_name ?>">       <?php echo $item->product_name ?>      <?php echo $item->quantity ?>       <?php echo Yii::$app->formatter->asCurrency($item->unit_price) ?></td>
<?php endforeach; ?>
Total Items: <?php echo $order->getItemsQuantity() ?>
Total Price: <?php echo $order->total_price ?>

