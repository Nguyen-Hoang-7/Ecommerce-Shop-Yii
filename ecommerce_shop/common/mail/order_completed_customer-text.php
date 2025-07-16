<?php

$orderAddress = $order->orderAddress;
?>

Order #<?php echo $order->id ?> Summary:

Account Information
    First Name: <?php echo $order->firstName ?>
    Last Name: <?php echo $order->lastName ?>
    Email: <?php echo $order->email ?>

Address Information
    Address: <?php echo $orderAddress->address ?>
    City: <?php echo $orderAddress->city ?>
    State: <?php echo $orderAddress->state ?>
    Country: <?php echo $orderAddress->country ?>
    Zipcode: <?php echo $orderAddress->zipcode ?>

Products
     Name       Quantity        Price
<?php foreach ($order->orderItems as $item): ?>
    <img src="<?php echo $item->product->getImageUrl() ?>"
         style="width: 50px;"
         alt="<?php echo $item->product_name ?>">       <?php echo $item->product_name ?>      <?php echo $item->quantity ?>       <?php echo Yii::$app->formatter->asCurrency($item->unit_price) ?></td>
<?php endforeach; ?>
Total Items: <?php echo $order->getItemsQuantity() ?>
Total Price: <?php echo $order->total_price ?>

