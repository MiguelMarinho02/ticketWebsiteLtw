<?php
   declare(strict_types = 1);
   require_once('connection.php');
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

      <h1>Welcome <?php echo $user["username"]?>!</h1>
      <br>
      <h2>Your role is <?php echo $user["role"]?>.</h2>
      <br>

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
               foreach ($tickets as $ticket) {

                $department_id = $ticket['department_id'];
                $stmt = $db->prepare('SELECT * FROM department WHERE id = ?');
                $stmt->execute(array($department_id));
                $departments = $stmt->fetchAll();
                foreach ($departments as $department){};
                
                $client_id = $ticket['client_id'];
                $stmt = $db->prepare('SELECT * FROM user WHERE id = ?');
                $stmt->execute(array($client_id));
                $users = $stmt->fetchAll();
                foreach ($users as $c_user){};

                $agent_id = $ticket['agent_id'];
                $stmt = $db->prepare('SELECT * FROM user WHERE id = ?');
                $stmt->execute(array($agent_id));
                $users = $stmt->fetchAll();
                foreach ($users as $a_user){};
                
                echo "<tr>
                    <td>" . $ticket['id'] . "</td>
                    <td>" . $department['name'] . "</td>
                    <td>" . $c_user['name'] . "</td>
                    <td>" . $a_user['name'] . "</td>
                    <td>" . $ticket['subject'] . "</td>
                    <td>" . $ticket['status'] . "</td>
                    <td>" . $ticket['priority'] . "</td>
                    <td>" . $ticket['created_at'] . "</td>
                </tr>";   
               }
            ?>
        </tbody>
      </table>

   </body>

</html>