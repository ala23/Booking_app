<?php
require_once 'login.php';
ini_set('session.cache_limiter', 'private');
$forename = $surname = $address = $email = $cus_address = $username = $password1 = $password2="";

if (isset($_POST['forename']))
$forename =fix_string($_POST['forename']);

if (isset($_POST['surname']))
$surname =fix_string($_POST['surname']);

if (isset($_POST['email']))
$email =fix_string($_POST['email']);

if( isset($_POST['cus_address']))
$cus_address =fix_string($_POST['cus_address']);

if( isset($_POST['username']))
$username =fix_string($_POST['username']);

if( isset($_POST['password1']))
$password1 =fix_string($_POST['password1']);

if( isset($_POST['password2']))
$password2 =fix_string($_POST['password2']);


$fail  = validate_forename($forename);
$fail .= validate_username($username);
$fail .= validate_surname($surname);
$fail .= validate_address($cus_address);
$fail .= validate_email($email);
$fail .= validate_password($password1);
$fail .= validate_password($password2);
$fail .= compare_Passwords($password1,$password2);
$message= "";
$name = $forename." ".$surname;

echo "<html><head><title>Registration</title>";
if($fail == "")
{

 try {$conn = new PDO("mysql:host=$host;dbname=$dbname",$user, $pwd);
  
   if  (!(check_customer_exist($username,$email,$conn)))
	  {
	 $message = register_user($name,$username,$email,$cus_address,$password1,$conn);
	 session_start();
	  $_SESSION['validated_name'] = $name ;
     $_SESSION['validated_username']= $username;
	$_SESSION['validated_address']=$cus_address;
	 $_SESSION['valid_user']= true;
	 $_SESSION['name'] =$name;
	 $_SESSION['username']= $username;
	 $_SESSION['forename'] =$forename;
	 $_SESSION['surname'] =$surname;
	 $_SESSION['password'] =$password1;
	 
	  echo "</head><body><br />$message </body></html>";
	/*   echo <<<_END
	   <form method ="post" action="index.php"> 
       <input type ="submit"  value="Home Page" />
        </form>
_END;*/
   exit("<p><a href = index.php>click here to continue</a></p>");
	  }
   else
	  { 
	  // customer detail already registered
	    $message = check_customer_exist($username,$email,$conn);	   
	 // show login page  	 
      } 

  }
 catch(PDOException $e) 
 {
 echo $e->getMessage();
 }
  
}
 // output javascript
echo <<<_END

