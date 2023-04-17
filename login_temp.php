<!DOCTYPE html>
<?php
session_start();
?>
<html>
<head>
    <title>Log In</title>
    <meta charset="utf-8">
</head>
<body>
    <header>
        <div class = "main-header">
            <div class = "topbar-container">
                <div class = "topbar_logo">
                   
                </div>
            </div>
        </div>
    <div class="wrapper">
        <div class="title">
            <h2>Iniciar sessão</h2>
        </div>
        <hr>
        <div class = "form">
        <form action="login_query.php" method="post">
            <div class = "input_field">
                <label for="username"><b>Username</b></label>
                <input class = "center-block" type="text" name="username" spellcheck="false" autocomplete="off" autocorrect="off" autocapitalize="off" required>
            </div>   

            <div class = "input_field">
                <label for="password"><b>Password</b></label>
                <input class = "center-block" type="password" name="password" spellcheck="false" autocomplete="off" autocorrect="off" autocapitalize="off" required>
            </div>
            <?php
					//checking if the session 'error' is set. Erro session is the message if the 'Username' and 'Password' is not valid.
					if(ISSET($_SESSION['error'])){
				?>
				<!-- Display Login Error message -->
					<div class="alert-danger"><?php echo $_SESSION['error']?></div>
				<?php
					//Unsetting the 'error' session after displaying the message. 
					unset($_SESSION['error']);
					}
				?>
            <div class = "input_field_create">
                <button type="submit" name ="login"><b>Iniciar sessão</b></button>
            </div>
        </form>
        </div>
        <hr>
        <div class = "input_field_noacc">
        <p>Não tem uma conta?<br> Crie aqui! </p>
            <button type="submit"><a href = "registration.php"><b>Criar Conta</b></a></button>
        </div>    
    </div>
</body>
</html>