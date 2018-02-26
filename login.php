<?php
    require("config.php");
    
    // echo $_SERVER['REQUEST_METHOD'];
	//print_r($_POST); exit();
	
	if(empty($_POST['username'])) die("Username required");
	if(empty($_POST['password'])) die("Password required");	
		
	$rememberMe = $_POST['rememberMe'];
	$loginToken = $_POST['loginToken'];
	$username = $_POST['username'];
	$email = $_POST['username'];
	$password = $_POST['password'];
	$hash = md5($password);
	
	// check to see if the username has an @ character 
	// if so, thei login with email address
	// otherwise login with username
	
	$email = filter_var($email, FILTER_SANITIZE_EMAIL);

	// Validate e-mail
	if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
	    // $email is a valid email address");
	    $query = "SELECT username FROM users WHERE email = :email and password = :password";
	    $query_params = array(':email' => $email, ':password' => $hash);
	} else {
	    // $email is not a valid email address");
		$query = "SELECT username FROM users WHERE username = :username and password = :password";
		$query_params = array(':username' => $username, ':password' => $hash);
	}
	
	
    try { 
        $stmt = $db->prepare($query); 
        $result = $stmt->execute($query_params); 
    } catch(PDOException $ex){ 
       	// echo "0 .Failed to run select query: " . $ex->getMessage();
       	http_response_code(500);
		echo json_encode(array(
			'error' => array(	
			'msg' => 'Failed to run select query: ' . $ex->getMessage(),
			'code' => $ex->getCode(),
			),
		));
		exit();
    } 
    
    $row = $stmt->fetch(); 
    
    if($row) { // this is a successful login
	    $username = $row['username'];
	    
        if($rememberMe == 'true') {
	        
	        // remove any preexisting entries that are 30 days or older
	        $sql = 'delete from rememberme where tokendate < DATE_SUB(now(), INTERVAL 1 MONTH)';
			//$query_params = array(':username' => $username); 		
			
			try {  
					$stmt = $db->prepare($sql); 
					$result = $stmt->execute(); 
			} catch(PDOException $ex) { 
				logmsg("login.php : Failed to run delete query for rememberme token: " . $ex->getMessage());					
			} 			        
	        
			$sql = 'insert into rememberme (token, username, tokenDate) values (:loginToken, :username, now())';	
		
			$query_params = array(
				':loginToken' => $loginToken, 
				':username' => $username); 		

			try {  
				$stmt = $db->prepare($sql); 
				$result = $stmt->execute($query_params); 
			} catch(PDOException $ex) { 
                // not returning error here since we dont want to bother the users with this
                // nest to set a message in the logs for the programmers to find
                
                
                //logmsg("login.php : Failed to insert query for remember me token: " . $ex->getMessage());	
			} 					
		
		}		
		
        // at this point it should be a success
            $query = "SELECT ID, username, name, email FROM users WHERE username = :userName";
            $query_params = array(':userName' => $username);

			try { 
				$stmt = $db->prepare($query); 
				$result = $stmt->execute($query_params); 
				
				$outData = array();
				while($row = $stmt->fetch()) {
					$outData[] = $row;
				} 
				//echo json_encode($outData);
				echo '{"user":' . json_encode($outData) . '}'; 
				exit();
			} catch(PDOException $ex){ 
				echo "0 .Failed to run query: " . $ex->getMessage();
				http_response_code(500);
                echo json_encode(array(
                    'error' => array(	
                    'msg' => 'Error Selecting User: ' . $ex->getMessage(),
                    'code' => $ex->getCode(),
                    ),
                ));
                exit();
			} 
        
    } else { // failed login
	    echo -1;
	    exit();
    }
	
	
?>


