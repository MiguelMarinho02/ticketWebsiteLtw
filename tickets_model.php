<?php
session_start();
require_once('connection.php');

$valid_ticket = true;
$db = getDatabaseConnection();

if(!empty($_POST['subject'] && !empty($_POST['description']))){
    $uniqueid = uniqid();
    
    $client_id = $_SESSION["user_id"];
    $agent_id = 4;
    $created_at = date("Y/m/d");
    $updated_at = NULL;

    $stmt = $db->prepare('INSERT INTO tickets (department_id, client_id, agent_id, subject, description, status, priority, created_at, updated_at) VALUES (?,?,?,?,?, "open",?,?,?)');
    $stmt->execute(array($_POST["department"],$client_id,$agent_id, $_POST["subject"], $_POST["description"], $_POST["priority"], $created_at, $updated_at));
    header("Location: tickets.php");
}
else{
    $valid_ticket = false;
    header("Location: tickets.php");
}

?>