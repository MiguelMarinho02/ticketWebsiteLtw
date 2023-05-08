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

$stmt = $db->prepare('SELECT * FROM user WHERE username = ?');
$stmt->execute(array($_GET["username"]));
$user_in_profile = $stmt->fetch();

if($user_in_profile == null){
  header("Location: users.php");
}

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["roleChange"])){
  $stmt = $db->prepare('UPDATE user SET role = ? WHERE id = ?');
  $stmt->execute(array($_POST["roleChange"],$user_in_profile["id"]));
  header("Location: {$_SERVER['REQUEST_URI']}");
  exit();
}

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["department"])){
  $stmt = $db->prepare('UPDATE user SET department_id = ? WHERE id = ?');
  $stmt->execute(array($_POST["department"],$user_in_profile["id"]));
  header("Location: {$_SERVER['REQUEST_URI']}");
  exit();
}

?>

<!DOCTYPE html>
<html lang="en-US">
<link rel="stylesheet" href="css/style_index.css">
<link rel="stylesheet" href="css/user_profile.css">
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
          <h3>Name: <?php echo $user_in_profile["name"]?></h3>
          <h3>Username: <?php echo $user_in_profile["username"]?></h3>
          <h3>Email: <?php echo $user_in_profile["email"]?></h3>
          <h3>Role: <?php echo $user_in_profile["role"]?></h3>
          <?php if($user_in_profile["role"] == "agent"):?>
            <h3>Department: <?php echo searchDepartment($user_in_profile["department_id"])["name"]?></h3>
          <?php endif;?>  
        </div>

        <?php if($user["role"] == "admin" && $user_in_profile["role"] != "admin"):?>
        <div class="roleChange">
          <form method="POST">
            <label for="roleChange">Change Role</label>
            <select name="roleChange" class="roleChange">
              <option value="admin">admin</option>
              <option value="agent">agent</option>
            </select> 
            <br>       
            <input type="submit" value="Change Role">
          </form>
        </div>
        <?php endif;?>

        <br>

        <?php if($user["role"] == "admin" && $user_in_profile["role"] == "agent"):?>
        <div class="departmentChange">
          <form method="POST">
            <label for="department">Change Department</label>
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
        </div>
        <?php endif;?>

        <?php if($user["id"] == $user_in_profile["id"]):?>
        <div class="edit">
          <a href="edit_profile.php">Edit Profile</a>
        </div>
        <?php endif;?>
      </div>
    </div>
   </body>

</html>