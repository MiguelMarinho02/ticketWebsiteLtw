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

?>

<!DOCTYPE html>
<html lang="en-US">
<link rel="stylesheet" href="../css/style_index.css">
<link rel="stylesheet" href="../css/users.css">
<link rel="stylesheet" href="../css/responsive.css">
   <head>
      <title>UserList</title>
      <script src="../script/script.js" defer></script>
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

      <h2>List of users</h2>

      <div class="search_wrapper">
        <label for="search">Search Users</label><br>
        <input type="search" id="search_user" placeholder="Search.." style="width: 300px; height: 30px; margin-top: 7px;">
      </div>
      
      <div id="search-result" class="table">
      </div>
      <button id="show-more-user">Show More</button> 
      </div> <?php //content div ?>
    </div>  
   </body>

</html>