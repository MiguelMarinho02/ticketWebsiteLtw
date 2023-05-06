<?php

declare(strict_types = 1);
require_once('connection.php');
require_once('functions.php');
session_start();
if (!isset($_SESSION["user_id"])){
    header("Location: login.php");
}
$db = getDatabaseConnection();

$user = searchUser($_SESSION["user_id"]);

$success = true;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try{
        if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
            die("Valid email is required");
        }

        if($_POST["new_password"] == "" and $_POST["old_password"] == ""){
            $stmt = $db->prepare('UPDATE user SET name = ?,username = ?,email = ? WHERE id = ?');
            $stmt->execute(array($_POST["name"],$_POST["username"],$_POST["email"],$user["id"]));
            $url = "user_profile.php?username=".$_POST["username"];
            header("Location: " . $url);
            exit();
        }
        
        if(password_verify($_POST["old_password"],$user["password"])){
            if( strlen($_POST["new_password"]) < 8) {
                die("Password must be at least 8 characters long");
            }
            $new_password = password_hash($_POST["new_password"],PASSWORD_DEFAULT);
            $stmt = $db->prepare('UPDATE user SET name = ?,username = ?,email = ?, password = ? WHERE id = ?');
            $stmt->execute(array($_POST["name"],$_POST["username"],$_POST["email"],$new_password,$user["id"]));
            header("Location:logout.php");
            exit();
        }
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
    $success = false;
}

?>

<!DOCTYPE html>
<html lang="en-US">
<link rel="stylesheet" href="css/form.css">
   <head>
      <title>Edit Profile</title>
      <script src="script/script.js"></script>
   </head>

   <body>
      <div class="buttons">
        <div class="profile">
        <button onclick="sendDataUser('<?php echo $user['username'] ?>')">Back</button>
        </div>
        <br>

      </div>
      <br>

      <?php if(!$success):?>
      <em>Invalid credentials</em>
      <br>
      <?php endif; ?>

      <div class="content">
        <form method="post">
            <div>
                <label for="name">Name</label>
                <input type="name" id= "name" name = "name" value=<?php echo $user["name"]?>>
            </div>

            <div>
                <label for="username">Username</label>
                <input type="username" id= "username" name = "username" value=<?php echo $user["username"]?>>
            </div>

            <div>
                <label for="email">Email</label>
                <input type="email" id= "email" name = "email" value=<?php echo $user["email"]?>>
            </div>

            <p><em>Fields below are not required to change the above ones</em></p>

            <div>
                <label for="old_password">Old Password</label>
                <input type="password" id= "old_password" name = "old_password">
            </div>

            <div>
                <label for="new_password">New Password</label>
                <input type="password" id= "new_password" name = "new_password">
            </div>

            <button>Edit</button>
        </form>
      </div>
   </body>

</html>