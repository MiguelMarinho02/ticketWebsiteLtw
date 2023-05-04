<!DOCTYPE html>
<?php
session_start();
require_once('connection.php');
if (!isset($_SESSION["user_id"])){
    header("Location: login.php");
}
$db = getDatabaseConnection();
$valid_ticket = 2;

if ($_SERVER["REQUEST_METHOD"] === "POST"){
    $valid_ticket = 1;
    if(!empty($_POST['subject'] && !empty($_POST['description']))){
        $uniqueid = uniqid();
        
        $client_id = $_SESSION["user_id"];
        $agent_id = null;
        $created_at = date("F j, Y, g:i a");
        $updated_at = date("F j, Y, g:i a");
    
        $stmt = $db->prepare('INSERT INTO tickets (department_id, client_id, agent_id, subject, description, status, priority, created_at, updated_at) VALUES (?,?,?,?,?, "open",?,?,?)');
        $stmt->execute(array($_POST["department"],$client_id,$agent_id, $_POST["subject"], $_POST["description"], $_POST["priority"], $created_at, $updated_at));
    }
    else{
        $valid_ticket = 0;
    }
}

$stmt = $db->prepare('SELECT * FROM department');
$stmt->execute();
$departments = $stmt->fetchAll();
?>
<html>
<link rel="stylesheet" href="css/form.css">
<head>
    <title>Create ticket</title>
    <meta charset="utf-8">
</head>
<body>
    <header>
        <div class="back">
            <button type="submit"><a href="tickets.php">Back</a></button>
        </div>
        <div class="title">
            <h2>Create a ticket</h2>
        </div>
        <form method="POST">
            <label for="subject">Subject</label><br><br>
            <textarea id="subject" name="subject" rows="1" cols="50"></textarea>
            <br><br>

            <label for="description">Description</label><br><br>
            <textarea id="description" name="description" rows="6" cols="50"></textarea>  
            <br>
            <label for="priority">Priority</label>
            <select name="priority">
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
            </select>
            <br>
            <label for="department">Department</label>
            <?php
                echo "<select name='department' class='department'>";
                $none_option = "---";
                echo "<option value='null'>$none_option</option>";
                foreach($departments as $department){
                    $d_name = $department['name'];
                    $department_id = $department['id'];
                    echo "<option value='$department_id'>$d_name</option>";
                }
                echo "</select>";
            ?>
            <br>       
            <input type="submit" value="Submit">
            <?php
                if($valid_ticket == 0):
            ?>
            <p><em>Invalid ticket!</em></p>
            <?php endif; ?>
            <?php
                if($valid_ticket == 1):
            ?>
            <p><em>Ticket created successfully!</p></em>
            <?php endif; ?>
        </form>

    </header>
</body>
</html>