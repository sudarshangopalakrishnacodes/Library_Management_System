<?php

require_once "templates/header.php";

?>

<?php

session_start();

if (isset($_SESSION["username"])) {
    header("Location:dashboard.php");
    exit;
}
require_once "database/db_config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, "password");

    $login_errors = [];

    if (empty($username)) {
        $login_errors[] = "Username or email field cannot be empty";
    }
    if (empty($password)) {
        $login_errors[] = "Please enter a password to proceed";
    }


    if (count($login_errors) == 0) {

        $con = connect();
        $stmnt = $con->prepare("SELECT * FROM users WHERE username = :username or email =:email");
        $stmnt->bindValue('username', $username);
        $stmnt->bindValue('email', $username);
        $stmnt->execute();
        $user = $stmnt->fetch(PDO::FETCH_ASSOC);


        if ($user) {
            if (password_verify($password, $user["password"])) {
                $_SESSION["username"] = $user["username"];
                $_SESSION["id"] = $user["id"];
                $_SESSION["role"] = $user["role"];
                if ($_SESSION["role"] == "customer") {
                    header("Location:dashboard.php");
                    exit;
                } else {
                    header("Location:dashboard.php");
                    exit;
                }
            } else {
                $login_errors[] = "Incorrect username or password";
            }
        } else {
            $login_errors[] = "No user found";
        }
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/registerLogin.css">


    <title>Document</title>
</head>

<body>

    <?php if (!empty($login_errors)): ?>
        <div class="error-container">
            <?php foreach ($login_errors as $error): ?>
                <p class="error-message"><?= $error ?></p>
            <?php endforeach ?>
        </div>
    <?php endif ?>

    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
        <h4 class="form-heading">
            <center>Your Books Await- Login!!</center>
        </h4>
        <label>Username or Email</label><br>
        <input type="text" name="username"><br>
        </br>
        <label>Password</label><br>
        <input type="password" name="password"><br>
        <input type="submit" name="login" value="Login">
        <h4>Not a member yet? Please register here <a href="register.php">Register</a></h4>
    </form>

</body>

</html>