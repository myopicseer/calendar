<?php
//include('../../../classes/dbConnPDO.php');
error_reporting(E_ALL);
include('session.php');
class userAuthenticate extends Session {	
	public $pdo;
	public $uUsername;
	public $uPassword;
	public $uPasswordEncrypted;	
	public $connected; // bool status of db connection
	public $logged;
	public $adminNameWithToken;
	public $userCompany;
	
	public function __construct(){
		
		parent::__construct();
		
		//prevent null of name:
		//$this->adminNameWithToken = "[ None ]";
		
		//$cryptOpts = array('cost' => 12,);
		$this->uUsername = $_POST['username'];
		$this->uPassword = $_POST['password'];
		//only need password_hash when saving a password to the db.
		
		//parent::__construct(); //make parent->db (pdo connection) available.
		
		$this->uPasswordEncrypted = password_hash ( $_POST['password'], PASSWORD_BCRYPT );
		// connect to the database		
		if(	$this->pdo = parent::dbOpen('custo299_wipcalendar')){//open connection to wipcalendar db.
			$this->connected = TRUE;
			$this->authenticate();
		} else {
			$this->connected = FALSE;
		}
		
	}
	// try user-provided credentials against db records.  return authentication status as bool.
	private function authenticate( ){		
			// Use Prepared Statement, employ PDO placeholders (?) for Query variables to guard against sql injections:
			$statement = $this->pdo->prepare('SELECT `id`, `username`, `role`, `password`, `company` FROM users WHERE `username` = :username ' );
			//execute the query, passing in the variables.
			
			$statement->execute(['username' => $this->uUsername ]);
			$result = $statement->fetch(PDO::FETCH_ASSOC);
		     //avoid member func call on non-obj when results are not returned.			
			
			if( $statement->rowCount() === 1 ) {	
				//returns TRUE if a match, FALSE otherwise.
				$validate = password_verify($this->uPassword, $result['password'] ); //returns bool FALSE / TRUE
				
				if( $validate ){
					
					$id = $result['id'];
					
					if( $result['role'] === 'admin' || $result['role'] === 'mgr' ) {
						//echo 'User is an '.$result['role'];
						//if( $this->userCompany !== "Developer" ) {								
							$this->userCompany = ( $result['company'] === 'ALL' ? 0 : $result['company'] );						
							
							//do not grab the admin token from the db if the token is occupied by someone else
							//returns user, admin, or mgr
							$reAssignedRole = $this->setAdminToken( $id, $result['role'] ); // e.g. 20, admin
							
						//} else {							
							if($result['role'] === 'admin'){
							//this is a developer logged into the /dev directory
								$reAssignedRole = 'admin';
							}
							if($result['role'] === 'mgr'){
								$reAssignedRole = 'mgr';
							}				
						} else {						
							$reAssignedRole = 'user';
							$this->userCompany = $result['company'];						
						}					
					$this->logged = array( 'status'=>'AUTH', 'user' => $result['username'], 'role' => $reAssignedRole, 'userId' => $result['id'], 'company' => $result['company']);	
					
						
				
				}else{
					$this->logged = array( 'status'=>'PSWDfail', 'user' => $result['username'], 'role' => $result['role'], 'userId' => $result['id'], 'company' => $result['company'] );
				}			
			} else {
				$this->logged = array( 'status'=>'USRNfail', 'user' => $this->uUsername, 'role' => 'guest' );
			}	
				$this->pdo = NULL; //close the db connection
		
	}// authenticate()
	
