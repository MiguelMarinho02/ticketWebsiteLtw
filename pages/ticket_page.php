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
date_default_timezone_set('Europe/London');

if($user == null){
  header("Location: index.php");
}

$ticket_to_display = searchTicket($_GET["ticket_id"]);
$messages = getMessagesFromTicket($ticket_to_display["id"]);

//remove or update current agent
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["updateAgent"])) {
  $updated_at = date("F j, Y, g:i a");
  echo $_POST["updateAgent"];

  if($_POST["userId"] == "N/A"){
    $stmt = $db->prepare('UPDATE tickets set agent_id = ?, updated_at = ?, status = ? WHERE id = ?');
    $stmt->execute(array(null,$updated_at,"open",$ticket_to_display["id"]));
    $db = null;
    insertChangeToTicket($user["id"],$ticket_to_display["id"],"Removed current agent",$updated_at);
  }
  else{
    if($_POST["userId"] != $ticket_to_display["client_id"]){
      $stmt = $db->prepare('UPDATE tickets set agent_id = ?, updated_at = ?, status = ? WHERE id = ?');
      $stmt->execute(array($_POST['userId'],$updated_at,"assigned",$ticket_to_display["id"]));
      $db = null;
      $msg = "Ticket assigned to " . searchUser($_POST["userId"])["username"];
      insertChangeToTicket($user["id"],$ticket_to_display["id"],$msg,$updated_at); 
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
  $msg = "Changed department to " . searchDepartment($_POST["department"])["name"];
  insertChangeToTicket($user["id"],$ticket_to_display["id"],$msg,$updated_at);
  header("Location: {$_SERVER['REQUEST_URI']}");
  exit();
}

//change the status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["status"])) {
  $updated_at = date("F j, Y, g:i a");
  $stmt = $db->prepare('UPDATE tickets set status = ?, updated_at = ? WHERE id = ?');
  $stmt->execute(array($_POST["status"],$updated_at,$ticket_to_display["id"]));
  $db = null;
  $msg = "Changed status to " . $_POST["status"];
  insertChangeToTicket($user["id"],$ticket_to_display["id"],$msg,$updated_at);
  header("Location: {$_SERVER['REQUEST_URI']}");
  exit();
}

//apply tag
$error = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST["tag"]) && $_POST["tag"] != "")) {

  $tag = searchTag($_POST["tag"]);
  if($tag == null){
    $db = null;
    insertTag($_POST["tag"]);
    $db = getDatabaseConnection();
    $tag = searchTag($_POST["tag"]);
  }
  
  $error = checkIfTagIsAssociated($tag["id"],$ticket_to_display["id"]);

  if(!$error){
    $stmt = $db->prepare('INSERT INTO ticket_hashtags (hashtag_id,ticket_id) VALUES (?,?)');
    $stmt->execute(array($tag["id"],$ticket_to_display["id"]));

    $updated_at = date("F j, Y, g:i a");
    $stmt = $db->prepare('UPDATE tickets set updated_at = ? WHERE id = ?');
    $stmt->execute(array($updated_at,$ticket_to_display["id"]));

    $db = null;
    $msg = "Added tag " . $_POST["tag"];
    insertChangeToTicket($user["id"],$ticket_to_display["id"],$msg,$updated_at);

    header("Location: {$_SERVER['REQUEST_URI']}");
    exit();
  }
}

//remove tag
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["removeTag"])) {

  $stmt = $db->prepare('DELETE FROM ticket_hashtags WHERE ticket_id = ? and hashtag_id = ?');
  $stmt->execute(array($ticket_to_display["id"],$_POST["removeTag"]));

  $updated_at = date("F j, Y, g:i a");
  $stmt = $db->prepare('UPDATE tickets set updated_at = ? WHERE id = ?');
  $stmt->execute(array($updated_at,$ticket_to_display["id"]));

  $db = null;
  $msg = "Removed tag " . searchTagById($_POST["removeTag"])["hashtag"];
  insertChangeToTicket($user["id"],$ticket_to_display["id"],$msg,$updated_at);

  header("Location: {$_SERVER['REQUEST_URI']}");
  exit();
}

//send message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["sendReply"])) {
  $updated_at = date("F j, Y, g:i a");
  $stmt = $db->prepare('INSERT INTO message (msg,ticket_id,user_id,created_at) VALUES (?,?,?,?)');
  $stmt->execute(array($_POST["sendReply"],$ticket_to_display["id"],$user["id"],$updated_at));
  header("Location: {$_SERVER['REQUEST_URI']}");
  exit();
}

if($ticket_to_display == null){
    header("Location: tickets.php");
}

?>

