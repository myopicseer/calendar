<?php

    // a user submitted a username and password
    if(isset($_POST['username']) && isset($_POST['password'])) {
	    
	    mysqli_report(MYSQLI_REPORT_STRICT);	   
	    define( 'DS', DIRECTORY_SEPARATOR);
			 
	   // 'classes' directory, one level above the html root
	   include("..".DS."..".DS."classes".DS."dbconn.php");
	   // connect to mysql and execute query
	   define("MYSQL_CONN_ERROR", "Unable to connect to database."); 
	 
	   // Ensure reporting is setup correctly
	   
	   $dbClass = new dbConn;  
	    
	    
	    
        try {
            // connect to the database
            $pdo = $dbClass->dbOpen('custo299_users'); 	
            // prepare the statement to find the user
            $stmt = $pdo->prepare('SELECT password from users where UPPER(username) = UPPER(:username)');
            $stmt->bindParam(':username', $_POST['username']);
            // execute the statement
            $stmt->execute();
            // get the result (user information, specifically the user's password
            $result = $stmt->fetch(\PDO::FETCH_OBJ);
            // make sure the user info was found
            if($result !== false) {
                // check to see if the passwords match
                // normally we would check to see if the hashes matched
                if($result->password === $_POST['password']) {
                    // successfully logged in
                }
            }
        } catch(\Exception $e) {
            var_dump($e->getMessage());
        }
    }
?>
