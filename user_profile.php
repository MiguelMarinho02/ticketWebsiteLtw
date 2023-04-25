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

$stmt = $db->prepare('SELECT * FROM user WHERE username = ?');
$stmt->execute(array($_GET["username"]));
$user_in_profile = $stmt->fetch();

?>

<!DOCTYPE html>
<html lang="en-US">
<link rel="stylesheet" href="style_index.css">
   <head>
      <title>User Profile</title>
      <script src="script/script.js"></script>
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
        <div class="users">
            <button type="submit"><a href="users.php">UserList</a></button>
        </div>
        <br>
        <div class="profile">
        <button onclick="sendData('<?php echo $user['username'] ?>')">User Profile</button>
        </div>
        <br>
        <?php if($user["role"] == "admin"): ?>
        <div class="admin_page">
            <button type="submit"><a href="admin_page.php">Admin Page</a></button>
        </div>
        <br>
        <?php endif; ?>
        <div class="logout">
            <button type="submit"><a href="logout.php">Logout</a></button>
        </div>
      </div>

      <br>
      <div>
        <h3>Name: <?php echo $user_in_profile["name"]?></h3>
        <h3>Username: <?php echo $user_in_profile["username"]?></h3>
        <h3>Email: <?php echo $user_in_profile["email"]?></h3>
        <h3>Role: <?php echo $user_in_profile["role"]?></h3>
      </div>

      <?php if($user["id"] == $user_in_profile["id"]):?>
      <div class="edit">
        <button type="submit"><a href="edit_profile.php">Edit Profile</a></button>
      </div>
      <?php endif;?>
   </body>

</html>