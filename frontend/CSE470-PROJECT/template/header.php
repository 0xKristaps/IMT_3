<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once "./functions/database_functions.php";

if (isset($_SESSION['email'])) {
    $customer = getCustomerIdbyEmail($_SESSION['email']);
    $name = $customer['firstname'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($title) ? $title : 'Online Bookshop'; ?></title>
    <link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="./bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="./bootstrap/css/jumbotron.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="index.php">BookShop</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="publisher_list.php">Publishers</a></li>
                <li><a href="category_list.php">Categories</a></li>
                <li><a href="books.php">Books</a></li>
                <li><a href="cart.php">My Cart</a></li>
                <?php
                if (isset($_SESSION['user'])) {
                    echo '<li><a href="logout.php">Logout</a></li>';
                    echo '<li><a href="profile.php">' . htmlspecialchars($name) . '</a></li>';
                } else {
                    echo '<li><a href="signin.php">Signin</a></li>';
                    echo '<li><a href="signup.php">Signup</a></li>';
                }
                ?>
            </ul>
        </div>
    </div>
</nav>
<div class="container" id="main">
