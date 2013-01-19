<?php
//reponsible for the booking  and update cart nwBooking table with booking details
require_once 'login.php';
include 'cart.php';
include 'navigation.php';
//ini_set('session.cache_limiter', 'private'); 

session_start();
$sessionId = session_id();
  
try {$conn = new PDO("mysql:host=$host;dbname=$dbname",$user, $pwd);
       
       if(isset($_SESSION['name']))
         $_SESSION['name'] == "" ? $username =$sessionId: $username=$_SESSION['name'];
		
				
		if(isset($_SESSION['title'])&& isset($_SESSION['date'] )&& isset($_SESSION['area'] ))
		{
		 
		 $titlev = $_SESSION['title'];
		 
		 $datev = $_SESSION['date'];
		 
		 $areav =$_SESSION['area'];		 
		 	 	
		 		 
        if (isset($_POST['seat']) && isset($_POST['price']))
		  {		    
		     $seat= sanitizeString(substr($_POST['seat'],0,3));
			 
			 $price= sanitizeString(substr($_POST['price'],0,4)) . '<br /> ';
			
		     $price = sanitizeString(deep_replace_str($_POST['price'])) .' <br />';
			  
			  /*  before confirmation*/	
			  $sql = "insert into nwBooking(row_no,date_time,customer_name,area_name,title,price)
		     values('$seat','$datev','$username','$areav','$titlev','$price')";
            $st = $conn->prepare($sql);
            $st->execute();
			if (!$st)
			{
			 die("Execute query error, because: ". $conn->errorInfo());
			}
		    $count = $st->rowCount();
        	if ($count == 0)
			{
			
			echo " the seat choosen is already reserved  ,You can add more seats in this area or another area of the theatre ".'<br />'.'<br />';
			
			showCart($username,$conn);
			
		    echo("<form method=\"post\" action=\"index.php#chooseseat\">\n");
            echo("<input type=\"submit\" value=\"Change Area\">\n");
            echo("</form>\n");
			
			}
			 if ($count >0)
			{
			
			print(" You have added Seat $seat for  $titlev  Showing On " . formatDate($datev)."  to your Basket ").'<br />'.'<br />';
			// display cart
			showCart($username,$conn);		
		
           }
	
	}
   }
   }
		
catch(PDOException $e) 
 {
 echo $e->getMessage();
 }
// security for sql injection
function sanitizeString($var)
{
$var = stripslashes_deep($var);
$var = htmlentities($var);
$var = strip_tags($var);
return $var;
}
//security for sql injection
function sanitizeMySQL($var)
{
$var= mysql_real_escape_string($var);
$var= sanitizeString($var);
return $var;
}
/*
function formatDate($date)
{

 return  date("l F jS , Y - g:ia",strtotime($date));
}
*/
function formatBritishDate($date)
{
 
return $newDate = date("d-m-Y", strtotime($date));
}
function checkEmpty($anArray)
 {
   if(empty($anArray))
   {
   
     return false; 
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
  function checkSet(){
  return isset($_POST['name'],$_POST['address']);
}
function checkSession(){
return isset($_SESSION['title'],$_SESSION['date'],$_SESSION['area'] );
}
function queryMysql($query)
{
$result = mysql_query($query) or die (mysql_error());
return $result;
}

function stripslashes_deep($value)
{
    $value = is_array($value) ?
                array_map('stripslashes_deep', $value) :
                stripslashes($value);

    return $value;
}
function calculatePrice($var)
{
return $var += $var;
}
function deep_replace_str($stringinput)
{

return str_replace( array("\n", "\r"), "", $stringinput );

}
function formatString($string,$length)
{  
    $stringx =$string;
   $lengthx = intval($length);
    $stringo ="0";
  if (strlen($stringx)<$lengthx && strlen($stringx)!=0 )
  {
    $diflength = $lengthx - strlen($stringx);
   for($i=0; $i<=$diflength;$i++)
   {
    $stringx = $stringx + $stringo;
   
   }
   }
  else if (strlen($string)>$lengthx && strlen($string)!=0 )
  {
   $string = substr($string,0,$lengthx) ;
  }
    return $stringx;
  
  }
 

?>
