<?php 
declare(strict_types = 1);
require_once('connection.php');

$db = getDatabaseConnection();

$search_input = $_GET["value"];
$search_input = '%' . $search_input . '%';

$stmt = $db->prepare('SELECT username,name,role FROM user WHERE username LIKE ? or name LIKE ?');
$stmt->execute(array($search_input,$search_input));
$results = $stmt->fetchAll();

$html = '<table>
<thead>
    <tr>
        <th>Username</th>
        <th>Name</th>
        <th>Role</th>
    </tr>
</thead>
<tbody>
<tr>';
foreach ($results as $result) {
    $html .= "<tr>
    <td> <button onclick=sendData('". $result['username'] ."')>". $result['username'] ."</button></td>
    <td>" . $result['name'] . "</td>
    <td>" . $result['role'] . "</td>
    </tr>";
}
$html .= '</tr></tbody></table>';

echo $html;
?>