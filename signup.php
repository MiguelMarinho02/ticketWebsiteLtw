<?php

require_once('connection.php');

if ($_SERVER["REQUEST_METHOD"] === "POST"){
    if (empty($_POST["name"])){
        die("Name is required");
    }
    
    if (empty($_POST["username"])){
        die("UserName is required");
    }
    
    if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
        die("Valid email is required");
    }
    
    if( strlen($_POST["password"]) < 8) {
        die("Password must be at least 8 characters long");
    }
    
    if($_POST["password"] != $_POST["r_password"]){
        die("Passwords don't match");
    }
    
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
            echo "The value '$value' already exists in the database.";
        } else {
            // handle other errors
            echo "An error occurred: " . $e->getMessage();
        }
    }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>SignUp Page</title>
    </head>
    <body>
        <h1>SignUp</h1>

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
                <label for="r_password">Repeat Password</label>
                <input type="password" id= "r_password" name = "r_password">
            </div>

            <button>SignUp</button>
    </body>
</html>