	// setAdminToken only called if authenticated user has admin privilege in user table.
	// set active_token for admin to checked_out = 1, recent_login = linux time, id_user = this user $id, id_session to session id of user.
	// if checked_out == 0, or if == 1 but recent_login is older than 1 hour
	// return role as admin if token set for user of $id; return role as 'user' if token actively leased out to another.
	private function setAdminToken($id, $role) //20, admin
	{		
		$updateToken = 'UPDATE active_token ';
		$updateTokenColumns = ' SET checked_out = :checked_out';
		$updateTokenFilter = ' WHERE coid_token = :coid AND id = :id';
		//UPDATE active_token SET checked_out = :checked_out [,POSSIBLE MORE FIELDS TO SET] WHERE id = :id AND coid_token = :coid;		
		$null = NULL;
		$one = 1;
		$zero = 0;		
		$unixSecs = time();
		
		// examine the access_level (mgr or admin) row from active_token tbl		
		$selectToken = $this->pdo->prepare('SELECT * FROM active_token WHERE access_level = "admin" AND coid_token = :coid');
		$selectToken->bindParam(':coid', $this->userCompany, PDO::PARAM_INT);
		
		if( $selectToken->execute() ){
			
			$result = $selectToken->fetch(PDO::FETCH_ASSOC);		
			
			if( $result['checked_out'] === 0 ){ //It is Available to Grab for the Authenticated User.		
				
				//echo "Checkout is 0";
				
				//checked_out is 0; proceed with checking in this user as an admin or mgr role:
				// prepare query on update of active_token
				$updateTokenColumns .= ', recent_login = :recent, id_user = :id_user';
		
				$queryToken = $updateToken.$updateTokenColumns.$updateTokenFilter;
				//echo $queryToken; 
				//outputs: UPDATE active_token SET checked_out = :checked_out, recent_login = :recent, id_user = :id_user WHERE coid_token = :coid AND id = :id
				
				
				
				
				// query should equal = 'UPDATE active_token SET checked_out = :checked_out, recent_login = :recent, 
				// id_user = :id_user, id_session = :id_session WHERE id = :id';
				$updateStmt = $this->pdo->prepare($queryToken);				
				
				//session not authenticated yet, so set to NULL;
				$updateStmt->bindParam(':checked_out', $one);
				$updateStmt->bindParam(':recent', $unixSecs);
				$updateStmt->bindParam(':id_user', $id);
				//$updateToken->bindParam(':id_session', $null);
				$updateStmt->bindParam(':id', $result['id']);				
				$updateStmt->bindParam(':coid', $this->userCompany, PDO::PARAM_INT);
				
					if($updateStmt->execute()) {	
						/*
						echo"<br/>user_id is " . $id;
						echo"<br/>id is " . $result['id'];
						echo"<br/>coid is " . $this->userCompany;
						echo"<br/>checkedout is " . $one;
						echo"<br/>recent is " . $unixSecs;
						
						user_id is 22
						id is 3
						coid is 0
						checkedout is 1
						recent is 1507049172
						*/
						
						
						
						//echo "Updated the active_token."; BUT NO IT DID NOT!
						
						$role = ($role === 'admin' ? 'admin' : 'mgr' );				
						
					} else {		
						echo "Could not execute the update.";
						$role = 'user';
						$this->adminNameWithToken = "Anonymous";
						
					}
				return $role;
				
			} //end no one had it checked out.	
		
			
			elseif($result['checked_out'] === (integer)1)  {  // checked_out = 1;
				// checked_out is set to 1, i.e., true; 
				// if recent_login is > 30 mins ago, still set this user as the admin.
				// or if the current user's $id == the id in user tbl, same person/no conflict possible.
				
				
				//echo "checked out is ".$result['checked_out']; 
				
				
				if( $result['id_user'] === $id ){
					
					//echo "id_user " . $result['id_user'] ." is the same as the id " . $id;
					
					
					if( (( time() / 60 ) - ($result['recent_login'] / 60) ) > 30 ){
					
						// recent_login value is greater than 30 mins ago [expire it]
						// set token to this user as new admin

						$updateTokenColumns .= ', recent_login = :recent, id_user = :id_user';
						$query = $updateToken.$updateTokenColumns.$updateTokenFilter;
						
						$updateStmt = $this->pdo->prepare($query);
						$updateStmt->bindParam(':checked_out', $one);
						$updateStmt->bindParam(':recent', $unixSecs);
						$updateStmt->bindParam(':id_user', $id, PDO::PARAM_INT);
						//$updateStmt->bindParam(':id', $result['id'], PDO::PARAM_INT);
						$updateStmt->bindParam(':coid', $this->userCompany, PDO::PARAM_INT);

						if( $updateStmt->execute() ) {
							$role = ($role === 'admin' ? 'admin' : 'mgr' );
						} else {
							$this->adminNameWithToken = "Anonymous";
							$role = 'user';
						}
						return $role;

					} //end elseif time expired on the logged-in admin

					//echo "id_user " . $result['id_user'] ." is the same as the id " . $id;
				}//end id user and id are the same.
					// just update the recent login
					$updateTokenColumns .= ', recent_login = :recent ';
					$query = $updateToken.$updateTokenColumns.$updateTokenFilter;				
					
					//echo $query;
				     //UPDATE active_token SET checked_out = :checked_out, recent_login = :recent WHERE coid_token = :coid AND id = :id
					
					$upd = $this->pdo->prepare($query);
					$upd->bindParam(':checked_out', $one);
					$upd->bindParam(':recent', $unixSecs);
					$upd->bindParam(':id', $result['id']);
					$upd->bindParam(':coid', $this->userCompany, PDO::PARAM_INT);
					//$upd->bindParam(':coid', $coid);
					//echo $query; exit; OUTPUTS: UPDATE active_token SET checked_out = :checked_out, recent_login = :recent WHERE id = :id
					
					if($upd->execute()) {	
						
						$role = ($role === 'admin' ? 'admin' : 'mgr' );
						return $role;

					} else {
						$this->adminNameWithToken = "Anonymous";
						return 'user';

					}
					
				//checkedout is 1, but the user_id !== current authenticated user.  
				//If too long, maybe we should expire the active administrator from the token.
				
				} elseif ( $result['id_user'] !== NULL ) {
					// give up dude; you can only login as a user right now:					
					//get the current admin's name for user output notification
					
					$getUser = $this->pdo->prepare("SELECT username from users WHERE id = :uid");
					$getUser->bindParam(':uid', $result['id_user']);
					
						if( $getUser->execute() ){
							
							$queryResult = $getUser->fetch(PDO::FETCH_ASSOC);
							$this->adminNameWithToken = $queryResult['username'];
							
						} else {
							
							$this->adminNameWithToken = "Anonymous";
							
						}					
					
				}
				
					
			
		} else {
			$this->adminNameWithToken = "Anonymous";
			$role = 'user';
			
		}
		
		return $role;
	}// setAdminToken
	
