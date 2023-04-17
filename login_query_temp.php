<?php
	//starting the session
	session_start();
	//including the database connection
	require_once 'connection.php';
 
	if(ISSET($_POST['login'])){
		// Setting variables
		$username = $_POST['username'];
		$password = $_POST['password'];

		$username = preg_replace ("/[^a-zA-Z\s]/", '', $username);

 
		// Select Query for counting the row that has the same value of the given username and password. This query is for checking if the access is valid or not.
		$query = "SELECT idUser,username,email,name,password,type FROM User WHERE username = ?";
		$stmt = $db->prepare($query);
		$stmt->execute([$username]);
		$row = $stmt->fetch();
		$count = $row['idUser'];
		if($count > 0){
			$validPassword = password_verify($password,$row['password']);
			if ($validPassword){
				$_SESSION['idUser'] = $row['idUser'];
				$_SESSION['username'] = $row['username'];
				$_SESSION['email'] = $row['email'];
				$_SESSION['name'] = $row['name'];
				$_SESSION['type'] = $row['type'];
				header('location:index.php');
			}
			else {
				$_SESSION['error'] = "Invalid password";
				header('location:login.php');
			}
		}
		else{
			$_SESSION['error'] = "Invalid username";
			header('location:login.php');
		}
	}
?>