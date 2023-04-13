<?php
   declare(strict_types = 1);
   require_once('connection.php');
   $db = getDatabaseConnection();
   $stmt = $db->prepare('SELECT * FROM tickets');
   $stmt->execute();
   $tickets = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en-US">
   
</html>