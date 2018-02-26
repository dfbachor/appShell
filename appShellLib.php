<?php
	function logmsg($msg) {
			
			date_default_timezone_set('America/New_York');
			$dayte = date("Y-m-d h:i:sa");
			$fileDayte = date("Y-m-d");
			
			$logmsg = $dayte . " -- " . $msg . "\r\n";

			file_put_contents('log/ApplicationShellLog_' . $fileDayte . '.txt', $logmsg, FILE_APPEND | LOCK_EX);
			
	}
	
	
	function generateRandomToken($n) {
		// generate a random set of charactered the length of $n
		// appends the above to the current time since 01/01/1970:0:0:0 in milliseconds
    	$text = "";
		$possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
	
		$milliseconds = round(microtime(true) * 1000);

		for($i=0; $i < $n; $i++ )
        	$text .= $possible[rand(0, strlen($possible)-1)];

        // $text += possible.charAt(Math.floor(Math.random() * possible.length));

		return $milliseconds .= $text;
	}

?>