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

   $stmt = $db->prepare('SELECT * FROM faq');
   $stmt->execute();
   $faqs = $stmt->fetchAll();

   if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $stmt = $db->prepare('DELETE FROM faq WHERE id = ?');
    $stmt->execute(array($_POST["del"]));
    header("Location: faqs.php");
   }
?>

<!DOCTYPE html>
<html lang="en-US">
<link rel="stylesheet" href="../css/style_index.css">
<link rel="stylesheet" href="../css/faq.css">
<link rel="stylesheet" href="../css/responsive.css">
   <head>
      <title>FAQ's</title>
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
      <?php if($user["role"] != "client"): ?>
        <div class="title-and-button">
          <h3 class="title">FAQ's</h3>
          <div class="create_faq_master">
            <div class="create_faq"><a href="create_faq.php">Create FAQ</a></div>
          </div>
        </div>  
      <?php endif; ?>
      <?php if($user["role"] == "client"): ?>
        <h3 class="title">FAQ's</h3>
      <?php endif; ?>          
      <form method="POST" action="faqs.php">
      <?php
      foreach ($faqs as $faq) {
        echo "<div class='faq-box'>";
        $answer = $faq['answer'];
        $question = $faq['question'];
        echo "<h3>$question</h3>";
        echo "<p>$answer</p>";
        if($user["role"] != "client"){
            echo "<input type='hidden' name='del' value='".$faq['id']."' />";
            echo "<input type='submit' name='btnsubmit' value='Delete'/>";
        }
        echo "<br>";
        echo "</div>";
      }
      ?>
      </form>
    </div>
    </div>
   </body>
</html>
