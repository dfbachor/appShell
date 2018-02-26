
// Global variables
var currentUser = null;


$(document).ready(function () {
	alert(document.cookie);
	var urlParameters = getURLParameters();
	// startup
	showMenuBasedOnLogin(false);


	// need to check url parameters to see if we need to show reset password screen
	if(urlParameters.resetToken) { 
		// this will require reset password to show
		showMenuBasedOnLogin(false);
		$("#homeNavItem").click();
		$("#home").hide();
		
		// populate hidden field with reset token
		$("#resetPWToken").val(urlParameters.resetToken);
		
		$("#resetPW").show();
		$("#resetPWButton").on("click", function() {
			
				if( $("#resetPWPassword").val() == "" ||
					$("#resetPWConfirmPassword").val() == "" ||
					$("#resetPWPassword").val() != $("#resetPWConfirmPassword").val()) {
					
					alert("Matching password values are required.");
					$("#resetPWPassword").focus();
					return;
				}
				
				$.ajax({
					url: 	'resetpw.php',
					type: 	'POST',
					data:	{
								token: $("#resetPWToken").val(), 
								password: $("#resetPWPassword").val()
							},
					dataType: 	'html',
					success:	function(data){
									alert("resetPW : "+ data);
									
									if(parseInt(data) != 0){ // success
										alert("Your password has been updated, please login");
										$("#loginNavItem").click();							
									} else {
										alert(data.split(".")[1]);								
									}
								},
					error: 	function (xhr, ajaxOptions, thrownError) {
								alert("-ERROR:" + xhr.responseText + " - " + thrownError + " - Options" + ajaxOptions);
							}
				}); // ajax
		});// $("#resetPWButton").on("click", function()
		
	} else {
	// normal 
		if(getCookie('loginToken')) {
			attemptAutoLogin(getCookie('loginToken'));
		} else {
			showMenuBasedOnLogin(false);
		}
	}
	/************************************************************************************************/
	
	$("#changePassword").on("show.bs.modal", function () {
		$("body").addClass("modal-open");		
		$("#changePasswordUsername").val($("#manageAccountUserName").val());
		
	});
	
	/************************************************************************************************/
	
	$("#changePasswordButton").on("click", function() {
		
		// get values from the change pw modal
		// send to database		
		if( !$(this).hasClass("disabled")) { // not disabled 
			
			$.ajax({
				url: 'changepassword.php',
				type: 'POST',
				data:	{
							username: $("#changePasswordUsername").val(), 
							oldPassword: $("#changePasswordOldPassword").val(),
							newPassword: $("#changePasswordPassword").val(),
						},
				dataType: 'html',
				success:	function(data){
								if(parseInt(data) == 0){
									alert(data.split(".")[1]);							
								} else {
									alert(data);								
								}
								$("#changePasswordForm").trigger('reset');
								$("#changePasswordCloseButton").click();
							},
				error: 	function (xhr, ajaxOptions, thrownError) {
							alert("-ERROR:" + xhr.responseText + " - " + thrownError + " - Options" + ajaxOptions);
						}
			});    		
		}
	});

	/************************************************************************************************/
	$("#signupNavItem").on("click", function() {
		$("#signupForm").trigger('reset');
	});
	
	$("#signUpButton").on("click", function() {
		if( !$(this).hasClass("disabled")) { // not disabled 
			
			if($("#signUpPassword").val() != $("#signUpConfirmPassword").val()) {
				alert("your passwords do not match");
				$("#signUpPassword").val("");
				$("#signUpConfirmPassword").val("");
				return; 
			}
			
			$.ajax({
				url: 'signup.php',
				type: 'POST',
				data:	{
							username: $("#signUpUsername").val(), 
							firstname: $("#signUpFirstName").val(),
							lastname: $("#signUpLastName").val(),
							email: $("#signUpEmail").val(),
							password: $("#signUpPassword").val()
						},
				dataType: 'html',
				success:	function(data){
								// alert(data);
								if(parseInt(data) != 0){ 
									data = $.parseJSON(data);
									currentUser = data.user;
									alert("success");
									$("#loggedInUserName").html("Hello " + data.user[0].username);
									showMenuBasedOnLogin(true);
									$("#homeNavItem").click();
										
								} else { // error
									alert(data.split(".")[1]);
								}						
							},
				error: 	function (xhr, ajaxOptions, thrownError) {
							alert("-ERROR:" + xhr.responseText + " - " + thrownError + " - Options" + ajaxOptions);
						}
			});    		
		} // end if
	}); // end $("#signUpButton").on("click", function() {
		
	/************************************************************************************************/
	$("#manageAccountButton").on("click", function() {
		if( !$(this).hasClass("disabled")) { // not disabled 			

			$.ajax({
				url: 'updateAccount.php',
				type: 'POST',
				data:	{
							ID: $("#manageAccountID").val(), 
							username: $("#manageAccountUserName").val(), 
							firstname: $("#manageAccountFirstName").val(),
							lastname: $("#manageAccountLastName").val()
						},
				dataType: 'html',
				success:	function(data){
								//alert(data);
								
								if(parseInt(data) != 0) { // error
									data = $.parseJSON(data);
									currentUser = data.user;
								 	alert("update successful");									
								 	$("#loggedInUserName").html("Hello " + data.user[0].username);
									
								} else { // success		
									alert(data.split(".")[1]);
									$("#manageAccountUserName").val(currentUser[0].username);
								} 
								
							},
				error: 	function (xhr, ajaxOptions, thrownError) {
							alert("-ERROR:" + xhr.responseText + " - " + thrownError + " - Options" + ajaxOptions);
						}
			});    		
		} // end if
	}); // end $("#manageAccountButton").on("click", function() {

	/************************************************************************************************/
	$("#loginButton").on("click", function() {
		var loginToken = "";
		
		if($("#rememberMeCheckBox").is(':checked')) 
				loginToken = generateRandomToken(25); // random string of length 25		
		
		$.ajax({
			url: 'login.php',
			type: 'POST',
			data:	{
						rememberMe: $("#rememberMeCheckBox").is(':checked'), 
						loginToken: loginToken, 
						username: $("#loginUsername").val(), 
						password: $("#loginPassword").val()
					},
			dataType: 'html',
			success:	function(data){
							//alert(data);
							if(parseInt(data) == 0) { // error
							
							 	alert(data.split(".")[1]);
								
							} else if (parseInt(data) == -1){
								
								alert("Invalid username and password combination!");
								
							} else {

									data = $.parseJSON(data);
									currentUser = data.user;
									$("#loggedInUserName").html("Hello " + data.user[0].username);
									if($("#rememberMeCheckBox").is(':checked'))
										setCookie("loginToken", loginToken);
									showMenuBasedOnLogin(true);
									$("#homeNavItem").click();
							} 
							//console.log(data);
						},
			error: 	function (xhr, ajaxOptions, thrownError) {
						alert("ERROR:" + xhr.responseText+" - "+thrownError);
					}
		});    		
	}); // end $("#loginButton")
	/************************************************************************************************/	
	$("#manageAccountNavItem").on("click", function() {
	
		var formData = new FormData();
		formData.append('userName',  currentUser[0].username);
		
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				var resp = xmlhttp.responseText; // return values go here
					
				//alert("respone from getAccountInfo.php " + resp);
					
				if(parseInt(resp) != 0){ 
					resp = $.parseJSON(resp);
					currentUser = resp.user;
					//alert(resp);
					
					$("#manageAccountID").val(resp.user[0].ID);
					$("#manageAccountUserName").val(resp.user[0].username);
					$("#manageAccountFirstName").val(resp.user[0].firstname);
					$("#manageAccountLastName").val(resp.user[0].lastname);
					$("#manageAccountPassword").val("");
					$("#manageAccountConfirmPassword").val("");
						
				} else { // error
					alert(resp.split(".")[1]);
				}
			} //end if
		} // end  function

			xmlhttp.open("POST","getAccountInfo.php", true); 
			xmlhttp.send(formData);
	});
	/************************************************************************************************/
	$("#logoutNavItem").on("click", function() {
		setCookie("loginToken", "", -1);
		currentUser = null;
		showMenuBasedOnLogin(false);
	}); // end $("#logoutButton").on("click", function() {
	/************************************************************************************************/		
	$("#loginPageSignUpLink").on("click", function() {
		$("#signupNavItem").click();
	});
	
	/************************************************************************************************/	
	
	$("#forgotPasswordModal").on("show.bs.modal", function () {
		//alert($("#loginUsername").val());
		$("body").addClass("modal-open");		
		$("#forgotPasswordUsername").val($("#loginUsername").val());
		//$("#forgotPasswordUsername").focus();
	});

	/************************************************************************************************/		
	$("#forgotPasswordButton").on("click", function() {
		$.ajax({
			url: 'forgotPassword.php',
			type: 'POST',
			data:	{
						username: $("#forgotPasswordUsername").val(), 
					},
			dataType: 'html',
			success:	function(data){
							
							alert("forgotPasswordButton: " + data);
						
							
							if(parseInt(data) == 0) { // error
							 	alert(data.split(".")[1]);
							} else { // success		
							 	alert("An email was sent to the email addresss on file for this account");
							} 
							//console.log(data);
						},
			error: 	function (xhr, ajaxOptions, thrownError) {
						alert("-ERROR:" + xhr.responseText + " - " + thrownError + " - Options" + ajaxOptions);
					}
		});    			
	});
	
	
}); // end document.ready


