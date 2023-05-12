<?php 
declare(strict_types = 1);
require_once('../database/connection.php');
require_once('../utils/functions.php');

$db = getDatabaseConnection();

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$search_input = $_GET["value"];

if($search_input == ""){
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

    $query .= ' LIMIT ?';
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

foreach ($results as $ticket) {
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

echo $html;
?>