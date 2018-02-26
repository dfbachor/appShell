<?php
    require("config.php");
    
    // echo $_SERVER['REQUEST_METHOD'];
	print_r($_POST); exit();
	
	if(empty($_POST['username'])) die("Username required");
	if(empty($_POST['username'])) die("Password required");	
		
	$username = $_POST['username'];
	$password = $_POST['password'];
	$hash = md5($password);
	
	
    $query = "SELECT 1 FROM users WHERE username = :username";
    $query_params = array(':username' => $username);

    try { 
        $stmt = $db->prepare($query); 
        $result = $stmt->execute($query_params); 
    } catch(PDOException $ex){ 
       	echo "0 .Failed to run query: " . $ex->getMessage();
       	exit();
    } 
    
    $row = $stmt->fetch(); 
    
    if($row) { 
        //die("This username address is already registered"); 
        // setup reset password button and sent password via email
        echo "0 .This username is already registered"; // for failure
        exit();
    } 
	
	
?>


