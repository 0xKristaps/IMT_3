<?php
session_start();


require "./functions/database_functions.php";
$conn = db_connect();


$errors = []; 
$success = false; 


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $firstname = trim($_POST['firstname']);
    $firstname = mysqli_real_escape_string($conn, $firstname);

    $lastname = trim($_POST['lastname']);
    $lastname = mysqli_real_escape_string($conn, $lastname);

    $email = trim($_POST['email']);
    $email = mysqli_real_escape_string($conn, $email);

    $password = trim($_POST['password']);
    $password = mysqli_real_escape_string($conn, $password);

    $address = trim($_POST['address']);
    $address = mysqli_real_escape_string($conn, $address);

    $city = trim($_POST['city']);
    $city = mysqli_real_escape_string($conn, $city);

    $zipcode = trim($_POST['zipcode']);
    $zipcode = mysqli_real_escape_string($conn, $zipcode);


    if (empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($address) || empty($city) || empty($zipcode)) {
        $errors[] = "All fields are required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }


    if (empty($errors)) {

        $findUser = "SELECT * FROM customers WHERE email = '$email'";
        $findResult = mysqli_query($conn, $findUser);

        if (mysqli_num_rows($findResult) == 0) {
            $insertUser = "INSERT INTO customers(firstname,lastname,email,address,password,city,zipcode) VALUES 
            ('$firstname','$lastname','$email','$address','$password','$city','$zipcode')";
            $insertResult = mysqli_query($conn, $insertUser);

            if (!$insertResult) {
                $errors[] = "Can't add new user: " . mysqli_error($conn);
            } else {
                $success = true;
                $userid = mysqli_insert_id($conn);
            }
        } else {
            $errors[] = "An account with this email already exists.";
        }
    }
}


$title = "User Signup";
require "./template/header.php";
?>

<div class="container">
    <h2>User Signup</h2>
    <?php

    if ($success) {
        echo "<p class='text-success'>Signup successful! Redirecting to login...</p>";
        echo "<script>setTimeout(() => { window.location.href = 'signin.php'; }, 3000);</script>";
    }


    if (!empty($errors)) {
        echo "<div class='alert alert-danger'>";
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
        echo "</div>";
    }
    ?>


    <form method="POST" action="user_signup.php">
        <div class="form-group">
            <label for="firstname">First Name:</label>
            <input type="text" class="form-control" name="firstname" id="firstname" required>
        </div>
        <div class="form-group">
            <label for="lastname">Last Name:</label>
            <input type="text" class="form-control" name="lastname" id="lastname" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" name="password" id="password" required>
        </div>
        <div class="form-group">
            <label for="address">Address:</label>
            <input type="text" class="form-control" name="address" id="address" required>
        </div>
        <div class="form-group">
            <label for="city">City:</label>
            <input type="text" class="form-control" name="city" id="city" required>
        </div>
        <div class="form-group">
            <label for="zipcode">Zip Code:</label>
            <input type="text" class="form-control" name="zipcode" id="zipcode" required>
        </div>
        <button type="submit" class="btn btn-primary">Sign Up</button>
    </form>
</div>

<?php

if (isset($conn)) {
    mysqli_close($conn);
}
require_once "./template/footer.php";
?>
