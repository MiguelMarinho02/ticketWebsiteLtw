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
<link rel="stylesheet" href="css/style_index.css">
   <head>
      <title>User Profile</title>
      <script src="script/script.js"></script>
   </head>

   <body>
    <div class="container_master">
      <div class="container">
        <ul class="buttons">
            <li class="index"><a href="index.php">Início</a></li>
            <li class="tickets"><a href="tickets.php">Tickets</a></li>
            <li class="faqs"><a href="faqs.php">FAQ's</a></li>
            <li class="users"><a href="users.php">UserList</a></li>
            <li class="profile"><button onclick="sendData('<?php echo $user['username'] ?>')">User Profile</button></li>
            <?php if($user["role"] == "admin"): ?>
            <li class="admin_page"><a href="admin_page.php">Admin Page</a></li>
            <?php endif; ?>
            <li class="logout"><a href="logout.php">Logout</a></li>
        </ul>
      </div>
      <div class="content">
        <div>
          <h3>Name: <?php echo $user_in_profile["name"]?></h3>
          <h3>Username: <?php echo $user_in_profile["username"]?></h3>
          <h3>Email: <?php echo $user_in_profile["email"]?></h3>
          <h3>Role: <?php echo $user_in_profile["role"]?></h3>
        </div>

        <?php if($user["id"] == $user_in_profile["id"]):?>
        <div class="edit">
          <a href="edit_profile.php">Edit Profile</a>
        </div>
        <?php endif;?>
      </div>
    </div>
   </body>

</html>