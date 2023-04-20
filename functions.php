<?php

 declare(strict_types = 1);
 require_once('connection.php');
 session_start();
function searchUser($id){
    $db = getDatabaseConnection();
    $stmt = $db->prepare('SELECT * FROM user WHERE id = ?');
    $stmt->execute(array($id));
    return $stmt->fetch();
}

function getTicketsTableForUser($paramter){

    $db = getDatabaseConnection();
 
    $user = searchUser($_SESSION["user_id"]);

    if($paramter){
        $stmt = $db->prepare('SELECT * FROM tickets WHERE client_id = ?');
        $stmt->execute(array($user["id"]));
        $tickets = $stmt->fetchAll();
    }
    else{
        $stmt = $db->prepare('SELECT * FROM tickets WHERE agent_id = ?');
        $stmt->execute(array($user["id"]));
        $tickets = $stmt->fetchAll();
    }

    foreach ($tickets as $ticket) {

        $stmt = $db->prepare('SELECT * FROM department WHERE id = ?');
        $stmt->execute(array($ticket['department_id']));
        $department = $stmt->fetch();
                
        $stmt = $db->prepare('SELECT * FROM user WHERE id = ?');
        $stmt->execute(array($ticket['client_id']));
        $c_user = $stmt->fetch();

        $stmt = $db->prepare('SELECT * FROM user WHERE id = ?');
        $stmt->execute(array($ticket['agent_id']));
        $a_user = $stmt->fetch();
                
        echo "<tr>
            <td>" . $ticket['id'] . "</td>
            <td>" . $department['name'] . "</td>
            <td>" . $c_user['name'] . "</td>
            <td>" . $a_user['name'] . "</td>
            <td>" . $ticket['subject'] . "</td>
            <td>" . $ticket['status'] . "</td>
            <td>" . $ticket['priority'] . "</td>
            <td>" . $ticket['created_at'] . "</td>
            </tr>";   
}
}

?>