<!DOCTYPE html>
<html lang="en-US">
<link rel="stylesheet" href="../css/style_index.css">
<link rel="stylesheet" href="../css/ticket_page.css">
   <head>
      <title>TicketPage</title>
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
        <div class="box">
          <div class="group">
          <div class="info">
            <h3>Created By: <?php $search_user = searchUser($ticket_to_display["client_id"]); echo $search_user["name"]?></h3>
            <h3>Agent assigned: <?php $search_user = searchUser($ticket_to_display["agent_id"]); if($search_user != null){echo $search_user["name"];}else{echo "N/A";}?></h3>
            <h3>Department: <?php $department = searchDepartment($ticket_to_display["department_id"]);if($department != null){echo $department["name"];}else{echo "N/A";}?></h3>
            <h3>Last updated: <?php if($ticket_to_display["updated_at"] == null){echo $ticket_to_display["created_at"];}else{echo $ticket_to_display["updated_at"];} ?></h3>
            <h3>Status: <?php echo $ticket_to_display["status"]; ?></h3>
            <?php if(($user["role"] != "client")):?>
              <div class="changes">
                <button onclick="sendDataTicketList('<?php echo $ticket_to_display['id'] ?>')">List Of Changes</button>
              </div>
              <?php if ($ticket_to_display["status"] != "closed"): ?>
            
                <div>
                  <form method="POST">
                    <input type="hidden" value="N/A" name="userId">
                    <input class="remove_agent" type="submit" value="Remove current Agent" name="updateAgent">
                  </form>
                </div>
                <br>
              <?php endif; ?>
            <?php endif; ?>

            
          </div>
          
          <div class="options">
            <?php if(($user["role"] != "client")):?>
              <?php if ($ticket_to_display["status"] != "closed"): ?>

                <div class="right-group">  
                  <div class="search_wrapper">
                    <label for="search">Search Users</label><br>
                    <input type="search" id="search_user_ticket" placeholder="Search.." style="width: 300px; height: 30px; margin-top: 7px;">
                  </div>

                  <div id="search-result">
                  </div>
                </div>

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
                  <input class="change_department" type="submit" value="Change Department">
                </form>

                <form method="POST">
                  <div class="search_wrapper">
                    <label for="tag">Apply Tag</label><br>
                    <?php if($error){echo "<p><em>Tag already used</em></p>";};?>
                    <input type="search" id="tag" name="tag" placeholder="Search.." style="width: 300px; height: 30px; margin-top: 7px;">
                    <br>
                    <input class="addTag" type="submit" value="Apply">
                  </div>
                </form>

              <?php endif; ?>
                <br>
                <form method="POST">
                  <label for="status">Status</label>
                  <select name="status" class="status">
                    <option value="open">open</option>
                    <option value="closed">closed</option>
                  </select> 
                  <br>       
                  <input class="change_status" type="submit" value="Change Status">
                </form>
                <br>
            <?php endif; ?>
          </div>
          </div> 
          
          <hr>
          <div class="tagsList">
            <h3>Hashtag List</h3>
            <?php
              $displayTags = getTagsFromTicket($ticket_to_display["id"]);
              if($displayTags == null){
                echo "No hashtags are associated with this ticket";
              }

              if($user["role"] == "client"){
                $html = "";
                foreach($displayTags as $displayTag){
                 $html .= $displayTag["hashtag"] . ", ";
                }
                $html = substr($html,0,-2);
                echo $html;
              }
              else{
                $html = "";
                foreach($displayTags as $displayTag){
                  $html .= '<form method="post">';
                  $html .= '<button type="submit" name="removeTag" value="' . $displayTag["id"] . '">' . $displayTag["hashtag"] . '</button>';
                  $html .= '</form>';
                }
                echo $html;
              }
            ?>
          </div>

          <hr>
          <div class="description">
            <h3>Description</h3>
            <p> <?php echo $ticket_to_display["description"] ?> </p>
          </div>                 

        </div>
          
        <br>
        <hr>  

        <?php if($user["id"] == $ticket_to_display["agent_id"] || $user["id"] == $ticket_to_display["client_id"]):?>          
          <div class="chat">
            <h3>Chat</h3>
            <?php
              foreach($messages as $message){
                if(searchUser($message["user_id"])["id"] == $user["id"]){
                  echo "<div class='userMsg'> <p>" . $message["msg"] . "</p> <h6>Sent at ".$message["created_at"]."</h6> </div>";
                  echo "<br>";
                }
                else{
                  echo "<div class='otherUserMsg'> <em>".searchUser($message["user_id"])["username"].":</em><br><p> " . $message["msg"] . "</p> <h6>Sent at ".$message["created_at"]."</h6> </div>";
                  echo "<br>";
                }
              }
            ?>

            <form method="POST">
              <input type="text" placeholder="Type your message..." name="sendReply">
			        <button>Send</button>
            </form>
          </div>
        <?php endif;?>
      </div>
    </div>
   </body>

</html>