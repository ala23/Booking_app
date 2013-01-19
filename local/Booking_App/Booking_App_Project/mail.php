<html>
	<title>Contact Us</title>
	<body>

		<?php
		include 'navigation.php';
		$toemail = "ayoola_al@yahoo.com";
		$error_field = "";
		$error_message="";
		if (isset($_POST['email']) || isset($_POST['subject']) || isset($_POST['message']))
		//if "email" is filled out, send email
		  {
		  	//required field names 
		  	$required = array('email','subject','message');
			$error = false;
			foreach ($required as $field ) 
			{
				
				if (!strlen(trim($_POST[$field])))
				{
				 $error=true; 
				 $error_field = $error_field .$field.' , ';
				 
				 if ($field =='subject')
				 {$error_message ="Please enter the subject of the message you want to send ".'<BR>';}
				
				 elseif ($field=="email") {"email field is empty.please enter valid contact email".'<BR>';}
				 
				 elseif ($field =='message')
				 {$error_message ="Please complete the message you want to send ".'<BR>';}	 
				 
				 }
				 
					
			}
			
			if ($error)
			 {
			  echo "The following field(s) is empty or missing.This field(s) are required: ". $error_field .'<BR>'."please fill out the form" ;
		      displayContactForm();
			 }
			
			//sanitize email
			else {
				$entered_email = $_POST['email'];
			    $sanitized_email = filter_var($entered_email, FILTER_SANITIZE_EMAIL);
			
			   if (filter_var($sanitized_email, FILTER_VALIDATE_EMAIL)) 
			    {
				$email = $sanitized_email;
				//send email if valid email address
				$email = $_POST['email'];
				$subject = $_POST['subject'];
				$message = $_POST['message'];
				if (mail($toemail, $subject, $message." email the sender enterred : ".$email, "From: ".$email))
				{
				exit("Message has been sent .Thank you for contacting  us ");
				
				}
				
				else{die("message not sent") ;}
				/*echo " <div class=navigation><ul><tab> | </tab><a href =index.php>Home Page</a><tab> | 
				</tab><a href = validate_reg_input.php>    Register</a><tab> | </tab> <a href = log_on.php>Check Your Booking</a>
                <tab> | </tab> <a href =index.php#chooseseat>Book Seats</a><tab> | </tab></ul></div>";
				  
				 */
			   }
			 
			  else 
			  {
			   echo "Invalid email entered ";
			    displayContactForm();
			  }
            }
		
			
		  }
		 
		//if "email" is not filled out, display the form
		 else {
			 displayContactForm();

		     }

		function displayContactForm() 
		{
			echo " <h4> Contact Form </h4> ";
			echo "<form method='post' action='mail.php'>
            Your Email: <input name='email' type='text' /><br /><br />
            Subject: <input name='subject' type='text' /><br /><br />
            Message:<br />
            <textarea name='message' rows='20' cols='100'>
            </textarea><br /><br />
            <input type='submit' name='Submit'
             value='submit'/>
            </form>";
		 }	
				
	?>
	</body>
</html>
