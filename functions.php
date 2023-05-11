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
        $stmt = $db->prepare('SELECT * FROM tickets WHERE client_id = ? and status != ?');
        $stmt->execute(array($user["id"],"closed"));
        $tickets = $stmt->fetchAll();
    }
    else if($paramter == 1){
        $stmt = $db->prepare('SELECT * FROM tickets WHERE agent_id = ? and status != ?');
        $stmt->execute(array($user["id"],"closed"));
        $tickets = $stmt->fetchAll();
    }
    else if($paramter == 2){ 
        $stmt = $db->prepare('SELECT * FROM tickets');
        $stmt->execute();
        $tickets = $stmt->fetchAll();
    }

    if($tickets == null){
        if($paramter == 0){
            echo "<h3>You have no tickets as a client</h3>";
        }
        if($paramter == 1){
            echo "<h3>You have no tickets as an agent</h3>";
        }
        if($paramter == 2){
            echo "<h3>There are no tickets</h3>";
        }
        return 0;
    }

    echo "<table>
    <thead>
        <tr>
            <th>ID<br><em>(Press ID to open)</em></th>
            <th>Department</th>
            <th>Client</th>
            <th>Agent</th>
            <th>Subject</th>
            <th>Status</th>
            <th>Priority</th>
            <th>Date</th>
        </tr>
    </thead>";

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
            <td> <button onclick=sendDataTicket('". $ticket['id'] ."')>".$ticket['id']."</button></td>
            <td>" . $department['name'] . "</td>
            <td>" . $c_user['name'] . "</td>
            <td>" . $a_user['name'] . "</td>
            <td>" . $ticket['subject'] . "</td>
            <td>" . $ticket['status'] . "</td>
            <td>" . $ticket['priority'] . "</td>
            <td>" . $ticket['updated_at'] . "</td>
            </tr>";   
}
}

function getAllUsersWithLimit(){
    $db = getDatabaseConnection();
    $stmt = $db->prepare('SELECT username,name,role FROM user LIMIT 10');
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

function getAllDepartments(){
    $db = getDatabaseConnection();
    $stmt = $db->prepare('SELECT * FROM department');
    $stmt->execute();
    $departments = $stmt->fetchAll();
    return $departments;
}

function insertChangeToTicket($user_id,$ticket_id,$change){
    $db = getDatabaseConnection();
    $stmt = $db->prepare('INSERT INTO changesToTicket (user_id,ticket_id,change)VALUES (?,?,?)');
    $stmt->execute(array($user_id,$ticket_id,$change));
    $db = null;
}

function searchTicket($ticket_id){
    $db = getDatabaseConnection();
    $stmt = $db->prepare('SELECT * FROM tickets WHERE id = ?');
    $stmt->execute(array($ticket_id));
    $ticket = $stmt->fetch();
    return $ticket;
}

function getMessagesFromTicket($ticket_id){
    $db = getDatabaseConnection();
    $stmt = $db->prepare('SELECT * FROM message WHERE ticket_id = ?');
    $stmt->execute(array($ticket_id));
    $messages = $stmt->fetchAll();
    return $messages;
}

?>