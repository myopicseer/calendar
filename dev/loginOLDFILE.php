<?php 
require_once('classes/security.php');
if( !empty($_POST["username"]) && !empty($_POST["password"]) ) {	
	$user = new userAuthenticate();
	if ( is_array($user->logged) && $user->logged['status'] == 'true' ) {		
		  header('Location: index.php');		
		//echo 'The header would redirect me to index.';
	}
	else {
		//header('Location: login.php');
		echo 'The header would keep me on the login page.';
	}

} else { 
	

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>LOGIN TO WIP CALENDAR</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<div id="page">
    <!-- [banner] -->
    <header id="banner">
        <hgroup>
            <h1>Login</h1>
        </hgroup>        
    </header>
    <!-- [content] -->
    <section id="content">
        <form id="login" method="post">
            <label for="username">Username:</label>
            <input id="username" name="username" type="text" required><br>
            <label for="password">Password:</label>
            <input id="password" name="password" type="password" required>                    
            <br />
            <input type="submit" value="Login">
        </form>
    </section>
    <!-- [/content] -->
    
    <footer id="footer">
        <details>
            <summary>Copyright 2017 - <?php echo date('Y',strtotime('today')); ?></summary>
            <p>Custom Sign Center, INC. All Rights Reserved.</p>
        </details>
    </footer>
</div>
<!-- [/page] -->
</body>
</html>
<?php }

?>