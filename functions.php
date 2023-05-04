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

//paramter == 0 (search by client_id)
//paramter == 1 (serach by agent_id)
//paramter == 2 (search all)
function getTicketsTableForUser($paramter){

    $db = getDatabaseConnection();
 
    $user = searchUser($_SESSION["user_id"]);

    if($paramter == 0){
        $stmt = $db->prepare('SELECT * FROM tickets WHERE client_id = ?');
        $stmt->execute(array($user["id"]));
        $tickets = $stmt->fetchAll();
    }
    else if(!$paramter == 1){
        $stmt = $db->prepare('SELECT * FROM tickets WHERE agent_id = ?');
        $stmt->execute(array($user["id"]));
        $tickets = $stmt->fetchAll();
    }
    else if($paramter == 2){ 
        $stmt = $db->prepare('SELECT * FROM tickets');
        $stmt->execute();
        $tickets = $stmt->fetchAll();
    }

    foreach ($tickets as $ticket) {

        $stmt = $db->prepare('SELECT * FROM department WHERE id = ?');
        $stmt->execute(array($ticket['department_id']));
        $department = $stmt->fetch();
        if($department == null){$department["name"] = "N\A";}
                
        $stmt = $db->prepare('SELECT * FROM user WHERE id = ?');
        $stmt->execute(array($ticket['client_id']));
        $c_user = $stmt->fetch();
        if($c_user == null){$c_user["name"] = "N\A";}

        $stmt = $db->prepare('SELECT * FROM user WHERE id = ?');
        $stmt->execute(array($ticket['agent_id']));
        $a_user = $stmt->fetch();
        if($a_user == null){$a_user["name"] = "N\A";}
                
        echo "<tr>
            <td> <button onclick=sendDataTicket('". $ticket['id'] ."')>Go Check</button></td>
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

function getAllUsers(){
    $db = getDatabaseConnection();
    $stmt = $db->prepare('SELECT username,name,role FROM user');
    $stmt->execute();
    $users = $stmt->fetchAll();
    return $users;
}

function searchDepartment($department_id){
    $db = getDatabaseConnection();
    $stmt = $db->prepare('SELECT * FROM department WHERE id = ?');
    $stmt->execute(array($department_id));
    $department = $stmt->fetch();
    return $department;
}

function updateTicketWithAgent($agent_id, $ticket_id){
    $db = getDatabaseConnection();
    $updated_at = date("F j, Y, g:i a");
    $stmt = $db->prepare('UPDATE tickets set agent_id = ?, updated_at = ? WHERE id = ?');
    $stmt->execute(array($agent_id,$updated_at,$ticket_id));
}

?>