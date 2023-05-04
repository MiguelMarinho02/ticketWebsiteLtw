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
<link rel="stylesheet" href="css/style_index.css">
   <head>
      <title>UserList</title>
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
            <li class="profile"><button onclick="sendDataUser('<?php echo $user['username'] ?>')">User Profile</button></li>
            <?php if($user["role"] == "admin"): ?>
            <li class="admin_page"><a href="admin_page.php">Admin Page</a></li>
            <?php endif; ?>
            <li class="logout"><a href="logout.php">Logout</a></li>
        </ul>
      </div>
      
      <div class="content">

      <h2>List of users</h2>

      <div class="search_wrapper">
        <label for="search">Search Users</label><br>
        <input type="search" id="search_user" placeholder="Search.." style="width: 300px; height: 30px; margin-top: 7px;">
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
                <td> <button onclick=sendDataUser('". $element['username'] ."')>". $element['username'] ."</button></td>
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
    </div>  
   </body>

</html>