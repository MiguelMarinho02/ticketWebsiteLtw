<?php 
declare(strict_types = 1);
require_once('connection.php');
session_start();
$db = getDatabaseConnection();

if (!isset($_SESSION["user_id"])){
 header("Location: login.php");
}

$stmt = $db->prepare('SELECT * FROM user WHERE id = ? and role = "admin"');
$stmt->execute(array($_SESSION["user_id"]));
$user = $stmt->fetch();

if($user == false){
 header("Location: index.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    if(!empty($_POST["del_dp"])){
       $stmt = $db->prepare('DELETE FROM department WHERE id = ?');
       $stmt->execute(array($_POST["del_dp"]));
       header("Location: admin_page.php");
    }

    if(!empty($_POST["del_user"])){
       $stmt = $db->prepare('DELETE FROM user WHERE id = ?');
       $stmt->execute(array($_POST["del_user"]));
       header("Location: admin_page.php");
    }
}

?>

<!DOCTYPE html>
<html lang="en-US">
<link rel="stylesheet" href="css/style_index.css">
<link rel="stylesheet" href="css/admin_page.css">
   <head>
      <title>AdminPage</title>
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
        <div class="content_department">
        <h3>Departments</h3>
        <form method="POST" action="admin_page.php">
        <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                
            </tr>
        </thead>
        <tbody>
            <?php

               $db = getDatabaseConnection();
               $stmt = $db->prepare('SELECT * FROM department');
               $stmt->execute();
               $departments = $stmt->fetchAll();

               foreach ($departments as $department) {

                echo "<tr>
                    <td>" . $department['id'] . "</td>
                    <td>" . $department['name'] . "</td>
                    <td><button type='submit' name='del_dp' value='".$department['id']."'>Delete</button></td>
                </tr>";   
               }

            ?>
        </tbody>
      </table>
      <br>
      <a href="create_department.php">Create department</a>
      </form>
      </div>

      <div class="content_user">
        <h3>Users</h3>
        <form method="POST" action="admin_page.php">
        <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Username</th>
                <th>Role</th>
        
            </tr>
        </thead>
        <tbody>
            <?php

               $db = getDatabaseConnection();
               $stmt = $db->prepare('SELECT * FROM user');
               $stmt->execute();
               $users_list = $stmt->fetchAll();

               foreach ($users_list as $user_list) {

                echo "<tr>
                    <td>" . $user_list['id'] . "</td>
                    <td>" . $user_list['name'] . "</td>
                    <td>" . $user_list['username'] . "</td>
                    <td>" . $user_list['role'] . "</td>
                    <td class='update_no_borders'>
                    <button type='submit' name='del_user' value='".$user_list['id']."'>Delete</button></td>
                </tr>";   
               }

            ?>
        </tbody>
      </table>
      </form>
      </div>
   <div>
   </div>    
   </body>
</html>