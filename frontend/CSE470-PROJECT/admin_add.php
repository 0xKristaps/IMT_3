<?php
session_start();


if ((!isset($_SESSION['manager']) && !isset($_SESSION['expert']))) {
    header("Location: index.php");
    exit();
}


require "./functions/database_functions.php";
$conn = db_connect();

ob_start(); 

$title = "Add New Book";
require "./template/header.php"; 

if (isset($_POST['add'])) {

    $isbn = mysqli_real_escape_string($conn, trim($_POST['isbn']));
    $title = mysqli_real_escape_string($conn, trim($_POST['title']));
    $author = mysqli_real_escape_string($conn, trim($_POST['author']));
    $descr = mysqli_real_escape_string($conn, trim($_POST['descr']));
    $price = mysqli_real_escape_string($conn, floatval(trim($_POST['price'])));
    $publisher = mysqli_real_escape_string($conn, trim($_POST['publisher']));
    $category = mysqli_real_escape_string($conn, trim($_POST['category']));


    $image = "";
    if (isset($_FILES['image']) && $_FILES['image']['name'] != "") {
        $image = $_FILES['image']['name'];
        $directory_self = dirname($_SERVER['PHP_SELF']) . "/bootstrap/img/";
        $uploadDirectory = $_SERVER['DOCUMENT_ROOT'] . $directory_self . $image;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadDirectory)) {
            echo "Error uploading the file.";
            exit();
        }
    }


    $findPub = "SELECT publisherid FROM publisher WHERE publisher_name = '$publisher'";
    $findResult = mysqli_query($conn, $findPub);
    if (mysqli_num_rows($findResult) == 0) {
        $insertPub = "INSERT INTO publisher(publisher_name) VALUES ('$publisher')";
        if (!mysqli_query($conn, $insertPub)) {
            echo "Can't add new publisher: " . mysqli_error($conn);
            exit();
        }
        $publisherid = mysqli_insert_id($conn);
    } else {
        $row = mysqli_fetch_assoc($findResult);
        $publisherid = $row['publisherid'];
    }


    $findCat = "SELECT categoryid FROM category WHERE category_name = '$category'";
    $findResult = mysqli_query($conn, $findCat);
    if (mysqli_num_rows($findResult) == 0) {
        $insertCat = "INSERT INTO category(category_name) VALUES ('$category')";
        if (!mysqli_query($conn, $insertCat)) {
            echo "Can't add new category: " . mysqli_error($conn);
            exit();
        }
        $categoryid = mysqli_insert_id($conn);
    } else {
        $row = mysqli_fetch_assoc($findResult);
        $categoryid = $row['categoryid'];
    }


    $query = "INSERT INTO books (book_isbn, book_title, book_author, book_image, book_descr, book_price, publisherid, categoryid) 
              VALUES ('$isbn', '$title', '$author', '$image', '$descr', '$price', '$publisherid', '$categoryid')";
    if (!mysqli_query($conn, $query)) {
        echo "Can't add new data: " . mysqli_error($conn);
        exit();
    }


    header("Location: admin_book.php");
    exit();
}


ob_end_flush();
?>


<form method="post" action="admin_add.php" enctype="multipart/form-data">
    <table class="table">
        <tr>
            <th>ISBN</th>
            <td><input type="text" name="isbn" required></td>
        </tr>
        <tr>
            <th>Title</th>
            <td><input type="text" name="title" required></td>
        </tr>
        <tr>
            <th>Author</th>
            <td><input type="text" name="author" required></td>
        </tr>
        <tr>
            <th>Image</th>
            <td><input type="file" name="image"></td>
        </tr>
        <tr>
            <th>Description</th>
            <td><textarea name="descr" cols="40" rows="5"></textarea></td>
        </tr>
        <tr>
            <th>Price</th>
            <td><input type="number" step="0.01" name="price" required></td>
        </tr>
        <tr>
            <th>Publisher</th>
            <td><input type="text" name="publisher" required></td>
        </tr>
        <tr>
            <th>Category</th>
            <td><input type="text" name="category" required></td>
        </tr>
    </table>
    <input type="submit" name="add" value="Add New Book" class="btn btn-primary">
    <input type="reset" value="Cancel" class="btn btn-default">
</form>

<?php
if (isset($conn)) {
    mysqli_close($conn);
}
require_once "./template/footer.php";
?>
