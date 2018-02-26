<?php
    require("config.php");
    
    // echo $_SERVER['REQUEST_METHOD'];
	//print_r($_POST);
	
	if(empty($_POST['username'])) die("Username required");
	if(empty($_POST['password'])) die("Password required");	
	if(empty($_POST['name'])) die("Name required");	
	if(empty($_POST['email'])) die("email required");	
		
	$username = $_POST['username'];
	$name = $_POST['name'];
	$password = $_POST['password'];
	$email = $_POST['email'];
	$hash = md5($password);
	
	
    $query = "SELECT 1 FROM users WHERE username = :username";
    $query_params = array(':username' => $username);

    try { 
        $stmt = $db->prepare($query); 
        $result = $stmt->execute($query_params); 
    } catch(PDOException $ex){ 
       	echo "0 .Failed to run query: " . $ex->getMessage();
		//logmsg("signup.php : Failed to run query: " . $ex->getMessage());
       	exit();
    } 
	
    
    $row = $stmt->fetch(); 
    
    if($row) { 
        //die("This username address is already registered"); 
        // setup reset password button and sent password via email
		echo "0 .This username is already registered"; // for failure
		
        exit();
    } 
	
	//inserting some some data
	$sql = 'INSERT INTO users (username, name, email, raw_password, password, created_at, updated_at) 
	VALUES (:username, :name,  :email, :rawPassword, :pw, now(), now())';
	
	$query_params = array(
		':username' => $username, 
		':name' => $name, 		
		':email' => $email, 
		':rawPassword' => $password, 		
		':pw' => $hash,
	); 	
		
	//print_r($query_params); exit();
	
	try {  
            $stmt = $db->prepare($sql); 
            $result = $stmt->execute($query_params); 
            // echo true; // for success
            // exit();
    } catch(PDOException $ex) { 
		echo json_encode(array(
        	'error' => array(
            	'msg' => $ex->getMessage(),
            	'code' => $ex->getCode(),
        	),
    	));
			exit();
    } 	  

	$query = "SELECT id, username, name, email FROM users WHERE username = :userName";
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
		
		echo json_encode(array(
        	'error' => array(
            	'msg' => $ex->getMessage(),
            	'code' => $ex->getCode(),
        	),
    	));
       	//echo "0 .Failed to run query: " . $ex->getMessage();
       	exit();
    } 
?>


