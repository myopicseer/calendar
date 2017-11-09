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
	//var_dump($auth->logged);
	
	if ( $auth->logged['status'] == 'AUTH' ) {
		
		// this user has been authenticated	
		
		// admin?
		if( $auth->logged['role'] === 'admin' || $auth->logged['role'] === 'mgr' ) {		
		
			$sesID ='';
			$_SESSION["user"]["status"] = $auth->logged['status'];
			$_SESSION["user"]["name"] = $auth->logged['user'];
			$_SESSION["user"]["role"] = $auth->logged['role'];  
			$_SESSION["user"]["userId"] = $auth->logged['userId'];
			$_SESSION["user"]["company"] = $auth->logged['company'];
			if( session_id() ) {	
				$sesID = session_id();
				$sesID = '&sid='.$sesID;					
			}
			
			//var_dump($_SESSION["user"]);
			
			
			//TODO: ya don't need to pass url parameters since these are 
			//stored in the session, accessible to the redirected page.
			header( 'Location: index.php?user='.$auth->uUsername.$sesID.'&editor='.$auth->access_tokenResults["username"].'&authtime='.$auth->access_tokenResults["logginTime"] );
			
		} elseif(!empty($auth->logged['role']) && $auth->logged['role'] !== 'guest') {
			// authenticated as a non-admin / non-mgr or NULL role.
			
			if( session_id() ) {	
				$sesID = session_id();
				$sesID = '&sid='.$sesID;					
			}	
			$_SESSION["user"]["status"] = $auth->logged['status'];
			$_SESSION["user"]["name"] = $auth->logged['user'];
			$_SESSION["user"]["role"] = $auth->logged['role'];  
			$_SESSION["user"]["userId"] = $auth->logged['userId'];
			$_SESSION["user"]["company"] = $auth->logged['company'];		
			$_SESSION['user']['loggedintime'] =  $auth->logged['recent_login'];

			
			$u = ( empty($auth->adminNameWithToken) ) ?  'Someone else': strtoupper($auth->adminNameWithToken);

			$error .= "<span style=\"font-size: 17px\">Welcome " . $auth->logged['user'] . "</span><br><br>";
			$error .= $u." is currently logged in as the Calendar Administrator.<br>";
			$error .= "You've VIEW-ONLY-ACCESS to the <a href=\"view.php?user=".$auth->logged['user'].$sesID.
				"&editor=".$auth->access_tokenResults['username']."&authtime=".$auth->access_tokenResults['logginTime']."\">Calendar</a>";
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

} //end if post is login action
elseif( isset($_POST['loggedOutUser']) ) {
	
	//release the admin token if logged out user is admin or mgr
	if( $_SESSION['user']['role'] === 'admin' || $_SESSION['user']['role'] === 'mgr') {
		//echo 'release token called';
		
			
		if( $auth->unleaseToken( $_SESSION['user']['userId'] ) === FALSE ){

			$error .= '[ Unleasing the Admin Token failed. ]<br><br>';

		}
		
	} 
		//just set loggedin status to 0 (false) in tbl users:
	$auth->updateLoggedInStatus( $_SESSION['user']['userId'], false );
		
	// delete session from database; see session class.
	session_destroy();
	
	$error .= $_POST['loggedOutUser'] ." successfully logged out.";
	
	
//active session id...	
} 
	
	
	
	if( session_id() !== NULL ) {
	
	//this is an admin or mgr
	if( @$_SESSION['user']['role'] === 'admin' || @$_SESSION['user']['role'] === 'mgr' ) {
		
		
		
			//var_dump($_SESSION["user"]);
			//{ ["name"]=> string(10) "outmanager" ["role"]=> string(3) "mgr" ["userId"]=> int(60) ["company"]=> int(3) } 
			
			
		

		
		
		$error .= "You are already Logged in as <br>" . $_SESSION['user']['name'] . " with ".strtoupper($_SESSION['user']['role'])." Editing Rights.<br><br>";
		$error .="Visit the Editable Version of the <a href=\"index.php?user=".$auth->logged['user'].$sesID.
				"&editor=".$auth->access_tokenResults['username']."&authtime=".$auth->access_tokenResults['logginTime']."\"> WIP Calendar</a><br><br>";
		
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
		
		$u = ( empty($auth->adminNameWithToken) ? 'Someone else ' : strtoupper($auth->adminNameWithToken));
		
		$error .= "<span style=\"font-size: 17px\">Welcome " . $auth->logged['user'] . "</span><br><br>";
		(!empty($u)) ? $u = '['.$u.']' : $u = '[?]';
		$error .= "Someone Else ".$u." is Currently Logged in as the Calendar Admin.<br>";
		$error .= "You've <strong>VIEW-ONLY-ACCESS</strong> to the <a href=\"view.php?user=".$auth->logged['user'].$sesID.
				"&editor=".$auth->access_tokenResults['username']."&authtime=".$auth->access_tokenResults['logginTime']."\">Calendar</a>";
		//logout option:
		$error .="<br><br>or<br><br><form action=\"login.php\" method=\"POST\" >
		<input type=\"hidden\" value=\"".$auth->logged['user']."\" name=\"loggedOutUser\" />
		<input type=\"submit\" value=\"logout\" name=\"logout\" />
		</form>";
		
	}
	//echo session_id();
	
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
	   <!-- the index.php can redirect to login with a message:-->
	   <?php if($_GET['notice'])
			{
				echo "<br/>".$_GET['notice']."<br/>";
			}
		?>
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
	<script type="text/javascript">
		function BrowserDetection() {
			//Check if browser is IE
			if (navigator.userAgent.indexOf('MSIE') > -1) {
				// insert conditional IE code here
				alert("Internet Explorer is NOT recommended for the Calendar. Install and Use FireFox or Chrome Instead.");
			}/*
			//Check if browser is Chrome
			else if (navigator.userAgent.search("Chrome") & gt; = 0) {
				// insert conditional Chrome code here
			}
			//Check if browser is Firefox 
			else if (navigator.userAgent.search("Firefox") & gt; = 0) {
				// insert conditional Firefox Code here
			}
			//Check if browser is Safari
			else if (navigator.userAgent.search("Safari") & gt; = 0 & amp; & amp; navigator.userAgent.search("Chrome") & lt; 0) {
				// insert conditional Safari code here
			}
			//Check if browser is Opera
			else if (navigator.userAgent.search("Opera") & gt; = 0) {
				// insert conditional Opera code here
			}*/
		}
	</script>
</body>
</html>
