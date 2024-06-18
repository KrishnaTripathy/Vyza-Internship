<?php
session_start(); // Start the session

// Include necessary files
// include("header.php");
// require_once("PaytmChecksum.php");

// Function to remove an item from the cart
if (isset($_POST['Remove_Item'])) {
    $item_name = $_POST['Item_Name'];
    foreach ($_SESSION['cart'] as $key => $value) {
        if ($value['Item_Name'] == $item_name) {
            unset($_SESSION['cart'][$key]); // Remove the item from the cart
        }
    }
}

// Calculate total amount
$total = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $key => $value) {
        $total += $value['Price'];
    }
}

// Store total amount in session
$_SESSION['totalAmount'] = $total;

?>
<!DOCTYPE html>
<html>

<head>
    <title>Cart</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

</head>

<body style="background-repeat: no-repeat; margin:25px;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center border rounded bg-light my-5">
                <h1>My Cart</h1>
            </div>
            <div class="col-xs-6 col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">Charge Rs.100 INR </h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label>Serial No.</label>
                            <input type="text" class="form-control" name="billing_serial" id="billing_serial" placeholder="Enter Serial No." required="" autofocus="">
                        </div>
                        <div class="form-group">
                            <label>Item Name</label>
                            <input type="name" class="form-control" name="billing_name" id="billing_name" placeholder="Enter Item Name" required="">
                        </div>

                        <div class="form-group">
                            <label>Item Price</label>
                            <input type="number" class="form-control" name="billing_price" id="billing_price" min-length="10" max-length="10" placeholder="Enter Item Price" required="" autofocus="">
                        </div>
                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="number" class="form-control" name="billing_quantity" id="billing_quantity" min-length="10" max-length="10" placeholder="Enter Quantity" required="" autofocus="">
                        </div>

                        <div class="form-group">
                            <label>Payment Amount</label>
                            <input type="text" class="form-control" name="payAmount" id="payAmount" value="100" placeholder="Enter Amount" required="" autofocus="">
                        </div>

                        <tbody class="text-center">

                            <?php
                            if (isset($_SESSION['cart'])) {
                                foreach ($_SESSION['cart'] as $key => $value) {
                                    $sr = $key + 1;
                                    echo "
        <tr>
            <td>$sr</td>
            <td> $value[Item_Name]</td>
            <td>$value[Price]</td>
            <td>
                <input class='text-center' type='number' value='$value[Quantity]' min='1' max='10'>
            </td>
            <td>
                <form action='' method='POST'> <!-- Use the same page for form submission -->
                    <button name='Remove_Item' class='btn btn-sm btn-outline-danger'>REMOVE</button>
                    <input type='hidden' name='Item_Name' value='$value[Item_Name]'>
                </form>
            </td>
        </tr>
        ";
                                }
                            }
                            ?>

                        </tbody>
                        <div class="col-lg-3">
                            <div class="border bg-light rounded p-4">
                                <h4>Total:</h4>
                                <h5 style="text-align: right;"><?php echo $total; ?></h5>
                                <br>
                                <form action="payment.php" method="POST">
                                    <input type="hidden" name="total_amount" value="<?php echo $total; ?>">
                                    <!-- <button class="btn btn-primary btn-block">Make Purchase</button> -->
                                </form>
                            </div>
                        </div>

                        <!-- submit button -->
                        <button id="PayNow" class="btn btn-primary btn-block">Make Purchase</button>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        //Pay Amount
        jQuery(document).ready(function($) {

            jQuery('#PayNow').click(function(e) {

                var paymentOption = '';
                let billing_serial = $('#billing_serial').val();
                let billing_name = $('#billing_name').val();
                let billing_price = $('#billing_price').val();
                let billing_quantity = $('#billing_quantity ').val();
                var shipping_serial = $('#billing_serial').val();
                var shipping_name = $('#billing_name').val();
                var shipping_price = $('#billing_price').val();
                var shipping_quantity= $('#billing_quantity').val();
                var paymentOption = "netbanking";
                var payAmount = $('#payAmount').val();

                var request_url = "payment.php";
                var formData = {
                    billing_serial: billing_serial,
                    billing_name: billing_name,
                    billing_price: billing_price,
                    billing_quantity:billing_quantity,
                    shipping_serial: shipping_serial,
                    shipping_name: shipping_name,
                    shipping_price: shipping_price,
                    shipping_quantity: shipping_quantity,
                    paymentOption: paymentOption,
                    payAmount: payAmount,
                    action: 'payOrder'
                }

                $.ajax({
                    type: 'POST',
                    url: request_url,
                    data: formData,
                    dataType: 'json',
                    encode: true,
                }).done(function(data) {

                    if (data.res == 'success') {
                        var orderID = data.order_number;
                        var orderNumber = data.order_number;
                        var options = {
                            "key": data.razorpay_key, // Enter the Key ID generated from the Dashboard
                            "amount": data.userData.amount, // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
                            "currency": "INR",
                            "name": "VYZA SOLUTIONS PVT LTD", //your business name
                            "description": data.userData.description,
                            "image": "VYZA.jpg",
                            "order_id": data.userData.rpay_order_id, //This is a sample Order ID. Pass 
                            "handler": function(response) {

                                window.location.replace("http://localhost/Vyza/Razorpay-payment-gateway/payment-success.php?oid=" + orderID + "&rp_payment_id=" + response.razorpay_payment_id + "&rp_signature=" + response.razorpay_signature);

                            },
                            "modal": {
                                "ondismiss": function() {
                                    window.location.replace("payment-success.php?oid=" + orderID);
                                }
                            },
                            "prefill": { //We recommend using the prefill parameter to auto-fill customer's contact information especially their phone number
                                "serial": data.userData.serial, //your customer's name
                                "name": data.userData.name,
                                "price": data.userData.price //Provide the customer's phone number for better conversion rates 
                            },
                            "notes": {
                                "address": "VYZA SOLUTIONS PVT LTD"
                            },
                            "config": {
                                "display": {
                                    "blocks": {
                                        "banks": {
                                            "name": 'Pay using ' + paymentOption,
                                            "instruments": [

                                                {
                                                    "method": paymentOption
                                                },
                                            ],
                                        },
                                    },
                                    "sequence": ['block.banks'],
                                    "preferences": {
                                        "show_default_blocks": true,
                                    },
                                },
                            },
                            "theme": {
                                "color": "#3399cc"
                            }
                        };
                        var rzp1 = new Razorpay(options);
                        rzp1.on('payment.failed', function(response) {

                            window.location.replace("http://localhost/Vyza/Razorpay-payment-gateway/payment-failed.php?oid=" + orderID + "&reason=" + response.error.description + "&paymentid=" + response.error.metadata.payment_id);

                        });
                        rzp1.open();
                        e.preventDefault();
                    }

                });
            });
        });
    </script>

</body>

</html>