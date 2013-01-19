<?php
require_once 'login.php';
ini_set('session.cache_limiter', 'private');
$forename = $surname = $address = $email = "";

if (isset($_POST['forename']))
$forename =fix_string($_POST['forename']);

if (isset($_POST['surname']))
$surname =fix_string($_POST['surname']);

if (isset($_POST['email']))
$email =fix_string($_POST['email']);

if( isset($_POST['address']))
$address =fix_string($_POST['address']);

$fail  = validate_forename($forename);
$fail .= validate_surname($surname);
$fail .= validate_address($address);
$fail .= validate_email($email);

echo "<html><head><title>Delivery Detail</title>";
if($fail == "")
{
echo "</head><body>Your Shipping Address correctly validated : $forename,$surname ,$address ,$email.</body></html>";
  
//$name = $forename ." ".$surname ;
exit;
 }
 // output javascript
echo <<<_END

<style>.delivery {border:1px solid #999999;
font:normal 14px heveltica; color :#444444;}</style>
<script type ="text/javascript">
function validate(form){
fail = validateForename(form.forename.value)
fail += validateSurname(form.surname.value)
fail +=validateAddress(form.address.value)
fail += validateEmail(form.email.value)
if (fail=="") return true
else { alert(fail); return false }
}
</script></head><body>
<table class="delivery" border="0" cellpadding="4"
cellspacing="10" bgcolor="#eeeeee">
<th colspan="10" align="center">Delivery Details </th>

<tr><td colspan="2">Please enter Shipping details <br />
The folowing fields are required in your form: <p><font color=red size=1>$fail<i></font></p>
</td></tr>
<form method ="post" action="booking_confirmation.php"
onSubmit="return validate(this)">
<tr><td>FirstName</td><td><input type="text" size="60" maxlength="32"
name="forename" value="$forename" /></td>
</tr><tr><td>LastName</td><td><input type="text"  size="60" maxlength="32"
name="surname" value="$surname" /></td>
</tr><tr><td>Delivery Address</td><td><input type="text"  size="120" maxlength="200"
name="address" value="$address"  /></td>
</tr><tr><td>Your Email</td><td><input type="text" size="60" maxlength="64"
name="email" value="$email" /></td>
</tr><tr><td colspan="5" align="center"><input type="submit" value="Confirm details" /></td>
</tr></form></table>

<script type="text/javascript">
function validateForename(field){
if (field == "" ) return " No Firstname entered.\\n"
return ""
}
function validateSurname(field) {
if(field == "" ) return " No Surname entered.\\n"
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
function fix_string($string)
{
if (get_magic_quotes_gpc()) $string = stripslashes($string);
return htmlentities($string);
}

?>