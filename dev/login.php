<?php 
error_reporting(E_ALL);
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
	
	//release the admin token if logged out user is admin or mgr
	if( $_SESSION['user']['role'] === 'admin' || $_SESSION['user']['role'] === 'mgr') {
		//echo 'release token called';
		$releaseToken = new userAuthenticate;		
			
		if( $releaseToken->unleaseToken( $_SESSION['user']['userId'], $_SESSION['user']['company'] ) === FALSE ){

			$error .= '[ Unleasing the Admin Token failed. ]<br><br>';

		}
		
	}
	
	session_unset(); 
	$error .= $_POST['loggedOutUser'] ." successfully logged out.";
} elseif( session_id() !== NULL ) {
	if( @$_SESSION['user']['role'] === 'admin' || @$_SESSION['user']['role'] === 'mgr' ) {
		
		$error .= "You are already Logged in as <br>" . $_SESSION['user']['name'] . " with ".strtoupper($_SESSION['user']['role'])." Editing Rights.<br><br>";
		$error .="Visit the Editable Version of the <a href=\"index.php\" > WIP Calendar</a><br><br>";
		
		//logout option:
		$error .="<br><form action=\"login.php\" method=\"POST\" >
		<input type=\"hidden\" value=\"".$_SESSION['user']['name']."\" name=\"loggedOutUser\" />
		<input type=\"submit\" value=\"logout\" name=\"logout\" />
		</form><br>";
		
		//only admins can register users
		if( @$_SESSION['user']['role'] === 'admin'){
			$error .="....or<br><br>
			<form action=\"register.php\" method=\"POST\" >
			<input type=\"hidden\" value=\"".$_SESSION['user']['role']."\" name=\"role\" />
			<input type=\"submit\" value=\"Register a User\" name=\"logout\" />
			</form>";
		}
		
	} elseif( isset( $_SESSION['user']['role'] ) && @$_SESSION['user']['role'] === 'admin' ) {
		
		$u = ( $auth->adminNameWithToken !== '' ? strtoupper($auth->adminNameWithToken) : $u = 'Someone else');
		
		$error .= "<span style=\"font-size: 17px\">Welcome " . $_SESSION['user']['name'] . "</span><br><br>";
		//(!empty($u)) ? $u = '['.$u.']' : $u = '[?]';
		$error .= $u." is Currently Logged in as the Calendar Admin.<br>";
		$error .= "You've <strong>VIEW-ONLY-ACCESS</strong> to the <a href=\"/calendar/dev/view.php\">Calendar</a>";
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
		if( $auth->logged['role'] === 'admin' || $auth->logged['role'] === 'mgr' ) {		
		
			$sesID ='';
			$_SESSION["user"] = array( 'name'=>$auth->logged['user'], 'role' => $auth->logged['role'],  'userId' => $auth->logged['userId'],
								'company' => $auth->logged['company']);
			if( session_id() ) {	
				$sesID = session_id();
				$sesID = '&sid='.$sesID;					
			}
			//TODO: ya don't need to pass url parameters since these are 
			//stored in the session, accessible to the redirected page.
			header( 'Location: http://customsigncenter.com/calendar/dev/index.php?user='.$auth->uUsername.$sesID );
			
		} else {
			// authenticated as a non-admin 
			
		$u = ( $auth->adminNameWithToken !== '' ? strtoupper($auth->adminNameWithToken) : 'Someone else');
		
		$error .= "<span style=\"font-size: 17px\">Welcome " . $_SESSION['user']['name'] . "</span><br><br>";
		$error .= $u." is currently logged in as the Calendar Admininstrator.<br>";
		$error .= "You've VIEW-ONLY-ACCESS to the <a href=\"/calendar/dev/view.php?user=".$auth->uUsername.$sesID."\">Calendar</a>";
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
