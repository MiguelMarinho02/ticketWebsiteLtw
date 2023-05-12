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

    $search_input = '%' . $search_input . '%';
    $stmt = $db->prepare('SELECT DISTINCT tickets.* FROM tickets 
                          JOIN ticket_hashtags ON tickets.id = ticket_hashtags.ticket_id 
                          JOIN hashtags ON hashtags.id = ticket_hashtags.hashtag_id
                          WHERE (hashtags.hashtag LIKE ?) LIMIT ?');
    $stmt->execute(array($search_input,$limit));
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