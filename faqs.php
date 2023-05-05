<?php
   declare(strict_types = 1);
   require_once('connection.php');
   session_start();
   $db = getDatabaseConnection();

   if (!isset($_SESSION["user_id"])){
    header("Location: login.php");
   }

   $stmt = $db->prepare('SELECT * FROM user WHERE id = ?');
   $stmt->execute(array($_SESSION["user_id"]));
   $user = $stmt->fetch();

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
<link rel="stylesheet" href="css/style_index.css">
<link rel="stylesheet" href="css/faq.css">
   <head>
      <title>Index</title>
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
            <li class="profile"><button onclick="sendData('<?php echo $user['username'] ?>')">User Profile</button></li>
            <?php if($user["role"] == "admin"): ?>
            <li class="admin_page"><a href="admin_page.php">Admin Page</a></li>
            <?php endif; ?>
            <li class="logout"><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="content">  
      <?php if($user["role"] != "client"): ?>
        <div class="create_faq_master">
          <h3 class="question">ahahhahahsoasoaoSA</h3> 
          <div class="create_faq"><a href="create_faq.php">Create FAQ</a></div>
        </div>  
      <?php endif; ?>          
      <form method="POST" action="faqs.php">
      <?php
      foreach ($faqs as $faq) {
        $answer = $faq['answer'];
        $question = $faq['question'];
        echo "<h3>$question</h3>";
        echo "<p>$answer</p>";
        if($user["role"] != "client"){
            echo "<input type='hidden' name='del' value='".$faq['id']."' />";
            echo "<input type='submit' name='btnsubmit' value='Delete'/>";
        }
        echo "<br>";
      }
      ?>
      </form>
    </div>
    </div>
   </body>
</html>
