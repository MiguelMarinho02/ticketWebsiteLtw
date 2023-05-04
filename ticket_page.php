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

if($user == null){
  header("Location: index.php");
}

$stmt = $db->prepare('SELECT * FROM tickets WHERE id = ?');
$stmt->execute(array($_GET["ticket_id"]));
$ticket_to_display = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["updateTicketWithAgent"])) {
  $updated_at = date("F j, Y, g:i a");
  $stmt = $db->prepare('UPDATE tickets set agent_id = ?, updated_at = ? WHERE id = ?');
  $stmt->execute(array($user["id"],$updated_at,$ticket_to_display["id"]));
  header("Location: {$_SERVER['REQUEST_URI']}");
  exit();
}

if($ticket_to_display == null){
    header("Location: tickets.php");
}

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
            <li class="profile"><button onclick="sendDataUser('<?php echo $user['username'] ?>')">User Profile</button></li>
            <?php if($user["role"] == "admin"): ?>
            <li class="admin_page"><a href="admin_page.php">Admin Page</a></li>
            <?php endif; ?>
            <li class="logout"><a href="logout.php">Logout</a></li>
        </ul>
      </div>
      <div class="content">
        <div>
          <h3>Created By: <?php $search_user = searchUser($ticket_to_display["client_id"]); echo $search_user["name"]?></h3>
          <h3>Agent assigned: <?php $search_user = searchUser($ticket_to_display["agent_id"]); if($search_user != null){echo $search_user["name"];}else{echo "N/A";}?></h3>
          <h3>Department: <?php $department = searchDepartment($ticket_to_display["department_id"]);if($department != null){echo $department["name"];}else{echo "N/A";}?></h3>
          <h3>Last updated: <?php if($ticket_to_display["updated_at"] == null){echo $ticket_to_display["created_at"];}else{echo $ticket_to_display["updated_at"];} ?></h3>
        </div>

        <?php if(($user["role"] != "client" && $ticket_to_display["agent_id"] == null) && ($user["id"] != $ticket_to_display["client_id"])):?>
        <div class="addAgent"> 
        <form method="POST">
          <input type="hidden" name="updateTicketWithAgent" value="updateTicketWithAgent">
          <button type="submit">Assign to self</button>
        </form>
        </div>
        <?php endif;?>
      </div>
    </div>
   </body>

</html>