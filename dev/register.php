<?php 
//libxml_use_internal_errors(true);
require_once('classes/register.php');
$register = new register();
//print_r($_SESSION);


$warn = '<span id="msg" style="color:#FF0000;font-size:17px;font-weight:bold">-----<br>';
$warnEnd = '<br>-----</span>';
$msg='';
if($_SESSION['user']['role'] !== 'admin'){
	$_SESSION["user"] = array('name' => $_SESSION['user']['name'], 'role' => $_SESSION['user']['role']);
	header('Location: http://customsigncenter.com/calendar/dev/login.php');	
	
} 
if( isset($_POST["submitregistration"]) ) {	
	if($register->connected == TRUE){
		$register->save();
	}

	
	

		

		if ( $register->status === true ) {	//returns true if user account was created
			  $msg = 'Successfully Created a User Account with Username ' . $register->uUsername . ' and Password ' . $register->uPassword . '.<br>Login <a href="login.php">Here</a>';
			 // header('Location: index.php');		
			//echo 'The header would redirect me to index.';
		}
		else { // output the message from the register class on failure

			$msg = $register->userMessage;
		}

	}
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>REGISTER A USER ACCOUNT FOR WIP CALENDAR</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/micropage.css" />
</head>
<body>
<div id="page">
    <!-- [banner] -->
    <header id="banner">
        <hgroup>
		   <h1 class="T_RexTitle">Register</h1>
        </hgroup>        
    </header>
    <!-- [content] -->
    <section id="content">
       <?php if( $msg !== '' ) { 
			echo $warn.$msg.$warnEnd.'<br><br>Login <a href="login.php">Here</a>';			
		} ?>
        <div id="miniform">
        <form id="login" method="post">
            <label for="username">Username (must be unique in database):</label>
            <input id="username" name="username" type="text" required><br><br>
            <label for="password">Password (use letters and/or numbers):</label>
            <input id="password" name="password" type="password" required> <br><br>
             <label for="email">Email:</label><br>
            <input id="email" name="email" type="text" required>  
            <input type="hidden" name="role" value="user" >                
            <br><br>
            <input type="submit" name="submitregistration" value="Register">
        </form>
	    </div>
    </section>
    <!-- [/content] -->
    
     <footer id="footer">
        <details>
            <p>Copyright - <?php echo date('Y',strtotime('today')); ?></p>
          
            <p>Custom Sign Center, INC. All Rights Reserved.</p>
        </details>
    </footer>
</div>
<!-- [/page] -->
</body>
</html>