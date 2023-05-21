<?php 
declare(strict_types = 1);
require_once('../database/connection.php');
require_once('../utils/functions.php');

$db = getDatabaseConnection();

$search_input = $_GET["value"];

if($search_input == ""){
    echo "";
    return;
}

$search_input = '%' . $search_input . '%';
$stmt = $db->prepare('SELECT * FROM hashtags WHERE hashtag LIKE ? LIMIT 5');
$stmt->execute(array($search_input));
$results = $stmt->fetchAll();

if($results == null){
    echo "No results found";
    exit();
}

$html = "<ul id='suggestions'>";
foreach ($results as $result) {
    $html .= "<li> <button name='tag' value=" .$result['hashtag']. ">". $result["hashtag"] ."</button></li>";
}
$html .= '</ul>';

echo $html;
?>