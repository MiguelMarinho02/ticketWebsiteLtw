<?php
   declare(strict_types = 1);
   require_once('connection.php');
   require_once('functions.php');
   session_start();
   $db = getDatabaseConnection();

   if (!isset($_SESSION["user_id"])){
    header("Location: login.php");
   }

   $user = searchUser($_SESSION["user_id"]);

?>

<!DOCTYPE html>
<html lang="en-US">
<link rel="stylesheet" href="css/style_index.css">
<link rel="stylesheet" href="css/tickets.css">
   <head>
      <title>Tickets</title>
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
            <button class="user_profile" onclick="sendDataUser('<?php echo $user['username'] ?>')">User Profile</button>
            <?php if($user["role"] == "admin"): ?>
            <button class="admin_page" onclick="adminPage()">Admin Page</button>
            <?php endif; ?>
            <button class="logout" onclick="logoutPage()">Logout</button>
        </ul>
        </div>
      <div class="content">          
      <h2>Active tickets</h2>
      <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Department</th>
                <th>Client</th>
                <th>Agent</th>
                <th>Subject</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
               getTicketsTableForUser(2);
            ?>
        </tbody>
      </table>
      <br><br>
      <div class="create_ticket">
        <h3><a href="create_ticket.php">New ticket</a></h3>
      </div>
      </div>
    </div>
   </body>

</html>