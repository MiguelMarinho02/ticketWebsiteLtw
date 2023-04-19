<!DOCTYPE html>
<?php
session_start();
require_once('connection.php');
$db = getDatabaseConnection();

$stmt = $db->prepare('SELECT * FROM department');
$stmt->execute();
$departments = $stmt->fetchAll();
?>
<html>
<head>
    <title>Create ticket</title>
    <meta charset="utf-8">
</head>
<body>
    <header>
        <div class="title">
            <h2>Create a ticket</h2>
        </div>
        <form action="tickets_model.php" method="POST">
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
                echo "<option value='$none_option'>$none_option</option>";
                foreach($departments as $department){
                    $d = $department['name'];
                    echo "<option value='$d'>$d</option>";
                }
                echo "</select>";
            ?>
            <br>       
            <input type="submit" value="Submit">
            <?php
                if(!$valid_ticket):
            ?>
            <em>Invalid ticket!</em>
            <?php endif; ?>
        </form>

    </header>
</body>
</html>