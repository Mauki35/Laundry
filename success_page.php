<?php
include("connect.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style_success.css">
</head>

<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="alert alert-success">
                    <strong>Success!</strong> You're a step away from paying your order!
                    <p>Proceed to Checkout to place your order and place it!</p>
                </div>
                <?php
                    $mobileNumber = isset($_GET['mobileNumber']) ? htmlspecialchars($_GET['mobileNumber']) : 'N/A';
                    $totalAmount = isset($_GET['totalAmount']) ? htmlspecialchars($_GET['totalAmount']) : 'N/A';
                ?>
                <form action="Checkout.php" method="get">
                    <div class="form-group">
                        <label for="mobileNumber">Mobile Number:</label>
                        <p id="mobileNumber"><?php echo $mobileNumber; ?></p>
                        <input type="hidden" name="mobileNumber" value="<?php echo htmlspecialchars($mobileNumber); ?>">
                    </div>
                    <div class="form-group">
                        <label for="totalAmount">Total Amount:</label>
                        <p id="totalAmount">Ksh <?php echo $totalAmount; ?></p>
                        <input type="hidden" name="totalAmount" value="<?php echo htmlspecialchars($totalAmount); ?>">
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary">Checkout</button>
                        <a href="Order Page.php" class="btn btn-secondary">Return To Order</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
