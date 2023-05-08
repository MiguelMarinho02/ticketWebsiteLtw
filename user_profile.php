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

if($user_in_profile == null){
  header("Location: users.php");
}

?>

<!DOCTYPE html>
<html lang="en-US">
<link rel="stylesheet" href="css/style_index.css">
<link rel="stylesheet" href="css/user_profile.css">
   <head>
      <title>User Profile</title>
      <script src="script/script.js"></script>
   </head>

   <body>
    <div class="container_master">
      <div class="container">
        <ul class="buttons">
        <button class="index" onclick="indexPage()">Início</button>
            <button class="tickets" onclick="ticketsPage()">Tickets</button>
            <button class="faqs" onclick="faqsPage()">FAQ's</button>
            <button class="users" onclick="usersPage()">UserList</button>
            <button onclick="sendDataUser('<?php echo $user['username'] ?>')">User Profile</button>
            <?php if($user["role"] == "admin"): ?>
            <button class="admin_page" onclick="adminPage()">Admin Page</button>
            <?php endif; ?>
            <button class="logout" onclick="logoutPage()">Logout</button>
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