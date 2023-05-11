<?php 
declare(strict_types = 1);
require_once('connection.php');

$db = getDatabaseConnection();

$search_input = $_GET["value"];
$search_input = '%' . $search_input . '%';

if($_GET["value"] == null){
    exit();
}

$stmt = $db->prepare('SELECT id,username,name,role FROM user WHERE (username LIKE ? or name LIKE ?) and role != ? LIMIT 3');
$stmt->execute(array($search_input,$search_input,"client"));
$results = $stmt->fetchAll();

if($results == null){
    exit();
}

$html = '<table>
<thead>
    <tr>
        <th>Assign</th>
        <th>Username</th>
        <th>Name</th>
        <th>Role</th>
    </tr>
</thead>
<tbody>
<tr>';
foreach ($results as $result) {
    
    $html .= "<tr>
    <td> <form method='POST'><label for='updateAgent'></label><input type='hidden' name='userId' value='". $result['id'] ."'><input type='submit' name='updateAgent' value='Click here to assign'><br></form>
    <td>" . $result['username'] . "</td>
    <td>" . $result['name'] . "</td>
    <td>" . $result['role'] . "</td>
    </tr>";
}
$html .= '</tr></tbody></table>';

echo $html;
?>