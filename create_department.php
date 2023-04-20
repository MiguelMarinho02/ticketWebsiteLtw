<?php
declare(strict_types = 1);
require_once('connection.php');
session_start();
$db = getDatabaseConnection();

if (!isset($_SESSION["user_id"])){
 header("Location: login.php");
}

$stmt = $db->prepare('SELECT * FROM user WHERE id = ? and role = "admin"');
$stmt->execute(array($_SESSION["user_id"]));
$user = $stmt->fetch();

if($user == false){
 header("Location: index.php");
}

$valid_input = true;
if ($_SERVER["REQUEST_METHOD"] === "POST"){
    if(!empty($_POST["name"])){
        $stmt = $db->prepare('INSERT INTO department (name) VALUES (?)');
        $stmt->execute(array($_POST["name"]));
        header("Location: admin_page.php");
    }
}
$valid_input = false;

?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Department</title>
    <meta charset="utf-8">
</head>
<body>
    <header>
        <div class="back">
            <button type="submit"><a href="admin_page.php">Back</a></button>
        </div>
        <div class="title">
            <h2>Create a ticket</h2>
        </div>

        <?php if(!$valid_input):?>
        <em>Please Input a name</em>
        <?php endif; ?>

        <form method="POST">
            <div>
                <label for="name">Name</label>
                <input type="name" id= "name" name = "name">
            </div>

            <button>Create</button>
        </form>

    </header>
</body>
</html>