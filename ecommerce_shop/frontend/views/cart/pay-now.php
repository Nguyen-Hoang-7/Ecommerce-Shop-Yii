<?php
$orderAddress = $order->orderAddress;
?>

<script src="https://www.paypal.com/sdk/js?client-id=<?php echo Yii::$app->params['paypalClientId'] ?>"></script>

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
            <tbody>
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
            </tbody>
        </table>
        <hr>
        <table class="table">
            <tr>
                <th>Total Items</th>
                <td><?php echo $order->getItemsQuantity() ?></td>
            </tr>
            <tr>
                <th>Total Price</th>
                <td><?php echo Yii::$app->formatter->asCurrency($order->total_price) ?></td>
            </tr>

        </table>
        <div id="paypal-button-container"></div>
    </div>
</div>

<script>
    paypal.Buttons({
        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{

                    amount: {
                        value: '<?php echo $exchangePrice ?>'
                    }
                }]
            });
        },
        onApprove: function(data, actions) {
            console.log(data, actions);
            // This function captures the funds from the transaction.
            return actions.order.capture().then(function(details) {
                console.log(details)
                const $form = $('#checkout-form');
                const formData = $form.serializeArray();
                // debugger;
                formData.push({
                    name: 'transaction_id',
                    value: details.id
                })
                formData.push({
                    name: 'order_id',
                    value: data.orderID
                })

                formData.push({
                    name: 'status',
                    value: details.status
                })
                $.ajax({
                    url: '<?php echo \yii\helpers\Url::to(["/cart/submit-payment", "orderId" => $order->id]) ?>',
                    method: 'POST',
                    data: data,
                    success: function(response) {
                        // Handle successful order creation
                        console.log('Order created successfully:', response);
                        alert('Transaction completed. Thanks for your business, ' + details.payer.name.given_name);
                        window.location.href = '';
                        // Redirect to a success page or update the UI accordingly
                    },
                    // error: function(xhr, status, error) {
                    //     // Handle errors
                    //     console.error('Error creating order:', error);
                    // }
                })
                // Show a success message to the buyer

                // Submit the form to create the order in your system
                //document.getElementById('checkout-form').submit();
            });
        },
    }).render('#paypal-button-container')
</script>