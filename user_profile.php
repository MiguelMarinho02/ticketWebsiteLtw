<?php

declare(strict_types = 1);
require_once('connection.php');
session_start();
$db = getDatabaseConnection();

$stmt = $db->prepare('SELECT * FROM user WHERE id = ?');
$stmt->execute(array($_SESSION["user_id"]));
$user = $stmt->fetch();

?>

<!DOCTYPE html>
<html lang="en-US">
<link rel="stylesheet" href="style_index.css">
   <head>
      <title>User Profile</title>
   </head>

   <body>
      <div class="buttons">
        <div class="index">
            <button type="submit" ><a href = "index.php"><b>Início</b></a></button>
        </div>
        <br>
        <div class="tickets">
            <button type="submit"><a href="tickets.php">Tickets</a></button>
        </div>
        <br>
        <div class="faqs">
            <button type="submit"><a href="faqs.php">Faqs</a></button>
        </div>
        <br>
        <div class="profile">
            <button type="submit"><a href="user_profile.php">Edit profile</a></button>
        </div>
        <br>
        <div class="logout">
            <button type="submit"><a href="logout.php">Logout</a></button>
        </div>
      </div>
      <br>
      <div>
        <h3>Name: <?php echo $user["name"]?></h3>
        <h3>Username: <?php echo $user["username"]?></h3>
        <h3>Email: <?php echo $user["email"]?></h3>
      </div>

      <div class="edit">
        <button type="submit"><a href="edit_profile.php">Edit Profile</a></button>
      </div>
   </body>

</html>