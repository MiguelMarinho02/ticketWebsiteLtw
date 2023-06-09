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
<link rel="stylesheet" href="../css/tickets.css">
<link rel="stylesheet" href="../css/responsive.css">
   <head>
      <title>Tickets</title>
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
      <h2>Tickets</h2>
      <div class="create_ticket">
        <h3><a href="create_ticket.php">New ticket</a></h3>
      </div>
      
      <div class="search_wrapper">
        <label for="search">Search Tickets</label><br>
        <input type="search" id="search-tickets" placeholder="Search by tag.." style="width: 300px; height: 30px; margin-top: 7px;">
      </div>

      <br>
      <div class="filters">
         <label for="filters">Filters</label><br>
         <?php if($user["role"] == "agent"):?>
         <input type="checkbox" id="filterByYourDepartment">By Your Department</input>
         <?php endif; ?>
         <input type="checkbox" id="filterByDate">By last updated ticket</input>
      </div>

      <div id="ticket-results">
      </div>  
      <br>
      <div class="button">
         <button id="show-more-tickets">Show More</button>
      </div>
      <br>    
    </div>
   </body>

</html>