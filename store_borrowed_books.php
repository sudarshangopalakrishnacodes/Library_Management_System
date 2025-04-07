<?php

require_once "templates/header.php";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/view_books.css">
    <title>Document</title>
</head>

<body>
    <div class="page_wrapper">

        <?php
        session_start();
        $errors = [];
        if (!($_SESSION["username"])) {

            header("Location:login.php");
            exit;
        }

        if ($_SESSION['role'] != 'customer') {
            $errors[] =  "You do not have access to this page!";
            exit;
        }
        require_once "database/db_config.php";

        $user_id = $_SESSION["id"];
        $con = connect();
        $stmnt = $con->prepare("SELECT bb.id as borrowed_id,  b.book_id, b.title, b.author, bb.borrow_date 
                            FROM borrowed_books bb JOIN books b on bb.book_id = b.book_id 
                            where bb.user_id = :user_id AND is_returned = FALSE");

        $stmnt->bindValue('user_id', $user_id);
        $stmnt->execute();

        $books = $stmnt->fetchAll();

        if (empty($books)) {
            $errors[] = "You have not borrowed any books";
        }

        ?>
        <?php if (!empty($errors)): ?>
            <div class="error-container">
                <?php foreach ($errors as $error): ?>
                    <p class="error-message"><?= $error ?></p>
                <?php endforeach ?>
            </div>
        <?php endif ?>

        <table class="books-table">
            <thead>
                <th>Book ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Borrow Date</th>
            </thead>
            <tbody>
                <?php foreach ($books as $book) : ?>
                    <tr>
                        <td><?= $book["book_id"]; ?></td>
                        <td><?= $book["title"]; ?></td>
                        <td><?= $book["author"]; ?></td>
                        <td><?= $book["borrow_date"]; ?></td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="dashboard.php">Take Me Home 📚 </a>
    </div>

</body>

</html>