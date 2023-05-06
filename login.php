<?php

require_once('connection.php');
$valid_login = true;
session_start();

if(isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $db = getDatabaseConnection();
    $stmt = $db->prepare('SELECT * FROM user WHERE email = ?');
    $stmt->execute(array($_POST["email"]));
    $user = $stmt->fetch();

    if ($user){
        if(password_verify($_POST["password"], $user["password"])){
            session_start();
            $_SESSION["user_id"] = $user["id"];
            header("Location: index.php");
            exit();
        }
    }
    $valid_login = false;
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Login Page</title>
        <link href="css/login.css" rel="stylesheet">
    </head>
    <body>
        <h1>Log In</h1>

        <?php
           if(!$valid_login):
        ?>
        <p><em>Invalid login</em></p>
        <?php endif; ?>

        <form method="post">
            <div>
                <label for="email">Email</label>
                <input type="email" id= "email" name = "email">
            </div>

            <div>
                <label for="password">Password</label>
                <input type="password" id= "password" name = "password">
            </div>

            <button>Log in</button>
        </form>
        <div class ="ref">
           <a href="signup.php">SignUp</a>
        </div>     
    </body>
</html>
        