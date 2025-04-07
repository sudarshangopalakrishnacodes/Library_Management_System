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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $book_id = filter_input(INPUT_POST, "bookid", FILTER_SANITIZE_NUMBER_INT);
        $user_id = $_SESSION['id'];
        if (empty($book_id)) {
            $errors[] = "Book ID field cannot be empty.";
        }
        $con = connect();
        $stmnt = $con->prepare("SELECT * FROM books where book_id = :book_id");
        $stmnt->bindValue('book_id', $book_id);
        $stmnt->execute();

        $book = $stmnt->fetch(PDO::FETCH_ASSOC);

        if (!($book)) {
            $errors[] =  "No such book exists in the Database";
        } else {

            $stmt = $con->prepare("SELECT book_id, user_id FROM borrowed_books where book_id = :book_id AND user_id = :user_id AND is_returned =FALSE");
            $stmt->bindValue('book_id', $book_id);
            $stmt->bindValue('user_id', $user_id);
            $stmt->execute();
            $borrow_check = $stmt->fetch();

            if (!($borrow_check)) {
                $errors[] = "You cannot return a book that you have not borrowed!!!!";
            } else {

                $updatestmnt = $con->prepare("SELECT * FROM borrowed_books wHERE book_id = ? AND  user_id =?   ORDER BY id ASC LIMIT 1");
                $updatestmnt->bindValue(1, $book_id);
                $updatestmnt->bindValue(2, $user_id);
                $updatestmnt->execute();

                $borrowed_book = $updatestmnt->fetch();
                $borrowed_book_id = $borrowed_book["id"];
                $updatestmnt = $con->prepare("DELETE FROM borrowed_books where id = ?");
                $updatestmnt->bindValue(1, $borrowed_book_id);
                $updatestmnt->execute();



                if ($book["available_copies"] < $book["total_copies"]) {
                    $stmnt = $con->prepare("UPDATE books SET available_copies= available_copies+1 WHERE book_id =:book_id");
                    $stmnt->bindValue('book_id', $book_id);
                    $stmnt->execute();
                    $success = true;
                } else {
                    $errors[] =  "All copies already returned";
                }
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
            Successfully Returned the Book
        </div>
    <?php endif; ?>


    <div class="page_wrapper">

        <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
            <label>Enter the book ID</label><br>
            <input type="number" name="bookid" required><br>
            <input type="submit" name="submit" value="Search Book"><br>

        </form>
        <a class="backToHome" href="dashboard.php">Take Me Home 📚 </a>
    </div>
</body>

</html>