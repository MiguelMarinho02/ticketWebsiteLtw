<?php 
declare(strict_types = 1);
require_once('../database/connection.php');
require_once('../utils/functions.php');

session_start();
$db = getDatabaseConnection();

$user = searchUser($_SESSION["user_id"]);

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$search_input = $_GET["value"];
$byYourDp = $_GET["filterByDp"];
$byDate = $_GET["filterByDate"];

if($search_input == "" && $byDate == "false"){
    $results = getAllTicketsWithLimit($limit);
}

else{
    //parse search_input
    $tags = explode(',',$search_input);
    $query = 'SELECT DISTINCT tickets.* FROM tickets 
    JOIN ticket_hashtags ON tickets.id = ticket_hashtags.ticket_id 
    JOIN hashtags ON hashtags.id = ticket_hashtags.hashtag_id
    WHERE ';
    $query .= '(hashtags.hashtag LIKE ?) ';
    $newArray = array();
    $tags = array_filter($tags); 
    foreach($tags as $tag){
        $tag = '%' . $tag . '%';
        array_push($newArray,$tag);
    }

    for($i = 0; $i < count($tags) -1;$i++){
        $query .= 'OR (hashtags.hashtag LIKE ?)';
    }

    if($byDate == "true"){
        $query .= ' ORDER BY tickets.updated_at DESC';
    }

    $query .= ' LIMIT ?';

    if($newArray[0] == null && $byDate == "true"){
        $query = 'SELECT * FROM tickets ORDER BY tickets.updated_at DESC LIMIT ?';
    }
    array_push($newArray,$limit);
    
    //exectute querry
    $stmt = $db->prepare($query);
    $stmt->execute($newArray);
    $results = $stmt->fetchAll();
}

if($results == null){
    echo "No results found";
    exit();
}

$html = '<table>
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
</thead>
<tbody>';

$checked_inside_loop = false;
foreach ($results as $ticket) {
    if($byYourDp == "true" && $user["role"] == "agent" && $ticket["department_id"] != $user["department_id"]){
        continue;
    }

    $checked_inside_loop = true;
    $html .= "<tr>
    <td> <button onclick=sendDataTicket('". $ticket['id'] ."')>".$ticket['id']."</button></td>
    <td>" . (searchDepartment($ticket["department_id"])["name"] ?? "N/A") . "</td>
    <td>" . searchUser($ticket["client_id"])["username"] . "</td>
    <td>" . (searchUser($ticket["agent_id"])["username"] ?? "N/A") . "</td>
    <td>" . $ticket['subject'] . "</td>
    <td>" . $ticket['status'] . "</td>
    <td>" . $ticket['priority'] . "</td>
    <td>" . $ticket['updated_at'] . "</td>
    </tr>";
}
$html .= '</tbody></table>';

if(!$checked_inside_loop){
    echo "No results found";
    exit();
}

echo $html;
?>