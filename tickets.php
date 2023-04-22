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

   $stmt = $db->prepare('SELECT * FROM tickets');
   $stmt->execute();
   $tickets = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en-US">
<link rel="stylesheet" href="style_index.css">
   <head>
      <title>Tickets</title>
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
      <h3><a href="create_ticket.php">New ticket</a></h3>

   </body>

</html>