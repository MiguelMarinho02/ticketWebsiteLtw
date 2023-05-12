<?php

declare(strict_types = 1);
require_once('../database/connection.php');
require_once('../utils/functions.php');
session_start();
if (!isset($_SESSION["user_id"])){
  header("Location: login.php");
}

$db = getDatabaseConnection();
$user = searchUser($_SESSION["user_id"]);

if($user["role"] == "client"){
    header("Location: tickets.php");
}

$ticket = searchTicket($_GET["ticket_id"]);

if($ticket == null){
    header("Location: tickets.php");
}

$stmt = $db->prepare('SELECT * FROM changesToTicket WHERE ticket_id = ?');
$stmt->execute(array($_GET["ticket_id"]));
$changes = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en-US">
<link rel="stylesheet" href="../css/style_index.css">
<link rel="stylesheet" href="../css/user_profile.css">
   <head>
      <title>List of Changes</title>
      <script src="../script/script.js"></script>
   </head>

   <body>
    <div class="container_master">
      <div class="container">
        <ul class="buttons">
        <button class="index" onclick="indexPage()">Início</button>
            <button class="tickets" onclick="ticketsPage()">Tickets</button>
            <button class="faqs" onclick="faqsPage()">FAQ's</button>
            <button class="users" onclick="usersPage()">UserList</button>
            <button class="user_profile" onclick="sendDataUser('<?php echo $user['username'] ?>')">User Profile</button>
            <?php if($user["role"] == "admin"): ?>
            <button class="admin_page" onclick="adminPage()">Admin Page</button>
            <?php endif; ?>
            <button class="logout" onclick="logoutPage()">Logout</button>
        </ul>
      </div>
      <div class="content">
        <h2>Changes:</h2>
        <?php
          foreach($changes as $change){
            $change_author = searchUser($change["user_id"]);
            echo "<h3>Change made by " . $change_author["username"] . ":</h3>";
            echo "<p>" . $change["change"] . "</p>";
            echo "<br>";
          } 
        ?>
      </div>
    </div>
   </body>

</html>