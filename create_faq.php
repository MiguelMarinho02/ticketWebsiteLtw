<?php
declare(strict_types = 1);
require_once('connection.php');
require_once('functions.php');
session_start();
$db = getDatabaseConnection();

if (!isset($_SESSION["user_id"])){
 header("Location: login.php");
}

$user = searchUser($_SESSION["user_id"]);

if($user["role"] == "client"){
 header("Location: index.php");
}

$valid_input = true;
if ($_SERVER["REQUEST_METHOD"] === "POST"){
    if(!empty($_POST["question"]) and !empty($_POST["answer"])){
        $stmt = $db->prepare('INSERT INTO faq (question,answer) VALUES (?,?)');
        $stmt->execute(array($_POST["question"],$_POST["answer"]));
        header("Location: faqs.php");
    }
}
$valid_input = false;

?>

<!DOCTYPE html>
<html>
<head>
    <title>Create FAQ</title>
    <meta charset="utf-8">
</head>
<body>
    <header>
        <div class="back">
            <button type="submit"><a href="faqs.php">Back</a></button>
        </div>
        <div class="title">
            <h2>Create a ticket</h2>
        </div>

        <?php if(!$valid_input):?>
        <em>Please fill all spaces</em>
        <?php endif; ?>

        <form method="POST">
            <div>
                <label for="question">Question</label>
                <input type="text" id= "question" name = "question">
            </div>

            <div>
                <label for="answer">Answer</label>
                <input type="text" id= "answer" name = "answer">
            </div>

            <button>Create</button>
        </form>

    </header>
</body>
</html>