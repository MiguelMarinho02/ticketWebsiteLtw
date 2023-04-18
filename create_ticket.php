<!DOCTYPE html>
<?php
session_start();
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
        <form action="index.php" method="post">
            <input type="submit" value="Submit">
        </form> 

    </header>
</body>
</html>