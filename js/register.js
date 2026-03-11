		$(function(){
		function checkPasswordMatch() {
			var password = $("#password").val();
			var confirmPassword = $("#rpassword").val();

			if (password != confirmPassword)
				$("#checkpassword").text(no_password_r);
			else
				$("#checkpassword").text("");
		}

		$(document).ready(function () {
		   $("#rpassword").keyup(checkPasswordMatch);
		});
		
		function checkUsername() {
			var name = $("#username").val();
			var regex = /^[0-9a-zA-Z]*$/;

			if(!regex.test(name))
				$("#checkname").text(no_special_chars);
			else
				$("#checkname").text("");
		}

		$(document).ready(function () {
		   $("#username").keyup(checkUsername);
		});	
		
		function checkUsername2() {
			var name = $("#username").val();
			var n = name.length;
			var type = 1;
			
			if(n>=5 && n<=16)
			{
				$.post(site_url + "checkusername.php", { type:type, username:name },  
					function(result){  
						if(result == 1)
							$('#checkname2').text(name + ' ' + not_available);
						else
							$('#checkname2').text("");  
				});
			}
		}

		$(document).ready(function () {
		   $("#username").keyup(checkUsername2);
		});
		
		function checkUserEmail() {
			var email = $("#email").val();
			var type = 2;
			if (email.indexOf("@") >= 0)
			{
				$.post(site_url + "checkusername.php", { type:type, email:email },  
					function(result){  
						if(result == 1)
							$('#checkemail').text(email + ' ' + not_available);
						else
							$('#checkemail').text("");  
				});  
			}
		}

		$(document).ready(function () {
		   $("#email").keyup(checkUserEmail);
		});	
		
		
		});