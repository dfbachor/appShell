

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
                        name: $("#signUpName").val(),
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

  

