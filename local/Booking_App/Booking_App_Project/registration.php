<?php
require_once 'login.php';
start_session();
$sessionId = session_id();

try {$conn = new PDO("mysql:host=$host;dbname=$dbname",$user, $pwd);

if (isset($_POST['email'] && $_POST['cus_address']&& $_POST['forename']&& $_POST['surname']&& $_POST['username']&& $_POST['password1'] && $_POST['password2']))
{   
   $email= fix_string($_POST['email']);
   $forename =fix_string ($_POST['forename']);
   $surname =fix_string ($_POST['surname']);
   $username =fix_string ($_POST['username']);    
   $cus_address =$_POST['cus_address'];
   $pwd1 =fix_string ($_POST['password1']);
   $pwd2 = fix_string($_POST['password2']);
   $name = $forename ." ".$surname;
   $_SESSION[valid_user]= false;
   $_SESSION[valid_pwd] = false;
    if ( $pwd1!= $pwd1)
   {
   echo " password entered do not matched please try again";   
   }
   elseif (validate_password($pwd1) == "") 
   { 
     echo " password valid";
		
   }
    
     
     // if valid email check if not already registered email customer
	  if (check_customer_exist($username,$email,$conn))
	  {
	    register_user( $name,$email,$cus_address,$pwd1,$conn);
	  }
	  else
	  { 
	   echo $message = check_customer_exist($username,$email,$conn);
	 // show login page  	 
     }   
    
   
 
}

catch(PDOException $e) 
 {
 echo $e->getMessage();
 }

 function check_valid_user($name)
 {
 
 }


function validate_password($pwd1)
{
      if ($pwd1=="")
     {
        return " No password entered";
     }
     else if (strlen( $pwd1< 6)|| strlen( $pwd1>10))
      {
         return "password MUST be between 6 and 10 chararaters long";

       }
   else if (!preg_match("/[a-z]/",$pwd1)||
            !preg_match("/[A-Z]/",$pwd1) ||
			!preg_match("/[0-9]/",$pwd1))
    	{  
		return "password require 1 each a-z ,A-Z and 0-9<br />";
	   }
			
	return "";

}
function validate_email($field)
{
if ($field == "") return " Your email required<br />";
else if (!((strpos($field,".")>0)&&(strpos($field,"@")>0)) ||
   preg_match("/[^a-zA-Z0-9.@_-]/",$field))
 return "invalid email<br />";
return "";
}

function validate_address($field)
{
if ($field == "") return "Address is required<br />";
else if (strlen($field) < 10) return "Address must be more than 10 characters <br />";
return "";
}

// check form is filled
function filled_out($postVals)
{
     foreach( $postVals as $key=> $value)
	 {    
        if !(isset($key)|| $value=="")
		 return false;
     }
        
   return true;
 }
  
function check_customer_exist($username,$email,$conn)
{
 //check if customer exists
 $sql =" select * from Customer where email = '$email' " ;
 if ($res=$conn->query($sql))
	$statement = $conn->prepare($sql);
    $statement->execute();
    $count = $statement->rowCount();
 if ($count > 0)
  {  
    return " this email " . $email . " is already registered ";
  }
  //check if username is available
 $sql=" select * from Logon where username = '$username' ";
 if ($res=$conn->query($sql))
 {
	$statement = $conn->prepare($sql);
    $statement->execute();
    $count = $statement->rowCount();
   if ($count > 0)
   {  
    return " this username " . $username . " is already registered please choose another username";
   }
 }
 
  return true;
}

function  register_user($name,$email,$cus_address,$pwd1,$conn)
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
         }		
}

function fix_string($string)
{
if (get_magic_quotes_gpc()) $string = stripslashes($string);
return htmlentities($string);
}




?>