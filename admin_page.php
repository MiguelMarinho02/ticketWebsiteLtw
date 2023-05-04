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
   <head>
      <title>AdminPage</title>
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

                //in order to use the option menu we will need ajax apparently
                echo "<tr>
                    <td>" . $user_list['id'] . "</td>
                    <td>" . $user_list['name'] . "</td>
                    <td>" . $user_list['username'] . "</td>
                    <td>" . $user_list['role'] . "</td>
                    <td class='update_no_borders'><button type='submit' name='update_user' value='".$user_list['id']."'>Update Role</button>
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