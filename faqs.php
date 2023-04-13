<?php
   declare(strict_types = 1);
   require_once('connection.php');
   $db = getDatabaseConnection();
   $stmt = $db->prepare('SELECT * FROM faq');
   $stmt->execute();
   $faqs = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en-US">
   <body>
      <?php
         foreach ($faqs as $faq) {
            $answer = $faq['answer'];
            $question = $faq['question'];
            echo "<p>$question</p>";
            echo $answer;
         }
      ?>
   </body>
</html>