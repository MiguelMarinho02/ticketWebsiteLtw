<?php
declare(strict_types = 1);
require_once('../database/connection.php');
require_once('../utils/functions.php');
session_start();
$db = getDatabaseConnection();

if (!isset($_SESSION["user_id"])){
 header("Location: login.php");
}

$user = searchUser($_SESSION["user_id"]);

if($user["role"] != "admin"){
 header("Location: index.php");
}

$valid_input = true;
if ($_SERVER["REQUEST_METHOD"] === "POST"){
    if(!empty($_POST["name"])){
        $stmt = $db->prepare('INSERT INTO department (name) VALUES (?)');
        $stmt->execute(array($_POST["name"]));
        header("Location: admin_page.php");
    }
    $valid_input = false;
}

?>

<!DOCTYPE html>
<html>
<link rel="stylesheet" href="../css/form.css">
<head>
    <title>Create Department</title>
    <meta charset="utf-8">
</head>
<body>
    <div class="box">
        <div class="back">
            <button type="submit"><a href="admin_page.php">Back</a></button>
        </div>
        <div class="title">
            <h2>Create a Department</h2>
        </div>

        <?php if(!$valid_input):?>
        <p><em>Please Input a name</em></p>
        <?php endif; ?>

        <form method="POST">
            <div>
                <label for="name">Name</label>
                <input type="name" id= "name" name = "name">
            </div>

            <button>Create</button>
        </form>

        </div>
</body>
</html>