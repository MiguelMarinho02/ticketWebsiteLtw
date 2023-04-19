<?php
session_start();
require_once('connection.php');


$db = getDatabaseConnection();

if(!empty($_POST['subject'] && !empty($_POST['description']))){
    $date = new DateTime();
    $date = $date->getTimestamp();
    $uniqueid = uniqid();
    
    $department_id = 3;
    $client_id = $_SESSION["user_id"];
    $agent_id = 4;
    $status = "open";
    $created_at = "1 jan 2019";
    $updated_at = NULL;

    $stmt = $db->prepare('INSERT INTO tickets (department_id, client_id, agent_id, subject, description, status, priority, created_at, updated_at) VALUES (?,?,?,?,?, "open",?,?,?)');
    $stmt->execute(array($department_id,$client_id,$agent_id, $_POST["subject"], $_POST["description"], $_POST["priority"], $created_at, $updated_at));
    header("Location: tickets.php");
}
else{
    echo 'error';
}

?>