	// called from login.php.
	// Admin logs out; free up the active_token for admin reassignment.
	// returns bool true if unleased, false otherwise.
	public function unleaseToken( $userId = NULL, $coid = NULL ) {
		
		
		if( !is_object( $this->pdo ) ) {
			
			//connect to db
			
			if(	$this->pdo = parent::dbOpen('custo299_wipcalendar')){//open connection to wipcalendar db.
				
				$this->connected = TRUE;		
				
			} else {
				// echo "could not connect to db";
				return FALSE;				
				
			}
			
		}		
		
		// db connection active?
		if( $this->connected == TRUE ) {
			
			// is current user the admin with the token?
			$query = 'SELECT * FROM active_token WHERE id_user = :userId'; // AND access_level = "admin" AND coid_token = :coid';
			
			// admin + coid = 'ALL' -> set coid = '0' (csc) as admin ( CSC admins see and manage all companies );
			$coid = ( $coid === 'ALL' ? '0' : $coid );		
			
			$statement = $this->pdo->prepare($query);
			
			$statement->bindParam(':userId', $userId, PDO::PARAM_INT);
			//$statement->bindParam(':coid', $coid, PDO::PARAM_STR);

			if( $statement->execute() ) {
				
				$data = $statement->fetch(PDO::FETCH_ASSOC);

				if( count( $data ) > 0 ) {
					
					$s = $this->pdo->prepare('UPDATE active_token SET checked_out = 0, recent_login = NULL, id_user = -1 WHERE id_user = '.$userId);
					//access_level = "admin" AND coid_token = '.$coid);
					//echo $s;
					if( $s->execute() ) {
						$this->adminNameWithToken = "[ NONE ]";
						return TRUE;

					} else {
						
						return FALSE;
					}				
				}
			}
		} else {
			
			return FALSE;
		}
		
	}// unleaseToken
	
}// userAuthenticate

?>