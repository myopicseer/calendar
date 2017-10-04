<?php 

//require_once('classes/session.php');  security.php includes session.php
require_once('classes/security.php');
//session_start();
//$_SESSION['counter']++;
//echo "You have visited this page $_SESSION[counter] times.";
$auth = new userAuthenticate;

$warn = '<span id="msg" style="color:#FF0000;font-size:17px;font-weight:bold">-----<br>';
$warnEnd = '<br>-----</span>';
$error='';

if( isset($_POST['loggedOutUser']) ) {
	
	//release the admin token if logged out user is admin
	if( $_SESSION['user']['role'] === 'admin' ) {
		//echo 'release token called';
		$releaseToken = new userAuthenticate;		
			
		if( $releaseToken->unleaseToken( $_SESSION['user']['userId'] ) === FALSE ){

			$error .= '[ Unleasing the Admin Token failed. ]<br><br>';

		}
		
	}
	
	session_unset(); 
	$error .= $_POST['loggedOutUser'] ." successfully logged out.";
} elseif( session_id() !== NULL ) {
	if( @$_SESSION['user']['role'] === 'admin' ) {
		
		$error .= "You are already Logged in as <br>" . $_SESSION['user']['name'] . " with full Admin Rights.<br><br>";
		$error .="View &amp; Manage the <a href=\"index.php?user=". $_SESSION['user']['name'] ."\"> WIP Calendar</a><br><br>";
		
		//logout option:
		$error .="<br><form action=\"login.php\" method=\"POST\" >
		<input type=\"hidden\" value=\"".$_SESSION['user']['name']."\" name=\"loggedOutUser\" />
		<input type=\"submit\" value=\"logout\" name=\"logout\" />
		</form><br>....or<br><br>";
		
		$error .="<form action=\"register.php\" method=\"POST\" >
		<input type=\"hidden\" value=\"".$_SESSION['user']['role']."\" name=\"role\" />
		<input type=\"submit\" value=\"Register a User\" name=\"logout\" />
	     </form>";
		
	} elseif( isset( $_SESSION['user']['role'] ) ) {
		
		( $auth->adminNameWithToken !== '' ) ? 
				    $u = strtoupper($auth->adminNameWithToken) : 
				    $u = 'Someone else';
		
		$error .= "<span style=\"font-size: 17px\">Welcome " . $_SESSION['user']['name'] . "</span><br><br>";
		$error .= $u." is currently logged in as the Calendar Admin.<br>";
		$error .= "You've VIEW-ONLY-ACCESS to the <a href=\"/calendar/view.php\">Calendar</a>";
		//logout option:
		$error .="<br><br>or<br><br><form action=\"login.php\" method=\"POST\" >
		<input type=\"hidden\" value=\"".$_SESSION['user']['name']."\" name=\"loggedOutUser\" />
		<input type=\"submit\" value=\"logout\" name=\"logout\" />
		</form>";
		
	}
	//echo session_id();
	
}

if( isset( $_POST['username'] ) ) {
	
	//$auth = new userAuthenticate();
	
	/*returns array logged [
		'status' => (str) (AUTH, PSWDfail, USRNfail), 
		'user' => (str) username found, or on USRNfail, the POSTed username
		'role' => (str) (guest, admin, user, etc)	
	]
	*/
	
	
	if ( $auth->logged['status'] == 'AUTH' ) {
		
		// this user has been authenticated	
		
		// admin?
		if( $auth->logged['role'] == 'admin' ) {		
		
			$sesID ='';
			$_SESSION["user"] = array( 'name'=>$auth->logged['user'], 'role' => $auth->logged['role'],  'userId' => $auth->logged['userId']);
			if( session_id() ) {	
				$sesID = session_id();
				$sesID = '&sid='.$sesID;					
			}

			header( 'Location: http://customsigncenter.com/calendar/dev/index.php?user='.$auth->uUsername.$sesID );
			
		} else {
			// authenticated as a non-admin 
			
		( $auth->adminNameWithToken !== '' ) ? 
				    $u = strtoupper($auth->adminNameWithToken) : 
				    $u = 'Someone else';
		
		$error .= "<span style=\"font-size: 17px\">Welcome " . $_SESSION['user']['name'] . "</span><br><br>";
		$error .= $u." is currently logged in as the Calendar Admin.<br>";
		$error .= "You've VIEW-ONLY-ACCESS to the <a href=\"/calendar/view.php\">Calendar</a>";
			$error .="<br><br>or Logout<br><br><form action=\"login.php\" method=\"POST\" >
		<input type=\"hidden\" value=\"".$_SESSION['user']['name']."\" name=\"loggedOutUser\" />
		<input type=\"submit\" value=\"logout\" name=\"logout\" />
		</form>";	
			
		}
		
	}
	elseif($auth->logged['status'] == 'USRNfail') {
		
		//header('Location: login.php');
		$error .= "Record not Found for " . $auth->uUsername . ".";
	} elseif($auth->logged['status'] == 'PSWDfail'){
		$error .= "Password Failed with User: " . $auth->uUsername . ".";
	}

} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>LOGIN TO WIP CALENDAR</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/micropage.css" />
</head>
<body>
<div id="page">
    <!-- [banner] -->
    <header id="banner">
        <hgroup>
            <h1 class="T_RexTitle">Login</h1>
        </hgroup>        
    </header>
    <!-- [content] -->
    <section id="content">
       <?php if($error !== '') echo $warn.$error.$warnEnd; ?>
       <div id="miniform">
		   <form id="login" method="post">
			  <label for="username">Username:</label>
			  <input id="username" name="username" type="text" required><br><br>
			  <label for="password">Password:</label>
			  <input id="password" name="password" type="password" required>                    
			  <br /><br>
			   <div  class="center"> <input type="submit" name="submit" value="Login"> </div>
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
