<?php
require_once "templates/header.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/view_books.css">


</head>



<body>
    <div class="page_wrapper">



        <?php

        session_start();

        if (!($_SESSION["username"])) {
            header("Location:login.php");
        }

        require_once "database/db_config.php";


        $con = connect();
        $stmnt = $con->prepare("SELECT book_id,title,author,total_copies,available_copies FROM books");
        $stmnt->execute();

        $books = $stmnt->fetchAll();



        ?>
        <table class="books-table">
            <thead>
                <th>Book ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Number of Copies</th>
                <th>available copies</th>
            </thead>
            <tbody>
                <?php foreach ($books as $book) : ?>
                    <tr>
                        <td><?= $book["book_id"]; ?></td>
                        <td><?= $book["title"]; ?></td>
                        <td><?= $book["author"]; ?></td>
                        <td><?= $book["total_copies"]; ?></td>
                        <td><?= $book["available_copies"] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="dashboard.php">Take Me Home 📚 </a>
    </div>
</body>


</html>