<?php

require_once('connection.php');

$error_msg;
$valid_login = true;
if ($_SERVER["REQUEST_METHOD"] === "POST"){
    if (empty($_POST["name"])){
        $error_msg = "Name is required";
    }
    
    else if (empty($_POST["username"])){
        $error_msg = "UserName is required";
    }
    
    else if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
        $error_msg ="Valid email is required";
    }
    
    else if( strlen($_POST["password"]) < 8) {
        $error_msg ="Password must be at least 8 characters long";
    }
    
    else if($_POST["password"] != $_POST["r_password"]){
        $error_msg ="Passwords don't match";
    }
    
    else{
    $password_hashed = password_hash($_POST["password"],PASSWORD_DEFAULT);
    
    $db = getDatabaseConnection();
    try{
        $stmt = $db->prepare('INSERT INTO user (name, username, email, password, role) VALUES (?,?,?,?,"client")');
        $stmt->execute(array($_POST["name"],$_POST["username"],$_POST["email"],$password_hashed));
        header("Location: login.php");
        exit();
    }
    catch(PDOException $e){
        if ($e->getCode() == 23000) {
            // extract the duplicate key value from the error message
            $message = $e->getMessage();
            $errorParts = explode(':', $message);
            $duplicateKey = trim($errorParts[count($errorParts) - 1]);
            //this only works if error message is consistent
            $value = explode('.',$duplicateKey)[1];
            // provide a specific error message to the user
            $error_msg = "The value '$value' already exists in the database.";
        } else {
            // handle other errors
            $error_msg = "An error occurred: " . $e->getMessage();
        }
    }
    }

    $valid_login = false;
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>SignUp Page</title>
        <link href="css/login.css" rel="stylesheet">
    </head>
    <body>
        <h1>SignUp</h1>

        <?php
        if(!$valid_login):
        ?>
        <p><em><?php echo $error_msg ?></em></p>
        <?php endif; ?>

        <form method="post">
            <div>
                <label for="name">Name</label>
                <input type="text" id= "name" name = "name">
            </div>

            <div>
                <label for="username">UserName</label>
                <input type="text" id= "username" name = "username">
            </div>

            <div>
                <label for="email">Email</label>
                <input type="email" id= "email" name = "email">
            </div>

            <div>
                <label for="password">Password</label>
                <input type="password" id= "password" name = "password">
            </div>

            <div>
                <label for="r_password">Repeat<br>Password</label>
                <input type="password" id= "r_password" name = "r_password">
            </div>

            <button>SignUp</button>´
        </form>    
    </body>
</html>