<?php

require_once 'login.php';
require_once 'createTemp.php';
include 'menu.php';
session_start();
$sessionId = session_id();
try {$conn = new PDO("mysql:host=$host;dbname=$dbname",$user, $pwd);
         if(checkSession())
		 {	
          	 
		   	
			 $_SESSION['name'] == "" ? $username =$sessionId: $username=$_SESSION['name']; 
              $username = $_SESSION['name'];
		   /*delete booking from  main booking table and delete record from temporal cart table   */
		    $sqltemp ="delete from nwBooking where customer_name = '$username' ";
            $st = $conn->prepare($sqltemp);
            $st->execute();
			$count = $st->rowCount();
			if($count>=0)
			{
			echo " Booking has been cancelled";            		    
			}
			$sql1 ="delete from Booking where customer_name ='$username' ";
            $st = $conn->prepare($sql1);
            $st->execute();
		    $count = $st->rowCount();
			//delete users record
		    
			destroy_session_data();			
		 }
			 	 		        
     else {          			    
		     	
		   /*drop cart for this booking -temporal booking table    */
		    		   
			droptable('nwBooking',$conn);
				   		
			echo " Booking has been cancelled";            		    
			
		 //   destroy_session_data();	    			    
									
		}
		
	}
	
		 
             
       
catch(PDOException $e) 
 {
 echo $e->getMessage();
 }
 
  echo("<form method=\"post\" action=\"index.php\">\n");
            
            echo("</form>\n");

// security for sql injection
function check_input($string)
{    $problem=' empty string entered';
    $string = trim($string);
    $string = stripslashes($string);
    $string = htmlspecialchars($string);
    if ($problem && strlen($string) == 0)
    {
        die($problem);
    }
    return $string;
}
function sanitizeString($var)
{
$var = stripslashes($var);
$var = htmlentities($var);
$var = strip_tags($var);
return $var;
}
function sanitizeMySQL($var)
{
$var= mysql_real_escape_string($var);
$var= sanitizeString($var);
return $var;
}
function formatDate($date)
{

 return  date("l F jS , Y - g:ia",strtotime($date));
}
function formatBritishDate($date)
{
 
return $newDate = date("d-m-Y", strtotime($date));
}
function checkEmpty($anArray)
 {
   if(empty($anArray))
   {
   
     return "empty";
   }
  
   return true;
 }
 function emptyRows($anArray)
 {
 if(empty($anArray))
   {
     echo "empty";
   }
   return true;
 }
  function checkSession(){
return isset($_SESSION['name']);
}
 
 function checkSet(){
  return isset($_POST['name'],$_POST['address']);
}
function queryMysql($query)
{
$result = mysql_query($query) or die (mysql_error());
return $result;
}
function destroy_session_data()
{

$_SESSION = array();
if(session_id()!="" || isset($_COOKIE[session_name()]))
setcookie(session_name(),'',time()-2592000,'/');
session_destroy();
}
function droptable($table,$conn)
{
$sql1 = "drop table $table ";
             $st = $conn->prepare($sql1);
            $st->execute();		
		
 }
?>