<?php
   declare(strict_types = 1);
   require_once('../database/connection.php');
   require_once('../utils/functions.php');
   session_start();
   $db = getDatabaseConnection();

   if (!isset($_SESSION["user_id"])){
    header("Location: login.php");
   }

   $user = searchUser($_SESSION["user_id"]);

?>

<!DOCTYPE html>
<html lang="en-US">
<link rel="stylesheet" href="../css/style_index.css">
<link rel="stylesheet" href="../css/index.css">
   <head>
      <title>Index</title>
      <script src="../script/script.js"></script>
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
      <h1>Welcome back <?php echo $user["username"]?>!</h1>
      <br>

      <h2>Your Active tickets as client</h2>
        <tbody>
            <?php
               getTicketsTableForUser(0); //prints clients table
            ?>
        </tbody>
      </table>
      <?php if($user["role"] != "client"):?>
      <br>
      <h2>Your Active tickets as agent</h2>
        <tbody>
            <?php
               getTicketsTableForUser(1); //prints agent table
            ?>
        </tbody>
      </table>
      <?php endif; ?>
      </div> <?php //content div ?>
      </div> <?php //container div ?>
    </div>
   </body>

</html>
