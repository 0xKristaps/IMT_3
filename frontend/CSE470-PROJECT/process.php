<?php
session_start();

require_once "./functions/database_functions.php";

$title = "Purchase Process";
require "./template/header.php";


$conn = db_connect();


$customer = getCustomerIdbyEmail($_SESSION['email']);
$id = $customer['id'];


$firstname = mysqli_real_escape_string($conn, trim($_POST['firstname']));
$lastname = mysqli_real_escape_string($conn, trim($_POST['lastname']));
$address = mysqli_real_escape_string($conn, trim($_POST['address']));
$city = mysqli_real_escape_string($conn, trim($_POST['city']));
$zipcode = mysqli_real_escape_string($conn, trim($_POST['zipcode']));

$query = "UPDATE customers SET 
    firstname='$firstname', 
    lastname='$lastname', 
    address='$address', 
    city='$city', 
    zipcode='$zipcode'  
    WHERE id='$id'";
mysqli_query($conn, $query);


if (mysqli_error($conn)) {
    echo "Error updating customer: " . mysqli_error($conn);
    exit;
}


unset($_SESSION['cart']);
unset($_SESSION['total_price']);
unset($_SESSION['total_items']);


echo '<p class="lead text-success" id="p">Your order has been processed successfully.</p>';
?>


<script>
    window.setTimeout(function(){
        window.location.href = "index.php";
    }, 3000);
</script>

<?php
if (isset($conn)) {
    mysqli_close($conn);
}
require_once "./template/footer.php";
?>