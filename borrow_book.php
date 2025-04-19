<?php

require_once "templates/header.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/borrowReturn.css">

</head>

<body>

    <?php

    $errors = [];
    session_start();
    if (!($_SESSION["username"])) {
        header("Location:login.php");
    }
    if ($_SESSION["role"] != 'customer') {
        $errors[] = "You cannot access this page!!";
        exit;
    }
    require_once "database/db_config.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $book_id = filter_input(INPUT_POST, "bookid", FILTER_SANITIZE_NUMBER_INT);
        $user_id = $_SESSION["id"];
        if (empty($book_id)) {
            $errors[] = "Book ID field cannot be empty.";
        }

        $con = connect();
        $stmnt = $con->prepare("SELECT book_id, title,author,total_copies,available_copies FROM books where book_id =:book_id");
        $stmnt->bindValue('book_id', $book_id);
        $stmnt->execute();
        $book = $stmnt->fetch(PDO::FETCH_ASSOC);

        if (!($book)) {
            $errors[] = "No book found with the entered ID. Please check for the book IDs from View All Books ";
        } else {
            if ($book["available_copies"] > 0) {
                $stmnt = $con->prepare("UPDATE books SET available_copies= available_copies-1 WHERE book_id =:book_id");
                $stmnt->bindValue('book_id', $book_id);
                $stmnt->execute();
                $success = true;
                $stmt = $con->prepare("INSERT INTO borrowed_books (user_id, book_id, borrow_date)
                                     VALUES (:user_id, :book_id, CURDATE())");

                $stmt->bindValue('user_id', $user_id);
                $stmt->bindValue('book_id', $book_id);
                $stmt->execute();
                $success = true;
            } else {
                $errors[] =  "No copies available to borrow";
            }
        }
    }



    ?>

    <?php if (!empty($errors)): ?>
        <div class="error-container">
            <?php foreach ($errors as $error): ?>
                <p class="error-message"><?= $error ?></p>
            <?php endforeach ?>
        </div>
    <?php endif ?>

    <?php if (isset($success) && $success): ?>
        <div class="success-message">
            Successfully Borrowed the Book
        </div>
    <?php endif; ?>

    <div class="page_wrapper">
        <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
            <label>Enter the book ID</label><br>
            <input type="number" name="bookid" required><br>
            <input type="submit" name="submit" value="Borrow Book"><br>


        </form>
        <a class="backToHome" href="dashboard.php">Take Me Home 📚 </a>

    </div>


</body>

</html>