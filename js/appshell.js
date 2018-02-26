
//Globals
var currentUser = null;

$(document).ready(function() {
    
    if(getCookie('loginToken')) {
        attemptAutoLogin(getCookie('loginToken'));
    } else {
        toggleLoginLogoffItems(false);
    }

	/************************************************************************************************/		
	$("#loginPageSignUpLink").on("click", function() {
		$("#signupNavItem").click();
	});	

});

//
function toggleLoginLogoffItems(loggedin) {
    if(loggedin == true){
        $('.loggedOn').show();
        $('.loggedOff').hide();
    } else {// login = false 
        $("#loggedInUserName").html(" ");
        $('.loggedOn').hide();
        $('.loggedOff').show();
    }
}

$('#logoutNavItem').on("click", function() {
    setCookie("loginToken", "", -1);
    currentUser = null;
    toggleLoginLogoffItems(false);
});


$('#signUpButton').on('click', function() {
    if($('#signUpPassword').val() != $('#signUpConfirmPassword').val()) {
        alert("passwords must match");
         // evt.preventDefault();
        return ;
    }

    $.ajax({
        url: 'signup.php',
        type: 'POST',
        data:	{
                    username:   $("#signUpUsername").val(), 
                    name:       $("#signUpName").val(),
                    email:      $("#signUpEmail").val(),
                    password:   $("#signUpPassword").val()
                },
        dataType: 'html',
        success:	function(data){

                        try {
                            data = JSON.parse(data);
                            alert("success");
                            currentUser = data.user; // set the currentUser to the global variable
                                $("#signUpUsername").val("");
                                $("#signUpName").val("");
                                $("#signUpEmail").val("");
                                $("#signUpPassword").val("");
                                $("#signUpConfirmPassword").val("");
                            toggleLoginLogoffItems(true);
                            $("#homeNavItem").click();
                        } catch (ex) {
                            alert(ex);
                        }
                    },
        error: 	    function (xhr, ajaxOptions, thrownError) {
                        alert("-ERROR:" + xhr.responseText + " - " + 
                        thrownError + " - Options" + ajaxOptions);
                    }
    });    		
});

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
                                
                                    toggleLoginLogoffItems(true);
                                $("#homeNavItem").click();
                        } 
                        //console.log(data);
                    },
        error: 	function (xhr, ajaxOptions, thrownError) {
                    alert("ERROR:" + xhr.responseText+" - "+thrownError);
                }
    });    		
}); // end $("#loginButton")

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
/***************************************************************************** */
function setCookie(cname,cvalue,exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires=" + d.toGMTString();
    // alert(cname + " " + cvalue + " " + expires);
    document.cookie = cname + "=" + cvalue + "; " + expires;
}
/************************************************************************** */
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
			} else { // success		
				//alert("successful auto login");
				resp = $.parseJSON(resp);
				currentUser = resp.user;
				toggleLoginLogoffItems(true);
				
                $("#loggedInUserName").html("Hello " + currentUser[0].username);
                alert("welcome Back " + currentUser[0].username);
				//setCookie("userName", $("#manageAccountUserName").val());
		
			} 
		} //end if
	} // end  function

	xmlhttp.open("POST","validateLoginToken.php", true); 
	xmlhttp.send(formData);
} // end  function
