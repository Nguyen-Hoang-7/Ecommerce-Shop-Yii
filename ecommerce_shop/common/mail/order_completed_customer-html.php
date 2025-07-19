<?php

$orderAddress = $order->orderAddress;
?>

<style>
    .row {
        display: flex;
    }
    .col {
        flex: 1;
    }
</style>
<h3>Order #<?php echo $order->id ?> Summary:</h3>
<div class="row">
    <div class="col">
        <h4>Account Information</h4>
        <table class="table">
            <tr>
                <th>First Name</th>
                <td><?php echo $order->firstName ?></td>
            </tr>
            <tr>
                <th>Last Name</th>
                <td><?php echo $order->lastName ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo $order->email ?></td>
            </tr>
        </table>
        <h4>Address Information</h4>
        <table class="table">

            <tr>
                <th>Address</th>
                <td><?php echo $orderAddress->address ?></td>
            </tr>
            <tr>
                <th>City</th>
                <td><?php echo $orderAddress->city ?></td>
            </tr>
            <tr>
                <th>State</th>
                <td><?php echo $orderAddress->state ?></td>
            </tr>
            <tr>
                <th>Country</th>
                <td><?php echo $orderAddress->country ?></td>
            </tr>
            <tr>
                <th>Zipcode</th>
                <td><?php echo $orderAddress->zipcode ?></td>
            </tr>

        </table>
    </div>
    <div class="col">
        <table class="table table-sm">
            <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
            </thead>
            <?php foreach ($order->orderItems as $item): ?>
                <tr>
                    <td>
                        <img src="<?php echo $item->product->getImageUrl() ?>"
                         style="width: 50px;"
                         alt="<?php echo $item->product_name ?>">
                    </td>
                    <td><?php echo $item->product_name ?></td>
                    <td><?php echo $item->quantity ?></td>
                    <td><?php echo Yii::$app->formatter->asCurrency($item->unit_price) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <hr>
        <table class="table">
            <tr>
                <th>Total Items</th>
                <td><?php echo $order->getItemsQuantity() ?></td>
            </tr>
            <tr>
                <th>Total Price</th>
                <td><?php echo $order->total_price ?></td>
            </tr>

        </table>

    </div>
</div>
