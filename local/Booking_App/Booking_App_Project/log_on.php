<?php
session_start();
ini_set('session.cache_limiter', 'private');
require_once 'login.php';
//include 'view_orders.php';
$email = $password1 ="";
if (isset($_POST['email']))
$email =fix_string($_POST['email']);

if( isset($_POST['password1']))
$password1 =fix_string($_POST['password1']);
$fail = validate_email($email);
$fail .= validate_password($password1);
$message= "";
echo "<html><head><title>Sign In</title>";
if($fail == "")
{

  try {$conn = new PDO("mysql:host=$host;dbname=$dbname",$user,$pwd);
    	
    
   if  (check_customer_exist($email,$password1,$conn)=="No")
	 {
	  
	  $message="Invalid username/password combination";	 
	}
	else {	
	   $msg = check_customer_exist($email,$password1,$conn);
     echo $msg .'<br />'.'<br />';
	  
	  $_SESSION['valid_user'] = true;
	  
	 //die(printBooking($email,$cus_Id,$username,$name,$conn));	  
	//die ("<p><a href=view_orders.php>Click here to view your Bookings</a></p>");
	die(header('Location: view_orders.php'));
	  } 
  }
 catch(PDOException $e) 
 {
 echo $e->getMessage();
 }
  
}
 // output javascript
echo <<<_END

<style>.signIn {border:1px solid #999999;
font:normal 14px heveltica; color :#444444;}</style>
<script type ="text/javascript">
function validate(form){
fail = validateEmail(form.email.value)
fail +=validatePassword(form.password1.value)
if (fail=="") return true
else { alert(fail); return false }
}
</script></head><body>
<table class="signIn" border="0" width ="50%"  height= "50%" cellpadding="2"
cellspacing="10" bgcolor="#eeeeee">
<th colspan="10" align="center">Please Sign In</th>
<tr><td colspan="2"> <br /> <p><font color=red size=3>$message<i></font></p>
</td></tr>
<form method ="post" action="log_on.php"
onSubmit="return validate(this)">
<tr><td>Email</td><td><input type="text" size="60" maxlength="64"
name="email" value="$email" /></td>
<tr><td>Password</td><td><input type="password" size="60" maxlength="64"
name="password1" value="$password1" /></td></tr>
<tr><td colspan="5" align="center"><input type="submit" value="Sign In" /></td>
</tr></form></table>

<script type="text/javascript">

function validateEmail(field) {
if(field == "" ) return " No Email entered.\\n"
		else if (!((field.indexOf(".") > 0) &&
			     (field.indexOf("@") > 0)) ||
			    /[^a-zA-Z0-9.@_-]/.test(field))
		return "The Email address is invalid.\\n"
	return ""
}

function validatePassword(field) {
	if (field == "") return "No Password was entered.\\n"
	else if (field.length < 6)
		return "Passwords must be at least 6 characters.\\n"
	else if (!/[a-z]/.test(field) || ! /[A-Z]/.test(field) ||
		    ! /[0-9]/.test(field))
		return "Passwords require one each of a-z, A-Z and 0-9.\\n"
	return ""
}

</script></body></html>
_END;

// if javascript is disabled validation  will be handled by PHP functions

function validate_email($field)
{
if ($field == "") return "No Email was entered<br />";
		else if (!((strpos($field, ".") > 0) &&
			      (strpos($field, "@") > 0)) ||
			    preg_match("/[^a-zA-Z0-9.@_-]/", $field))
		return "The Email address is invalid<br />";
	return "";
}

function validate_password($field) {
	if ($field == "") return "No Password was entered<br />";
	else if (strlen($field) < 6)
		return "Passwords must be at least 6 characters<br />";
	else if ( !preg_match("/[a-z]/", $field) ||
			!preg_match("/[A-Z]/", $field) ||
			!preg_match("/[0-9]/", $field))
		return "Passwords require 1 each of a-z, A-Z and 0-9<br />";
	return "";
}

function check_customer_exist($email,$pwd1,$conn)
{
$salt1="qm&h*";
$salt2="pg!@";
$token =  md5("$salt1$pwd1$salt2");	
$message=""; 
 //check if customer exists 
 $sql = " select * from Logon where email = '$email' ";
  if( $res=$conn->query($sql))
  {
	$statement = $conn->prepare($sql);
    $statement->execute();
    $count = $statement->rowCount();
  if ($count > 0)
       { 	 
    		foreach ($conn->query($sql)as $row)
			{	
			if(substr($row['pwd'],0,25) == substr($token,0,25))	
			{            			
			 $_SESSION['email']=$row['email'];
	         $_SESSION['username']= $row['username'];                  
      		 $_SESSION['password'] =$row['pwd'];
			 $_SESSION['cus_Id'] =$row['cus_Id'];			  
			}
			elseif(substr($row['pwd'] ,0,25)!= substr($token,0,25))
			{
			  return "No";
			 }			
	       }
			//retrieve customer  details e.g name
     $sql = " select * from Customer where email = '$email'";
    $res=$conn->query($sql);
	$statement = $conn->prepare($sql);
    $statement->execute();
    $countx = $statement->rowCount();
	if ($countx > 0){
	
      foreach($conn->query($sql)as $row)
        {	 
          $_SESSION['name']  = $row['name'];
          $_SESSION['cus_Id']= $row['cus_Id'];	  
         
	     $name =$_SESSION['name'];
		 $username= $_SESSION['username'];
		 $_SESSION['validated_name']=$name;
	     $_SESSION['validated_username']= $username;
	    $message= "Hi ".$name ." you are sign in as : ".$username;
	  }
     } 
    return $message;	 
   }
 }
else{
 
 }
 return "No" ;
}
function fix_string($string)
{
if (get_magic_quotes_gpc()) $string = stripslashes($string);
return htmlentities(trim($string));
}

?>