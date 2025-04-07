<?php

require_once "templates/header.php";
?>

<?php


session_start();
if (!(isset($_SESSION["username"]))) {
    header("Location:login.php");
    exit;
}




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/dashboard.css">
    <title>Document</title>
</head>



<body>

    <a href="logout.php" class="logout-button">Logout</a>

    <div class="container">

        <?php
        $username = $_SESSION["username"];
        $role = $_SESSION["role"];

        echo "<h2>WELCOME {$username} ({$role})</h2>";
        if ($role == "customer") {
            echo '<a href ="borrow_book.php">Borrow Book</a><br>';
            echo '<a href ="return_book.php">Return Book</a><br>';
            echo '<a href ="view_books.php">View All Books</a><br>';
            echo '<a href ="store_borrowed_books.php">View All Borrowed Books</a><br>';
        } else {
            echo '<a href ="add_book.php">Add a Book</a><br>';
            echo '<a href ="remove_book.php">Remove a  Book</a><br>';
            echo '<a href ="view_books.php">View All Books</a><br>';
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            header("Location:logout.php");
        }
        ?>



    </div>
</body>


</html>