<?php
session_start();
require_once "./functions/database_functions.php";
$conn = db_connect();

$name = trim($_POST['username']);
$pass = trim($_POST['password']);


if (empty($name) || empty($pass)) {
    header("Location: signin.php?signin=empty");
    exit();
}


$query = "SELECT name, pass FROM manager WHERE name = '$name' AND pass = '$pass'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $_SESSION['manager'] = true;
    unset($_SESSION['expert'], $_SESSION['user'], $_SESSION['email']);
    header("Location: admin_book.php");
    exit();
}

$query = "SELECT name, pass FROM expert WHERE name = '$name' AND pass = '$pass'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $_SESSION['expert'] = true;
    unset($_SESSION['manager'], $_SESSION['user'], $_SESSION['email']);
    header("Location: admin_book.php");
    exit();
}

$name = mysqli_real_escape_string($conn, $name);
$pass = mysqli_real_escape_string($conn, $pass);

$query = "SELECT email, password FROM customers WHERE email = '$name' AND password = '$pass'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $_SESSION['user'] = true;
    $_SESSION['email'] = $name;
    unset($_SESSION['manager'], $_SESSION['expert']);
    header("Location: index.php");
    exit();
}

header("Location: signin.php?signin=invalid");
exit();

if (isset($conn)) {
    mysqli_close($conn);
}
?>
