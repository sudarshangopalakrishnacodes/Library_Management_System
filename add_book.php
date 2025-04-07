<?php

require_once "templates/header.php";

?>

<?php
session_start();

if (!(isset($_SESSION["username"]))) {
    header("Location:login.php");
    exit;
}

if ($_SESSION["role"] != "librarian") {
    echo "You do not have access to this page!";
    exit;
}

require_once "database/db_config.php";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $book_title = filter_input(INPUT_POST, "title", FILTER_SANITIZE_SPECIAL_CHARS);
    $author = filter_input(INPUT_POST, "author", FILTER_SANITIZE_SPECIAL_CHARS);
    $subject = filter_input(INPUT_POST, "subject", FILTER_SANITIZE_SPECIAL_CHARS);
    $total_copies = filter_input(INPUT_POST, "total_copies", FILTER_SANITIZE_NUMBER_INT);


    if (empty($book_title)) {
        $errors[] = "Book title cannot be empty";
    }
    if (empty($author)) {
        $errors[] = "Author cannot be empty";
    }
    if (ctype_digit($author)) {
        $errors[] = "Author name cannot be digits";
    }
    if (empty($subject)) {
        $errors[] = "Subject cannot be empty";
    }
    if (ctype_digit($subject)) {
        $errors[] = "Subject cannot be digits";
    }
    if (empty($total_copies)) {
        $errors[] = "Total copies cannot be empty";
    }
    if ($total_copies <= 0) {
        $errors[] = "Number of copies must be greater than zero.";
    }



    if (count($errors) == 0) {
        $con = connect();
        $stmnt = $con->prepare("INSERT INTO books (title,subject,author,total_copies,available_copies) 
        VALUES (?,?,?,?,?)");
        $stmnt->bindValue(1, $book_title);
        $stmnt->bindValue(2, $subject);
        $stmnt->bindValue(3, $author);
        $stmnt->bindValue(4, $total_copies);
        $stmnt->bindValue(5, $total_copies);
        $stmnt->execute();
        $success = true;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/addbook.css">
    >
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
            Successfully Added the Book
        </div>
    <?php endif; ?>
    <div class="page_wrapper">
        <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
            <label>Enter Book Title</label><br>
            <input type="text" name="title" required><br>
            <label>Enter Author name</label><br>
            <input type="text" name="author" required><br>
            <label>Enter Genre</label><br>
            <input type="text" name="subject" required><br>
            <label>Enter number of copies</label><br>
            <input type="number" name="total_copies" required><br>
            <input type="submit" name="submit" value="Add Book">
        </form>
        <a class="backToHome" href="dashboard.php">Take Me Home 📚 </a>
    </div>
</body>

</html>