<?php
require_once "templates/header.php";
?>

<?php

require_once "database/db_config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, "password");
    $phone = filter_input(INPUT_POST, "phone");
    $countrycode = filter_input(INPUT_POST, "countrycode");
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $role = filter_input(INPUT_POST, "role");

    $errors = [];

    if (empty($name)) {
        $errors[] = "Name field cannot be empty";
    }
    if (ctype_digit($username)) {
        $errors[] = "Name cannot be digits";
    }
    if (empty($username)) {
        $errors[] = "Username field cannot be empty";
    }
    if (ctype_digit($username)) {
        $errors[] = "Username cannot contain only digits";
    }

    if (empty($password)) {
        $errors[] = "Password field cannot be empty";
    }
    if (strlen($password) < 7) {
        $errors[] = "Password length should be at least 7 characters";
    }
    if (empty($phone)) {
        $errors[] = "Phone number cannot be empty";
    }
    if (!(ctype_digit($phone))) {
        $errors[] = "Phone number must contain only digits";
    }
    $length = strlen($phone);

    if (($countrycode == "+91" || $countrycode == "+1") && $length != 10) {
        $errors[] = "Phone number must be exactly 10 digits for country code (+1) and (+91)";
    }
    if ($countrycode == "+49" && ($length != 10 && $length != 11)) {
        $errors[] = "Phone number must be exactly 10 or 11 digits for country code (+49)";
    }

    if (empty($email)) {
        $errors[] = "Email cannot be empty";
    }
    if (!(filter_var($email, FILTER_VALIDATE_EMAIL))) {
        $errors[] = "Enter a correct email";
    }
    if (empty($role)) {
        $errors[] = "Role cannot be empty";
    }




    $fullphone = $countrycode . $phone;
    $hash = password_hash($password, PASSWORD_DEFAULT);
    if (count($errors) == 0) {
        $con = connect();
        $checkstmnt = $con->prepare("SELECT *FROM users WHERE username =:username OR email=:email");
        $checkstmnt->bindValue(':username', $username);
        $checkstmnt->bindValue(':email', $email);
        $checkstmnt->execute();

        if ($checkstmnt->fetch()) {
            $errors[] = "Username or email already exists";
        } else {
            $insertstmnt = $con->prepare("INSERT INTO users (name,username,password,phone,email,role) VALUES (?,?,?,?,?,?)");
            $insertstmnt->bindValue(1, $name);
            $insertstmnt->bindValue(2, $username);
            $insertstmnt->bindValue(3, $hash);
            $insertstmnt->bindValue(4, $fullphone);
            $insertstmnt->bindValue(5, $email);
            $insertstmnt->bindValue(6, $role);
            $insertstmnt->execute();

            $success = true;
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/registerLogin.css">


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
            Registration successful. You can now <a href="login.php">log in</a>.
        </div>
    <?php endif; ?>


    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
        <h4 class="form-heading">
            <center>Join the Library Universe</center>
        </h4>
        <label>Enter your Name</label>
        <input type="text" name="name" required><br>
        <label>Username</label>
        <input type="text" name="username" required><br>
        <label>Password</label>
        <input type="password" name="password" required><br>
        <label>Phone Number</label>
        <select name="countrycode" required>
            <option value="+49">+49</option>
            <option value="+91">+91</option>
            <option value="+1">+1</option>
        </select>
        <input type="text" name="phone" required><br>
        <label>Email</label>
        <input type="email" name="email" required><br>
        <label for="role">Choose a role</label>
        <select name="role" id="role" required>
            <option value="customer">Customer</option>
            <option value="librarian">Librarian</option>
        </select>
        <input type="submit" name="register" value="Register">
        <h4>Already a member? <a href="login.php">Login</a></h4>
    </form>

</body>

</html>