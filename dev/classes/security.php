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
		
		if( preg_match( '~wwwroot~', __DIR__ ) || preg_match( '~dev~', __DIR__ ) ) {			
			$this->userCompany = 'Developer';			
		} 
		
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
	private function authenticate(){	
		
			$statement = $this->pdo->prepare('SELECT `id`, `username`, `role`, `password`, `company` FROM users WHERE `username` = :username ' );
		
			//execute the query, passing in the variables.			
			$statement->execute(['username' => $this->uUsername ]);
			$result = $statement->fetch(PDO::FETCH_ASSOC);
		
		     //avoid member func call on non-obj when results are not returned.
			if(is_object($statement)){
				if( $statement->rowCount() === 1 ) {
					
					//returns TRUE if a match, FALSE otherwise.
					$validate = password_verify( $this->uPassword, $result['password'] ); //returns bool FALSE / TRUE
					if( $validate ) {
						$c=$result['company'];
						//set $this->userCompany
						$this->setUserCompany($c);
						$this->setSessionToken( $result );					
					} else {
						$this->logged = array( 'status'=>'PSWDfail', 'user' => $result['username'], 'role' => $result['role'], 'userId' => $result['id'], 'company' => $this->userCompany );
					}			
				} else {
					$this->logged = array( 'status'=>'USRNfail', 'user' => $this->uUsername, 'role' => 'guest' );
				}	
				$this->pdo = NULL; //close the db connection
			}
	}// authenticate()
	
	// setAdminToken only called if authenticated user has admin privilege in user table.
	// set active_token for admin to checked_out = 1, recent_login = linux time, id_user = this user $id, id_session to session id of user.
	// if checked_out == 0, or if == 1 but recent_login is older than 1 hour
	// return role as admin if token set for user of $id; return rolw as 'user' if token actively leased out to another.
	private function setAdminToken($id)
	{
		/** We use this query once we get all access_token records **/
		$updateToken = 'UPDATE active_token SET ';		
		$updateTokenFilter = ' WHERE id = :id';
		$null = NULL;
		$one = 1;
		$zero = 0;		
		$unixSecs = time();
		/*********************************************************/
		
		// QUERY SELECT ALL from active_token		
		$selectToken = $this->pdo->prepare('SELECT * FROM active_token WHERE coid_token = :userCompany');
		$selectToken->bindParam(':userCompany', $this->userCompany);
		
		if( $selectToken->execute() ){	
			
			$tokenInfo = $selectToken->fetch(PDO::FETCH_ASSOC);
			if( $tokenInfo['checked_out'] === 0 ){		
				
				// This user can claim the available company token:
				// prepare query on update of active_token				
				$queryToken = 	$updateToken.
							'checked_out = :checked_out, recent_login = :recent, id_user = :id_user'.
							$updateTokenFilter;
		
				// query should equal = 'UPDATE active_token SET checked_out = :checked_out, recent_login = :recent, 
				// id_user = :id_user, id_session = :id_session WHERE id = :id';
				$updateStmt = $this->pdo->prepare($queryToken);				
				
				//session not authenticated yet, so set to NULL;
				$updateStmt->bindParam(':checked_out', $one);
				$updateStmt->bindParam(':recent', $unixSecs);
				$updateStmt->bindParam(':id_user', $id);
				//$updateToken->bindParam(':id_session', $null);
				$updateStmt->bindParam(':id', $tokenInfo['id']);
				
					if($updateStmt->execute()) {			
						$role = 'admin';
					} else {					
						$role = 'user';
						$this->adminNameWithToken = "Anonymous";
					}
				
			} else  {  // checked_out = 1;				
				
				// Maybe this is the same Person, then update the recent_login field
				// Or another user's login is stale: recent_login is > 30 mins ago, Replace him with current user.
				
				if( $tokenInfo['id_user'] === $id ){
					
					// just update the recent login
					$updateQuery = $updateToken.'recent_login = :recent '.$updateTokenFilter;
					$upd = $this->pdo->prepare($updateQuery);					
					$upd->bindParam(':recent', $unixSecs);
					$upd->bindParam(':id', $tokenInfo['id']);
					//echo $query; exit; OUTPUTS: UPDATE active_token SET checked_out = :checked_out, recent_login = :recent WHERE id = :id
					
					if($upd->execute()) {						
						return 'admin';
					} else {
						$this->adminNameWithToken = "Anonymous ";
						return 'user';
					}					
				
				} elseif( (( time() / 60 ) - ($tokenInfo['recent_login'] / 60) ) > 60 ){
					
					// recent_login value is greater than 60 mins ago [expire it]
					// set token to this user as new admin
					
					
					$query = $updateToken.'recent_login = :recent, checked_out = :co, id_user = :id_u'.$updateTokenFilter;
					$updateStmt = $this->pdo->prepare($query);
					$updateStmt->bindParam(':co', $one);
					$updateStmt->bindParam(':recent', $unixSecs);
					$updateStmt->bindParam(':id_u', $id);
					$updateStmt->bindParam(':id', $tokenInfo['id']);

					if( $updateStmt->execute() ) {	

						$role = 'admin';

					} else {
						
						$this->adminNameWithToken = "Anonymous";
						$role = 'user';

					}
					
				} //end elseif time expired on the logged-in admin
				elseif ( $result['id_user'] !== NULL ) {
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
				
			}//end else if checked_out == 1			
			
		} else {
			$this->adminNameWithToken = "Anonymous";
			$role = 'user';
			
		}
		
		return $role;
	}// setAdminToken
	
	// called from login.php.
	// Admin logs out; free up the active_token for admin reassignment.
	// returns bool true if unleased, false otherwise.
	public function unleaseToken( $userId = NULL ) {
		
		
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
			$query = 'SELECT * FROM active_token WHERE id_user = :userId AND access_level = "admin"';
			
			$statement = $this->pdo->prepare($query);
			
			$statement->bindParam(':userId', $userId);

			if( $statement->execute() ) {
				
				$data = $statement->fetch(PDO::FETCH_ASSOC);

				if( count( $data ) > 0 ) {
					
					$s = $this->pdo->prepare('UPDATE active_token SET checked_out = 0, recent_login = NULL, id_user = -1 WHERE access_level = "admin"');

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
	
	/**
	* Check if a token for the user's company is available in access_token tbl
	*/
	private function setSessionToken( $result ){		

		if( !empty($result['role']) && $result['role'] !== 'user' ) {
			
			//Developer, mgr or admin
			
			//look up user in the access_token tbl based on his company token
			//reassign as a 'user' if the token is used by someone else.
			$reAssignedRole = $this->setAdminToken($result['id']);
			
			
			
			/* if admin role, and active_token.checked_out == 1, 
			get the id_user, to fetch user name of active admin.*/ 

			if( empty( $reAssignedRole ) ) {

				// another admin has the active_token.

				$reAssignedRole = 'user';

			}

		} else {
			$reAssignedRole = 'user';
			$this->userCompany = $result['company'];
		}			

		$this->logged = array( 'status'=>'AUTH', 'user' => $result['username'], 'role' => $reAssignedRole, 'userId' => $result['id'], 'company' => $this->userCompany );
	
	} //setSessionToken

	// map the company in the database from ALL to 0, etc.  Return new value;
	private function setUserCompany($iniCompany){
		
		switch ($iniCompany){			
			case '':
				$this->userCompany = 0;
				break;
			case 'ALL':
				$this->userCompany = 0;
				break;
			case 'Developer':
				$this->userCompany = 10;
				break;
			default:
				$this->userCompany = $iniCompany;
				break;
		}		
	}
	
}// userAuthenticate

?>