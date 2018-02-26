<!doctype html>
<html>
<head>	
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Application Shell</title>
	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

</head>

<body>
		<!-- Header -->
	<?php include($_SERVER['DOCUMENT_ROOT'] . "/applicationShell/header.html"); ?>
	
	<?php include($_SERVER['DOCUMENT_ROOT'] . "/applicationShell/home.html"); ?>
	<?php include($_SERVER['DOCUMENT_ROOT'] . "/applicationShell/about.html"); ?>
	<?php include($_SERVER['DOCUMENT_ROOT'] . "/applicationShell/contact.html"); ?>
	<?php include($_SERVER['DOCUMENT_ROOT'] . "/applicationShell/signup.html"); ?>
	<?php include($_SERVER['DOCUMENT_ROOT'] . "/applicationShell/login.html"); ?>
	<?php include($_SERVER['DOCUMENT_ROOT'] . "/applicationShell/manageAccount.html"); ?>
	<?php include($_SERVER['DOCUMENT_ROOT'] . "/applicationShell/resetpw.html"); ?>

		<!-- Footer -->
	<?php include($_SERVER['DOCUMENT_ROOT'] . "/applicationShell/footer.html"); ?>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.js"></script>
	
	
	<script src="js/appShell.js"></script>
	
	<script>
		$(document).ready(function() {
		    $('section').eq('home').show(); 
			
		    $('.navbar-nav').on('click', 'a', function() {
		        $($(this).attr('href')).show().siblings('section:visible').hide();
		    });
		});
	</script>

</body>
</html>