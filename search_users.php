<?php 
declare(strict_types = 1);
require_once('connection.php');
require_once('functions.php');

$db = getDatabaseConnection();

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$search_input = $_GET["value"];

if($search_input == ""){
    $results = getAllUsersWithLimit();
}

else{
    $search_input = '%' . $search_input . '%';
    $stmt = $db->prepare('SELECT username,name,role FROM user WHERE username LIKE ? or name LIKE ? LIMIT ?');
    $stmt->execute(array($search_input,$search_input,$limit));
    $results = $stmt->fetchAll();
}

if($results == null){
    exit();
}

$html = '<table>
<thead>
    <tr>
        <th>Username<br><em>(Press to see profile)</em></th>
        <th>Name</th>
        <th>Role</th>
    </tr>
</thead>
<tbody>
<tr>';
foreach ($results as $result) {
    $html .= "<tr>
    <td> <button onclick=sendDataUser('". $result['username'] ."')>". $result['username'] ."</button></td>
    <td>" . $result['name'] . "</td>
    <td>" . $result['role'] . "</td>
    </tr>";
}
$html .= '</tr></tbody></table>';

echo $html;
?>