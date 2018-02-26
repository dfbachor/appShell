<?php
    require("config.php");
    
    // echo $_SERVER['REQUEST_METHOD'];
	// print_r($_POST);
	
	if(empty($_POST['username'])) die("Username required");
	if(empty($_POST['password'])) die("Password required");	
	if(empty($_POST['firstname'])) die("First name required");	
	if(empty($_POST['lastname'])) die("Last name required");	
	if(empty($_POST['email'])) die("email required");	
		
	$username = $_POST['username'];
	$firstname = $_POST['firstname'];
	$lastname = $_POST['lastname'];
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
		logmsg("signup.php : Failed to run query: " . $ex->getMessage());
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
	$sql = 'INSERT INTO users (username, firstname, lastname, email, rawPassword, hashPassword) 
	VALUES (:username, :firstname, :lastname, :email, :rawPassword, :hashPassword)';
	
	$query_params = array(
		':username' => $username, 
		':firstname' => $firstname, 
		':lastname' => $lastname, 
		':email' => $email, 
		':rawPassword' => $password, 		
		':hashPassword' => $hash); 	
		
	//print_r($query_params); exit();
	
	try {  
            $stmt = $db->prepare($sql); 
            $result = $stmt->execute($query_params); 
            // echo true; // for success
            // exit();
    } catch(PDOException $ex) { 
	        echo "0 .Failed to run query: " . $ex->getMessage(); 
			logmsg("signup.php : Failed to run query: " . $ex->getMessage());
			exit();
    } 	  

	$query = "SELECT ID, username, firstname, lastname FROM users WHERE username = :userName";
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
       	exit();
    } 
?>


