<?php 
session_start(); // Start the session

// Include necessary files
// include("header.php");
// require_once("PaytmChecksum.php");

// Function to remove an item from the cart
if(isset($_POST['Remove_Item'])) {
    $item_name = $_POST['Item_Name'];
    foreach($_SESSION['cart'] as $key => $value) {
        if($value['Item_Name'] == $item_name) {
            unset($_SESSION['cart'][$key]); // Remove the item from the cart
        }
    }
}

// Calculate total amount
$total = 0;
if(isset($_SESSION['cart'])) {
    foreach($_SESSION['cart'] as $key => $value) {
        $total += $value['Price'];
    }
}

// Store total amount in session
$_SESSION['totalAmount'] = $total;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center border rounded bg-light my-5">
                <h1>My Cart</h1>
            </div>

            <div class="col-lg-9">
                <table class="table">
                    <thead class="text-center">
                        <tr>
                            <th scope="col">Serial No.</th>
                            <th scope="col">Item Name</th>
                            <th scope="col">Item Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>

                    <tbody class="text-center">

                        <?php 
                        if(isset($_SESSION['cart'])) {
                            foreach($_SESSION['cart'] as $key => $value) {
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
                </table>
            </div>

            <div class="col-lg-3">
                <div class="border bg-light rounded p-4">
                    <h4>Total:</h4>
                    <h5 style="text-align: right;"><?php echo $total; ?></h5>
                    <br>
                    <form action="payment.php" method="POST">
                        <input type="hidden" name="total_amount" value="<?php echo $total; ?>">
                        <button class="btn btn-primary btn-block">Make Purchase</button>
                    </form>
                </div>
            </div>

        </div>

    </div>
</body>
</html>