<style>.registration {border:1px solid #999999;
font:normal 14px heveltica; color :#444444;}</style>
<script type ="text/javascript">
function validate(form){
fail = validateForename(form.forename.value)
fail += validateSurname(form.surname.value)
fail +=validateAddress(form.cus_address.value)
fail += validateEmail(form.email.value)
fail +=validateUsername(form.username.value)
fail +=validatePassword(form.password1.value)
fail +=validatePassword(form.password2.value)
fail +=comparePasswords(form.password1.value,form.password2.value)
if (fail=="") return true
else { alert(fail); return false }
}
</script></head><body>
<table class="registration" border="0" width ="90%"  height= "80%" cellpadding="4"
cellspacing="10" bgcolor="#eeeeee">
<th colspan="10" align="center">Registration Form</th>
<tr><td colspan="2"> <br /> <p><font color=red size=3>$message<i></font></p>
</td></tr>
<form method ="post" action="validate_reg_input.php"
onSubmit="return validate(this)">
<tr><td>FirstName</td><td><input type="text" size="60" maxlength="32"
name="forename" value="$forename" /></td>
</tr><tr><td>LastName</td><td><input type="text"  size="60" maxlength="32"
name="surname" value="$surname" /></td>
</tr><tr><td>Address</td><td><input type="text"  size="190" maxlength="200"
name="cus_address" value="$cus_address"  /></td>
</tr><tr><td>Email</td><td><input type="text" size="60" maxlength="64"
name="email" value="$email" /></td>
</tr><tr><td>Username</td><td><input type="text" size="60" maxlength="64"
name="username" value="$username" /></td>
</tr><tr><td>Password</td><td><input type="password" size="60" maxlength="64"
name="password1" value="$password1" /></td></tr>
<tr><td>Confirm Password</td><td><input type="password" size="60" maxlength="64"
name="password2" value="$password2" /></td></tr>
<tr><td colspan="5" align="center"><input type="submit" value="Confirm details" /></td>
</tr></form></table>

<script type="text/javascript">
function validateForename(field){
if (field == "" ) return " No Firstname entered.\\n"
return ""
}
function validateSurname(field) {
if(field == "" ) return " No Surname ented.\\n"
return ""
}
function validateAddress(field) {
if(field == "" ) return " No Address  entered.\\n"
else if (field.length < 10) return "Address must be more than 10 characters.\\n"
return ""
}
function validateEmail(field) {
if(field == "" ) return " No Email entered.\\n"
		else if (!((field.indexOf(".") > 0) &&
			     (field.indexOf("@") > 0)) ||
			    /[^a-zA-Z0-9.@_-]/.test(field))
		return "The Email address is invalid.\\n"
	return ""
}
function validateUsername(field) {
	if (field == "") return "No Username was entered.\\n"
	else if (field.length < 5)
		return "Usernames must be at least 5 characters.\\n"
	else if (/[^a-zA-Z0-9_-]/.test(field))
		return "Only letters, numbers, - and _ in usernames.\\n"
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
function comparePasswords(field1,field2){
if (!( field1 == field2)) return " Passwords entered do not match.\\n"
return ""

}

</script></body></html>
_END;

// if javascript is disabled validation  will be handled by PHP functions
function validate_forename($field)
{
if ($field == "") return " Firstname  is required<br />";
return "";
}
function validate_surname($field)
{
if ($field == "") return " Lastname  is required<br />";
return "";
}
function validate_address($field)
{
if ($field == "") return "Address is required<br />";
else if (strlen($field) < 10) return "Address must be more than 10 characters <br />";
return "";
}
function validate_email($field)
{
if ($field == "") return "No Email was entered<br />";
		else if (!((strpos($field, ".") > 0) &&
			      (strpos($field, "@") > 0)) ||
			    preg_match("/[^a-zA-Z0-9.@_-]/", $field))
		return "The Email address is invalid<br />";
	return "";
}
function validate_username($field) {
	if ($field == "") return "No Username was entered<br />";
	else if (strlen($field) < 5)
		return "Usernames must be at least 5 characters<br />";
	else if (preg_match("/[^a-zA-Z0-9_-]/", $field))
		return "Only letters, numbers, - and _ in usernames<br />";
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
function compare_Passwords($field1,$field2){
if (!($field1==$field2)) return "Passwords do not match <br />";
return "";

}
function check_customer_exist($username,$email,$conn)
{
 //check if customer exists
 $sql = " select * from Customer where email = '$email' ";
    $res=$conn->query($sql);
	$statement = $conn->prepare($sql);
    $statement->execute();
    $count = $statement->rowCount();
 if ($count > 0)
  {  
    return " The email entered: " . $email . "  is already registered please choose another email or Log in";
  }
  //check if username is available
 $sql=" select * from Logon where username ='$username' ";
  $res=$conn->query($sql);
 
	$statement = $conn->prepare($sql);
    $statement->execute();
    $count = $statement->rowCount();
   if ($count > 0)
   {  
    return " The username entered: " . $username . "  is already registered please choose another username or Log in ";
   } 
 
  return false;
}

function  register_user($name,$username,$email,$cus_address,$pwd1,$conn)
{
 // insert customer details and generate customer ID insert password
 $spl="insert into Customer(name,address,email)
			  values('$name','$cus_address','$email')";			  		 
		    $st = $conn->prepare($spl);
            $st->execute();
			if (!$st){exit("database access failed:".mysql_error()); }
			$count = $st->rowCount();
            if ($count >0)
          {		
           $salt1="qm&h*";
           $salt2="pg!@";
           $token =  md5("$salt1$pwd1$salt2");		   
           $spl="insert into Logon(email,username,pwd,cus_Id)
			  values('$email','$username','$token',(select cus_Id from Customer where email= '$email'))";			  		 
		   $st = $conn->prepare($spl);
           $st->execute();
		   $_SESSION['validated_name'] = $name ;
		   $_SESSION['validated_username']= $username;
		   $_SESSION['validated_address']=$cus_address;
		return  $message = " Your Registration was sucessfull<br />" . " Your Username is ".$username.'<br />' ;
         }		
}

function fix_string($string)
{
if (get_magic_quotes_gpc()) $string = stripslashes($string);
return htmlentities(trim($string));
}

?>