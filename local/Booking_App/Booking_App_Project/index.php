<?php
/*

this a naive theatre booking system it allow users to select from list of perfomance and theatre area which are displayed on the home page -index.php
after selecting  one area and performance users are shown list of seats available in that area .The user can book as many seats as they wish but one seat at a time.
The selected seat is then added to basket and seats are shown to the user for confirmation as they are added to cart.user can then decide to cancel or confirm the booking .
if confirmed the system offers user options of printing ticket or cancelling the booking. 
if cancel is selected recorded are deleted from both the Booking table and nwBooking table .nwBooking table is temporal table that is use to store the cart before confirmation of booking selected item are stored in this table.After confirmation of booking records are updated on nwBooking and inserted into Booking table e.g names are upadated with real names suplied by user  .if cancel depending on the stage the records are delected from the nwBooking table or/and Booking table.
this system is still very naive ..
*/
session_start();
require_once 'login.php';
ini_set('session.cache_limiter', 'private');
$sessionId = session_id();
if (isset($_SESSION['name']))
$username = $_SESSION['name'];
else 
$username = $sessionId ;

  if (checkSession()){
              if  (!($_SESSION['name']== $sessionId ))
			  
			 {
			 $username = $_SESSION['name'];
			 //echo "Hi ".$_SESSION['name'].'<br />'.'<br /> '; 
			 if (isset($_SESSION['validated_name']))			
			 echo "Hi ".getFirword($_SESSION['validated_name']).'<br />'.'<br /> '; 
			 if (isset($_SESSION['validated_username']))
			 echo "You are sign in  as  ".$_SESSION['validated_username'].'<br />'.'<br /> ';
              if (isset($_SESSION['validated_address']))
			  $cusaddress=  $_SESSION['validated_address'];			 
			 }
	}


// create temporal table for cart  
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname",$user, $pwd);
      if(!$conn)
	  { 
	   die('Could not connect: ' . mysql_error());
	  }
	  $sql1 = "CREATE  TABLE IF NOT EXISTS nwBooking LIKE Booking";
       
            $st = $conn->prepare($sql1);
            $st->execute();
		    $count = $st->rowCount();
    	   // print("Number of rows afffected $count rows.\n");	  
                
			
        $sql3="Alter table nwBooking add column area_name char(12) NOT NULL ,add column title varchar(32) NOT NULL,add column price decimal(5,2) NOT NULL";
		$st = $conn->prepare($sql3);
            $st->execute();
		    $count = $st->rowCount();
    	    //print("Number of rows afffected $count rows.\n");			
			
     }
	
	 
catch(PDOException $e) 
 {
 echo $e->getMessage();
 }
 echo <<<_END
<html!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><head><title>Theater Booking System</title></head>
<body align =center>
<p><h2>Theatre Booking APP </h2></p>
 <div class="navigation"><ul><a href = validate_reg_input.php> Register</a><tab> | </tab> <a href = log_on.php>Check Orders</a>
 <tab> | </tab> <a href =index.php#chooseseat>Book Seats</a><tab> | 
 </tab><a href =mail.php>Contact Us</a> <tab>|</tab> 
  <br /><br /><form method ="post" action="showcart.php"><input type ="submit"  value="Show Cart" />
</form> </ul>
	</div>
<h3>Please See Below Our Shows </h3>
</body>
<br /></html>
_END;

 try {$conn = new PDO("mysql:host=$host;dbname=$dbname",$user, $pwd);	 
    
	$sql = " select title ,DATE(date_time) AS DATE,TIME(date_time) AS TIME from Performance order by DATE ,TIME ";
	echo "  Theater Perfomance Times " .'<br />';
	echo "<table CELLPADDING=4> <tr><th>Performance</th><th>Date</th>  <th>Time</th>";
	foreach ($conn->query($sql) as $row)	
	{
	$originalDate = $row['DATE'];
	$time =$row['TIME'];
	$title=$row['title'];
    $newDate = date("d-m-Y", strtotime($originalDate));
      echo "<tr>";
	  echo " <td> $title </td> ";
	  echo " <td> $newDate </td> ";
	  echo " <td> $time </td> ";
	  echo "</tr>";
	}
	echo "</table>";
	
	} 
   catch (PDOException $e)
   {
	echo $e->getMessage();
  }


echo <<<_END
<html!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><head><title></title></head><body>
</body>
<br />
<!-- default to popular show large area -->
  <div class = "choose"><h4><a name="chooseseat" >To book please select a performance to see avalaible seats and prices, click show seats button </a></h4>
<form method ="post" action="seats.php">
<label>Macbeth <input type="radio" name ="title"  value="Macbeth" checked="checked"/> 
<label>Othello <input type="radio" name ="title"  value="Othello" />
<label>Romeo and Juliet <input type="radio" name ="title"  value="Romeo and Juliet" />
<br /><br />
Choose Seat Area <br /><br />
<label>box 1 <input type="radio" name ="area"  value="box 1" />|
<label>box 2 <input type="radio" name ="area"  value="box 2" />|
<label>box 3 <input type="radio" name ="area"  value="box 3" />|
<label>box 4<input type="radio" name ="area"   value="box 4" />|
<label>balcony <input type="radio" name ="area"  value="balcony" />|
<label>front stalls<input type="radio" name ="area"  value="front stalls" checked="checked" />|
<label>rear stalls <input type="radio" name ="area"  value="rear stalls" />
<br /> <br />
Select Date and Time</br> <select  name ="date" size ="8"><br /> <br />
<option selected="selected" value="2012-04-01  12:00:00">01-04-2012  12:00</option>
<option value="2012-04-01  15:00:00">01-04-2012  15:00</option>
<option value="2012-04-01  18:00:00">01-04-2012  18:00</option>
<option value="2012-04-01  21:00:00">02-04-2012  21:00</option>
<option value="2012-04-02  12:00:00">02-04-2012  12:00</option>
<option value="2012-04-02  15:00:00">02-04-2012  15:00</option>
<option value="2012-04-02  18:00:00">02-04-2012  18:00</option>
<option value="2012-04-02  21:00:00">02-04-2012  21:00</option>
<option value="2012-04-03  15:00:00">03-04-2012  15:00</option>
<option value="2012-04-03  21:00:00">03-04-2012  21:00</option>
<option value="2012-04-04  15:00:00">04-04-2012  15:00</option>
<option value="2012-04-04  21:00:00">04-04-2012  21:00</option>
</select>
<br /><br />
<input type ="submit"  value="Show Seats" />
</form><br /> <br />
</div>
</body>
</html>

_END;



// security for sql injection
function sanitizeString($var)
{
$var = stripslashes($var);
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
function checkSet()
{
  return isset($_POST['title'],$_POST['area'],$_POST['date']);
}
/*function destroy_session_data()
{

$_SESSION = array();
if(session_id()!="" || isset($_COOKIE[session_name()]))
setcookie(session_name(),'',time()-2592000,'/');
session_destroy();
}*/
 
function checkSession(){
return isset($_SESSION['name'] );
}
function getFirword($longword)
{
if (!(empty($longword))){
$var =explode(' ',trim($longword ));
return $var[0];
}
return "";
}

?>
