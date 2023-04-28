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
<link rel="stylesheet" href="style_index.css">
   <head>
      <title>Index</title>
      <script src="script/script.js"></script>
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
      <br>
      <?php if($user["role"] != "client"): ?>
      <a href="create_faq.php">Create FAQ</a>
      <?php endif; ?>
   </body>
</html>