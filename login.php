<?php 

//require_once('classes/session.php');  security.php includes session.php
require_once('classes/security.php');
//session_start();
//$_SESSION['counter']++;
//echo "You have visited this page $_SESSION[counter] times.";
$auth = new userAuthenticate;
$u;
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
	
	
//active session id...	
} elseif( session_id() !== NULL ) {
	
	//this is an admin or mgr
	if( @$_SESSION['user']['role'] === 'admin' || @$_SESSION['user']['role'] === 'mgr' ) {
		
		$error .= "You are already Logged in as <br>" . $auth->logged['user'] . " with ".strtoupper($_SESSION['user']['role'])." Editing Rights.<br><br>";
		$error .="Visit the Editable Version of the <a href=\"index.php\" > WIP Calendar</a><br><br>";
		
		//logout option:
		$error .="<br><form action=\"login.php\" method=\"POST\" >
		<input type=\"hidden\" value=\"".$auth->logged['user']."\" name=\"loggedOutUser\" />
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
		
		$u = ( empty($auth->adminNameWithToken !== '' )) ? strtoupper($auth->adminNameWithToken) : 'Someone else ';
		
		$error .= "<span style=\"font-size: 17px\">Welcome " . $auth->logged['user'] . "</span><br><br>";
		(!empty($u)) ? $u = '['.$u.']' : $u = '[?]';
		$error .= "Someone Else ".$u." is Currently Logged in as the Calendar Admin.<br>";
		$error .= "You've <strong>VIEW-ONLY-ACCESS</strong> to the <a href=\"/calendar/view.php\">Calendar</a>";
		//logout option:
		$error .="<br><br>or<br><br><form action=\"login.php\" method=\"POST\" >
		<input type=\"hidden\" value=\"".$auth->logged['user']."\" name=\"loggedOutUser\" />
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
		'userId' => $result['id'], 
		'company' => $result['company']) (ALL, 0, 1, etc.)
	]
	*/
	
	
	if ( $auth->logged['status'] == 'AUTH' ) {
		
		// this user has been authenticated	
		
		// admin?
		if( $auth->logged['role'] === 'admin' || $auth->logged['role'] === 'mgr' ) {		
		
			$sesID ='';
			$_SESSION["user"]["name"] = $auth->logged['user'];
			$_SESSION["user"]["role"] = $auth->logged['role'];  
			$_SESSION["user"]["userId"] = $auth->logged['userId'];
			$_SESSION["user"]["company"] = $auth->logged['company'];
			if( session_id() ) {	
				$sesID = session_id();
				$sesID = '&sid='.$sesID;					
			}
			//TODO: ya don't need to pass url parameters since these are 
			//stored in the session, accessible to the redirected page.
			header( 'Location: http://customsigncenter.com/calendar/index.php?user='.$auth->uUsername.$sesID );
			
		} else {
			// authenticated as a non-admin / non-mgr
			
			
		if( session_id() ) {	
				$sesID = session_id();
				$sesID = '&sid='.$sesID;					
			}	
			
		$_SESSION["user"]["name"] = $auth->logged['user'];
			$_SESSION["user"]["role"] = $auth->logged['role'];  
			$_SESSION["user"]["userId"] = $auth->logged['userId'];
			$_SESSION["user"]["company"] = $auth->logged['company'];	
			
		( empty($auth->adminNameWithToken) ) ?  'Someone else': strtoupper($auth->adminNameWithToken);
		
		$error .= "<span style=\"font-size: 17px\">Welcome " . $auth->logged['user'] . "</span><br><br>";
		$error .= $u." is currently logged in as the Calendar Administrator.<br>";
		$error .= "You've VIEW-ONLY-ACCESS to the <a href=\"/calendar/view.php?user=".$auth->logged['user'].$sesID."\">Calendar</a>";
			$error .="<br><br>or Logout<br><br><form action=\"login.php\" method=\"POST\" >
		<input type=\"hidden\" value=\"".$auth->logged['user']."\" name=\"loggedOutUser\" />
		<input type=\"submit\" value=\"logout\" name=\"logout\" />
		</form>";	
			
		}
		
	}
	elseif($auth->logged['status'] == 'USRNfail') {
		
		//header('Location: login.php');
		$error .= "Record not Found for " . $auth->logged['user']. ".";
	} elseif($auth->logged['status'] == 'PSWDfail'){
		$error .= "Password Failed with User: " . $auth->logged['user']. ".";
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
