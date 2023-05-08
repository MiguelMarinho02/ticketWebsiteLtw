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
date_default_timezone_set('Europe/London');

if($user == null){
  header("Location: index.php");
}

$stmt = $db->prepare('SELECT * FROM tickets WHERE id = ?');
$stmt->execute(array($_GET["ticket_id"]));
$ticket_to_display = $stmt->fetch();

//remove or update current agent
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["updateAgent"])) {
  $updated_at = date("F j, Y, g:i a");
  echo $_POST["updateAgent"];

  if($_POST["userId"] == "N/A"){
    $stmt = $db->prepare('UPDATE tickets set agent_id = ?, updated_at = ?, status = ? WHERE id = ?');
    $stmt->execute(array(null,$updated_at,"open",$ticket_to_display["id"]));
    $db = null;
    insertChangeToTicket($user["id"],$ticket_to_display["id"],"REMOVED CURRENT AGENT");
  }
  else{
    if($_POST["userId"] != $ticket_to_display["client_id"]){
      $stmt = $db->prepare('UPDATE tickets set agent_id = ?, updated_at = ?, status = ? WHERE id = ?');
      $stmt->execute(array($_POST['userId'],$updated_at,"assigned",$ticket_to_display["id"]));
      $db = null;
      $msg = "Ticket assigned to " + searchUser($_POST["userId"])["username"];
      insertChangeToTicket($user["id"],$ticket_to_display["id"],$msg); 
    }
  }
  header("Location: {$_SERVER['REQUEST_URI']}");
  exit();
}

//change the department
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["department"])) {
  $updated_at = date("F j, Y, g:i a");
  $stmt = $db->prepare('UPDATE tickets set department_id = ?, updated_at = ? WHERE id = ?');
  $stmt->execute(array($_POST["department"],$updated_at,$ticket_to_display["id"]));
  $db = null;
  $msg = "Changed department to " + searchDepartment($_POST["department"])["name"];
  insertChangeToTicket($user["id"],$ticket_to_display["id"],$msg);
  header("Location: {$_SERVER['REQUEST_URI']}");
  exit();
}

//change the status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["status"])) {
  $updated_at = date("F j, Y, g:i a");
  $stmt = $db->prepare('UPDATE tickets set status = ?, updated_at = ? WHERE id = ?');
  $stmt->execute(array($_POST["status"],$updated_at,$ticket_to_display["id"]));
  $db = null;
  $msg = "Changed status to " + $_POST["status"];
  insertChangeToTicket($user["id"],$ticket_to_display["id"],$msg);
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
      <title>TicketPage</title>
      <script src="script/script.js" defer></script>
   </head>

   <body>
    <div class="container_master">
      <div class="container">
        <ul class="buttons">
            <li class="index"><a href="index.php">Início</a></li>
            <li class="tickets"><a href="tickets.php">Tickets</a></li>
            <li class="faqs"><a href="faqs.php">FAQ's</a></li>
            <li class="users"><a href="users.php">UserList</a></li>
            <button onclick="sendDataUser('<?php echo $user['username'] ?>')">User Profile</button>
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
          <h3>Status: <?php echo $ticket_to_display["status"]; ?></h3>
        </div>

        <?php if(($user["role"] != "client")):?>

        <?php if ($ticket_to_display["status"] != "closed"): ?>
            
            <div>
              <form method="POST">
                <input type="hidden" value="N/A" name="userId">
                <input type="submit" value="Remove current Agent" name="updateAgent">
              </form>
            </div>
            <br>

            <div class="search_wrapper">
              <label for="search">Search Users</label><br>
              <input type="search" id="search_user_ticket" placeholder="Search.." style="width: 300px; height: 30px; margin-top: 7px;">
            </div>

            <div id="search-result">
            </div>
            <br>
        <?php endif; ?>

        <form method="POST">
          <label for="department">Department</label>
            <?php
              $departments = getAllDepartments();
              echo "<select name='department' class='department'>";
              foreach($departments as $department){
                $d_name = $department['name'];
                $department_id = $department['id'];
                echo "<option value='$department_id'>$d_name</option>";
              }
              echo "</select>";
            ?>
            <br>       
            <input type="submit" value="Change Department">
        </form>

        <br>
        <form method="POST">
          <label for="status">Status</label>
          <select name="status" class="status">
            <option value="open">open</option>
            <option value="closed">closed</option>
          </select> 
          <br>       
          <input type="submit" value="Change Status">
        </form>  
        <?php endif;?>  
      </div>
    </div>
   </body>

</html>