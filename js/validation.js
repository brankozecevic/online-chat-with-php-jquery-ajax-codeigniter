/** 
  * @desc This class is validation of login form input
  * @author Branko Zecevic
*/
$(document).ready(function(){
	//Hiding elements that represents errors
	$('#error1').hide();
	$('#error2').hide();
	//declaring variables used in function that is activated after form is submitted
    let usernameError1 = false;
    let usernameError2 = false;	
	//event triggers
	$('#username').keyup(function(){
        checkName();
	});	
	//checking length and allowed characters for the form field
	function checkName(){
        let nameLength = $('#username').val().length;
		if((nameLength < 2) || (nameLength > 20)){
			$('#error1').html('Username should be betweem 2 and 20 characters.');
			$('#error1').show();
			usernameError1 = true;
		}else {
			$('#error1').hide();
			usernameError1 = false;
		} 
		let pattern = /^[-a-zA-Z0-9]*$/;
		if(pattern.test($('#username').val())){
			$('#error2').hide();
			usernameError2 = false;
		}else{
			$('#error2').html('Only letters and numbers are allowed in Username field.');
			$('#error2').show();
			usernameError2 = true;
		}
	}	
	//what happens when user click submit on the form
	$('#loginForm').submit(function(){
        checkName();		
		if((usernameError1 === false) && (usernameError2 === false)){
		    return true;
		}
		else return false;
	}); 	
});