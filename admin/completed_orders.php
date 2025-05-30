<?php
include '../components/connect.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Add your head content here -->
    <title>Total Orders of Customers</title>
</head>
<body>

   <?php include '../components/admin_header.php' ?>

   <section class="completed-orders">
      <h1 class="heading">Total Orders</h1>

      <style>
    /* Your existing styles above this comment */

    .box-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
    }

    .box {
        background-color: green;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease-in-out;
        display: grid;
        align-items: flex-start;
        margin: 15px;
        color: white;
    }

    .box:hover {
            transform: scale(1.02);
        }

        .box p {
        margin-bottom: 5px;
        font-size: 18px;
    }

    .Empty {
        padding: 1.5rem;
        text-align: center;
        width: 100%;
        font-size: 2rem;
        text-transform: capitalize;
        color: red;
    }
     /* Responsive styles */
     @media screen and (max-width: 768px) {
            .box {
                width: calc(50% - 20px);
            }
        }

        @media screen and (max-width: 480px) {
            .box {
                width: calc(100% - 20px);
            }
        }

        /* Style for Completed status */
        .box p span[status="Completed"] {
            color: white;            
        }

        .box label {
    text-transform: uppercase;
    font-weight: bold; /* Optional: You can also make it bold */
    font-size: 16px; /* Adjust the font size as needed */
}


        .box-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space; /* Adjust the justification as needed */
        }

        .box {
            flex-basis: calc(33.33% - 20px); /* Adjust the width and margin as needed */
            box-sizing: border-box;
        }
</style>


<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<!-- jQuery (required for Bootstrap JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>

<!-- Popper.js (required for Bootstrap JavaScript plugins) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-rs0p4MlJS9VvlCk70R1T0tkw6CsS87+a9/NYrOLv/GsPF0MVUeKYYaCk78QaQ1M6" crossorigin="anonymous"></script>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<!-- Font Awesome CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">


<!-- custom css file link  -->
<link rel="stylesheet" href="../css/admin_style.css">


<div class="box-container">

    <?php
    $select_completed_orders = $conn->prepare("SELECT * FROM `completed_orders` ORDER BY placed_on DESC");
    $select_completed_orders->execute();

    if ($select_completed_orders->rowCount() > 0) {
        while ($fetch_completed_orders = $select_completed_orders->fetch(PDO::FETCH_ASSOC)) {
            // Calculate estimated delivery time (assuming 5 minutes)
            $placedTime = strtotime($fetch_completed_orders['placed_on']);
            $estimatedDeliveryTime = date('Y-m-d H:i:s', $placedTime + (15 * 60)); // Adding 5 minutes
            ?>

<div class="box">
    <p>Date/Time Placed On: <span><?= $fetch_completed_orders['placed_on']; ?></span></p>
    <p>Customer Name: <span><?= $fetch_completed_orders['fname'] . ' ' . $fetch_completed_orders['mname'] . ' ' . $fetch_completed_orders['lname']; ?></span></p>
    <p>Total Menu: <span><?= $fetch_completed_orders['total_products']; ?></span></p>
    <p>Total Due: <span>₱<?= $fetch_completed_orders['total_price']; ?></span></p>
    <p>Payment Method: <span><?= $fetch_completed_orders['method']; ?></span></p>
    <p>Delivered Date/Time: <span><?= $estimatedDeliveryTime; ?></span></p>
    <p>Order Status:
    <label for="order_id" style="font-size: 18px; font-weight: bold; color: blue;">Completed
        <input type="checkbox" id="order_id" name="total_products" value="" <?php echo $fetch_completed_orders['order_status'] === 'Completed' ? 'checked disabled' : ''; ?>>
    </label>
</p>
</div>


            <?php
        }
    } else {
        echo '<p class="Empty">No completed orders yet.</p>';
    }
    ?>

</div>

   </section>

   <script src="../js/admin_script.js"></script>

</body>
</html>
