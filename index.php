<?php
   declare(strict_types = 1);
   require_once('connection.php');
   require_once('functions.php');
   session_start();
   $db = getDatabaseConnection();

   if (!isset($_SESSION["user_id"])){
    header("Location: login.php");
   }

   $stmt = $db->prepare('SELECT * FROM user WHERE id = ?');
   $stmt->execute(array($_SESSION["user_id"]));
   $user = $stmt->fetch();

?>

<!DOCTYPE html>
<html lang="en-US">
<link rel="stylesheet" href="css/style_index.css">
   <head>
      <title>Index</title>
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
      <h1>Welcome <?php echo $user["username"]?>!</h1>
      <br>
      <h2>Your role is <?php echo $user["role"]?>.</h2>
      <br>

      <h2>Your Active tickets as client</h2>
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
               getTicketsTableForUser(0); //prints clients table
            ?>
        </tbody>
      </table>
      <?php if($user["role"] != "client"):?>
      <br>
      <h2>Your Active tickets as agent</h2>
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
               getTicketsTableForUser(1); //prints agent table
            ?>
        </tbody>
      </table>
      <?php endif; ?>
      </div> <?php //content div ?>
      </div> <?php //container div ?>
    </div>
   </body>

</html>