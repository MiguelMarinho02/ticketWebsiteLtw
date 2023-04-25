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

?>

<!DOCTYPE html>
<html lang="en-US">
<link rel="stylesheet" href="style_index.css">
   <head>
      <title>UserList</title>
      <script src="script/script.js" defer></script>
   </head>

   <body>

      <div class="container">
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
        <div class="users">
            <button type="submit"><a href="users.php">UserList</a></button>
        </div>
        <br>
        <div class="profile">
        <button onclick="sendData('<?php echo $user['username'] ?>')">User Profile</button>
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
      
      <div class="content">

      <h2>List of users</h2>

      <div class="search_wrapper">
        <label for="search">Search Users</label><br>
        <input type="search" id="search_user" placeholder="Search..">
      </div>
      
      <div id="search-result">
      <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Name</th>
                <th>Role</th>
            </tr>
        </thead>
        <tbody>
            <?php
               $users = getAllUsers();
               foreach($users as $element){
                echo "<tr>
                <td>" . $element['username'] . "</td>
                <td>" . $element['name'] . "</td>
                <td>" . $element['role'] . "</td>
                </tr>";
               }
            ?>
        </tbody>
      </table>
      </div>
      </div> <?php //content div ?>
      </div> <?php //container div ?>
   </body>

</html>