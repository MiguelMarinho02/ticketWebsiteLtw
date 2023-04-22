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
<link rel="stylesheet" href="style_index.css">
   <head>
      <title>AdminPage</title>
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

      <div>
        <h3>Departments</h3>
        <a href="create_department.php">Create department</a>
        <form method="POST" action="admin_page.php">
        <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Delete</th>
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
      </form>
      </div>

      <div>
        <h3>Users</h3>
        <form method="POST" action="admin_page.php">
        <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Username</th>
                <th>Role</th>
                <th>Update role</th>
                <th>Delete User</th>
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
                    <td><button type='submit' name='update_user' value='".$user_list['id']."'>Update Role</button></td>
                    <td><button type='submit' name='del_user' value='".$user_list['id']."'>Delete</button></td>
                </tr>";   
               }

            ?>
        </tbody>
      </table>
      </form>
      </div>
   </body>
</html>