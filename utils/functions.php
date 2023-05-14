<?php

declare(strict_types = 1);
require_once('../database/connection.php');
session_start();
function searchUser($id){
    $db = getDatabaseConnection();
    $stmt = $db->prepare('SELECT * FROM user WHERE id = ?');
    $stmt->execute(array($id));
    return $stmt->fetch();
}

function getAllTickets(){
    $db = getDatabaseConnection();
    $stmt = $db->prepare('SELECT * FROM tickets');
    $stmt->execute();
    $tickets = $stmt->fetchAll();
    return $tickets;
}

function getAllTicketsWithLimit($limit){
    $db = getDatabaseConnection();
    $stmt = $db->prepare('SELECT * FROM tickets LIMIT ?');
    $stmt->execute(array($limit));
    $tickets = $stmt->fetchAll();
    return $tickets;
}

function searchTagById($tagId){
    $db = getDatabaseConnection();
    $stmt = $db->prepare('SELECT * FROM hashtags WHERE id = ?');
    $stmt->execute(array($tagId));
    $tag = $stmt->fetch();
    return $tag;
}

function getTagsFromTicket($ticketId){
    $db = getDatabaseConnection();
    $stmt = $db->prepare('SELECT hashtags.* FROM tickets JOIN ticket_hashtags ON tickets.id = ticket_hashtags.ticket_id
                                            JOIN hashtags ON hashtags.id = ticket_hashtags.hashtag_id
                                            WHERE tickets.id = ?');
    $stmt->execute(array($ticketId));
    $tags = $stmt->fetchAll();
    return $tags;
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
        $tickets = getAllTickets();
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

function getAllUsersWithLimit($limit){
    $db = getDatabaseConnection();
    $stmt = $db->prepare('SELECT username,name,role FROM user LIMIT ?');
    $stmt->execute(array($limit));
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

function insertChangeToTicket($user_id,$ticket_id,$change,$date){
    $db = getDatabaseConnection();
    $stmt = $db->prepare('INSERT INTO changesToTicket (user_id,ticket_id,change,date)VALUES (?,?,?,?)');
    $stmt->execute(array($user_id,$ticket_id,$change,$date));
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

function searchTag($tagName){
    $db = getDatabaseConnection();
    $stmt = $db->prepare('SELECT * FROM hashtags WHERE hashtag = ?');
    $stmt->execute(array($tagName));
    $hashtag = $stmt->fetch();
    return $hashtag;
}

function insertTag($tagName){
    $db = getDatabaseConnection();
    $stmt = $db->prepare('INSERT INTO hashtags (hashtag) VALUES (?)');
    $stmt->execute(array($tagName));
    $db = null;
}

function checkIfTagIsAssociated($tagId,$ticketId){
    $db = getDatabaseConnection();
    $stmt = $db->prepare('SELECT * FROM ticket_hashtags WHERE hashtag_id = ? and ticket_id = ?');
    $stmt->execute(array($tagId,$ticketId));
    $result = $stmt->fetch();
    echo $result["hashtag_id"];
    if($result == null){
        return false;
    }
    return true;
}

?>