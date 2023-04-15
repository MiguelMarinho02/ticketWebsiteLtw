<?php
   declare(strict_types = 1);
   require_once('connection.php');
   $db = getDatabaseConnection();
   $stmt = $db->prepare('SELECT * FROM tickets');
   $stmt->execute();
   $tickets = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en-US">
<link rel="stylesheet" href="style_index.css">
   <head>
      <title>Index</title>
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

      <h1>Welcome!</h1>
      <br>

      <h2>Active tickets</h2>

   </body>

</html>