function showMenuBasedOnLogin (login) {
	// pass in true if the user has logged in
	// otherwise pass in false
	
	if(login == true) {
		$(".loggedOn").show();
		$(".loggedOff").hide();
						
	} else { 
		$("#loggedInUserName").html(" ");
		$(".loggedOn").hide();
		$(".loggedOff").show();
		$("#homeNavItem").click();		
	}
}


function getCookie(cName) {
	if(document.cookie){
		var cookie = document.cookie.split("; ");
		for(var i = 0; i < cookie.length; i++) {
			if(cookie[i].split("=")[0] == cName) {
				return unescape(cookie[i].split("=")[1]);
			}
		}
	}
}

function setCookie(cname,cvalue,exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires=" + d.toGMTString();
    // alert(cname + " " + cvalue + " " + expires);
    document.cookie = cname + "=" + cvalue + "; " + expires;
}


function generateRandomToken(n)
{
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
	
	var dayte = new Date();
    var dateInMilliseconds = dayte.getTime();
    

    for( var i=0; i < n; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return dateInMilliseconds + text;
}


function attemptAutoLogin(token) {
	//alert("attemptAutoLogin token " + token);
	var formData = new FormData();
	formData.append('loginToken', token);

	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var resp = xmlhttp.responseText; // return values go here
				
			//alert("response from validateLoginToken.php " + resp);
				
			if(parseInt(resp) <= 0) { // error
				// alert(data.split(".")[1]);
				// $("#manageAccountUserName").val(getCookie("userName"));
			} else { // success		
				//alert("successful auto login");
				
				resp = $.parseJSON(resp);
				currentUser = resp.user;
				showMenuBasedOnLogin(true);
				
				$("#loggedInUserName").html("Hello " + currentUser[0].username);
				//setCookie("userName", $("#manageAccountUserName").val());
		
			} 
		} //end if
	} // end  function

	xmlhttp.open("POST","validateLoginToken.php", true); 
	xmlhttp.send(formData);
} // end  function


function getURLParameters() {
	// returns an array of name value pars for any url parameters
	var prmstr = window.location.search.substr(1);
	
	if(!(prmstr != null) && (prmstr != "")) {
    	return {};
	} else {
		var params = {};
	    var prmarr = prmstr.split("&");
	    for ( var i = 0; i < prmarr.length; i++) {
	        var tmparr = prmarr[i].split("=");
	        params[tmparr[0]] = tmparr[1];
	    }
	    return params;		    	
	}
}




