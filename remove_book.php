<?php
require_once "templates/header.php";
?>


<?php

session_start();
$errors = [];
if (!(isset($_SESSION["username"]))) {
    header("Location:login.php");
    exit;
}

if (($_SESSION["role"] != "librarian")) {

    echo "You do not have access to this page!!";
    exit;
}
require_once "database/db_config.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book_id = filter_input(INPUT_POST, "bookid", FILTER_SANITIZE_NUMBER_INT);

    if (empty($book_id)) {
        $errors[] =  "Book id cannot be empty/characters";
    } else {
        $con = connect();
        $stmnt = $con->prepare("SELECT * FROM books where book_id =:bookid");
        $stmnt->bindValue(':bookid', $book_id);
        $stmnt->execute();
        $book = $stmnt->fetch(PDO::FETCH_ASSOC);

        if (!($book)) {
            $errors[] = "Book not found";
        } else {
            $stmnt = $con->prepare("DELETE FROM books where book_id =:bookid");
            $stmnt->bindValue(':bookid', $book_id);
            $stmnt->execute();
            if ($stmnt->rowCount() > 0) {
                $success = true;
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/removebook.css">
    <title>Document</title>
</head>

<body>


    <?php if (!empty($errors)): ?>
        <div class="error-container">
            <?php foreach ($errors as $error): ?>
                <p class="error-message"><?= $error ?></p>
            <?php endforeach ?>
        </div>
    <?php endif ?>

    <?php if (isset($success) && $success): ?>
        <div class="success-message">
            Successfully Removed the Book
        </div>
    <?php endif; ?>
    <div class="page_wrapper">
        <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">

            <label>Enter the Book id of the book you want to remove</label>
            <input type="text" name="bookid" required>
            <input type="submit" name="submit" value="Remove Book">

        </form>
        <a class="backToHome" href="dashboard.php">Take Me Home 📚 </a>
    </div>
</body>

</html>