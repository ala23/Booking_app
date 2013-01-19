<html><title>Receipt</title>
 <div class="navigation"><ul><tab> | </tab> <a href =index.php>Home Page</a><tab> | </tab> <a href =index.php#chooseseat>Continue Shopping</a><tab> | 
 </tab><a href =mail.php>Contact Us</a></ul></div>
<body>
<?php
require_once 'login.php';
session_start();
$fromEmail="ala23@kent.ac.uk";
$toEmail=$_SESSION['email'];   
ini_set('session.cache_limiter', 'private');
$sessionId = session_id();
 
try { $conn = new PDO("mysql:host=$host;dbname=$dbname",$user, $pwd);
 
       		$name = $_SESSION['name'];
	     		      
		          $email=$_SESSION['email'];     
                          $orderId = $_SESSION['orderId'];			   
			  $cusId =  $_SESSION['cusId'] ;
			  $cusName=$_SESSION['validated_name'];
			  $cusAddress =$_SESSION['validated_address'];
			 
					/* print receipt */
		    		    
			// print name and shipping address printing from database to ensure we have right  details stored
			echo '<b>'."NAIVE THEATRE BOOKING RECEIPT ".'</b>' .'<br />'.'<br />';
			echo "Order Date: " .date("d-m-Y").'<br />'.'<br />';			
			$sql3 = "select customer_name AS name ,address  AS address ,cus_Id AS  cusId , order_Id AS orderId  from Booking 
			where customer_name ='$name' and order_Id = '$orderId' group by customer_name ";
			
			
	        if (($conn->query($sql3)))
			 foreach ($conn->query($sql3)as $row)	
	       {  $namex =$row['name'];
	        $addressx = $row['address'];
		$_SESSION['name'] =$namex;
		$_SESSION['address']= $addressx ;
		
            $cusId = $row['cusId'];
            $orderId = $row['orderId'];
             echo"<tr><b>Customer No : </b>". $cusId."</tr>".'<br />'.'<br />';
             echo"<tr><b>Order No : </b>". $orderId."</tr>".'<br />'.'<br />';			
             echo"<tr><b>Delivery Address : </b>".'<br />'; 
			 echo"<tr> ".$namex ."</tr>".'<br />';
	                 echo"<tr>".$addressx."</tr>".'<br />'.'<br />';			 
			 echo"<tr><b> Billing Address: </b>".'<br />'; 
			 echo "" .$cusnamex = $_SESSION['validated_name'].'<br />';
			 echo "".$cusaddressx = $_SESSION['validated_address'].'<br />';
             echo"<tr><b>Email : </b>". $email."</tr>".'<br />'.'<br />';				 
	        
			}
			
			echo "".'<br />';				
													
			// print  booking for this order by this customer
		    $sql4=" select Booking.ticket_no AS ticket ,Performance.title AS title,DATE(Performance.date_time) AS date, TIME(Performance.date_time) AS time,
			seat.area_name AS area, Booking.row_no AS seat , (Production.basicPrice * tarea.price_multiplier ) AS price 
			from seat  inner JOIN  Booking ON  seat.row_no = Booking.row_no  left JOIN Performance ON  Booking.date_time  = Performance.date_time 
			left join Production ON  Performance.title = Production.title left join tarea ON  seat.area_name =tarea.name 
			where Booking.customer_name ='$name' AND order_Id ='$orderId' 
			GROUP BY Booking.ticket_no ,Performance.date_time order by Performance.date_time,Performance.date_time,seat.area_name";   
			if (($conn->query($sql4)))
		   {
			 echo "<table CELLPADDING=14  border=0>  <tr bgcolor= gray > <th>Ticket No </th><th>Show</th><th>Date </th><th>Time</th><th>Area</th><th>Seat No</th><th>Price</th> ";
			 $cost=0;
			 foreach ($conn->query($sql4)as $row)	
	       {  $ticketx =$row['ticket'];
	          $titlex = $row['title'];
			  $datex = $row['date'];
			  $timex = $row['time'];
			  $areax = $row['area'];
			  $seatx = $row['seat'];
			  $pricex = $row['price'];
			  $formatdate=formatBritishDate($datex);
			  $formatpricex= sprintf("%01.2f",$pricex);
              $cost+= $pricex;
              $formatcost= sprintf("%01.2f",$cost);			  
            echo "<tr>";
	        echo " <td> $ticketx</td> ";
	        echo " <td> $titlex</td> ";  
            echo " <td> $formatdate</td> ";
            echo " <td> $timex </td> ";
            echo " <td> $areax </td> ";
            echo " <td> $seatx </td> ";
            echo " <td>$formatpricex</td> ";			
	        echo "</tr>";
			
			}
			echo "</table>";
			echo "".'<br />';
		    echo "<table CELLPADDING=20  > <tr ><th>Total Cost :</th><th></th><th></th><th></th><th>£</th><th>$formatcost</th></tr> ";
		    echo "</table>";		  
			echo "".'<br />';
			if (send_email($conn,$toEmail,$fromEmail,$orderId,$cusName,$cusAddress ))
			echo " Order Confirmation Email has been sent your email address.".'<br />'.'<br />';
           	echo '<b>'."THANKS FOR YOUR CUSTOM ..ENJOY YOUR SHOW ".'</b>';
			//send_email($conn,$email,$fromEmail,$orderId ,$cusName,$cusAddress );
			
			
	        }		
               
	 } 

					  
catch(PDOException $e) 
 {
 echo $e->getMessage();
 }
	/*echo("<form method=\"post\" action=\"printInvoice.php\">\n");
            echo("<input type=\"submit\"value=\"Print Receipt\">\n");
            echo("</form>\n");*/
			
		// security for sql injection
		
function send_email($conn,$toEmail,$fromEmail,$orderId,$cusnamex,$cusaddressx )
{
$name = $_SESSION['name'];
$to    = $toEmail;
$deliveryName=$_SESSION['name'] ;
$deliveryAddress=$_SESSION['address'];
$subject = "Booking Confirmation  Order No ". $orderId ;
$headers = "From: " . $fromEmail . "\r\n" ;
$headers.=   "Reply-To: ". $fromEmail . "\r\n" ;   
 //   'X-Mailer: PHP/' . phpversion();
$headers .= "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
 $message="";
    $message .= "<html>";
    $message .= "<h2>NaiveTheatre.com</h2>";
    $message .= "Order Date: " .date("d-m-Y")."<br>";
    $message .= "Order No:". $orderId ." <br>";
    $message .= "<br>";
    $message .= "<b> Delivery Addess</b>"."<br>";
    $message .= $deliveryName."<br>";
    $message .= $deliveryAddress."<br>";
    $message .= "<br>";
    $message .= "<b> Billing Addess</b>"."<br>";
    $message .= $cusnamex ."<br>";
    $message .= $cusaddressx ."<br>"."<br>";
	$message .= "<p>";
	$message .= "Hi " .getFirword($cusnamex);
	$message .= "</p>"."<br>";
    $message .=  "<p>";	
    $message .= "Thank you for your order. We appreciate your shopping at ";
    $message .= "Naivetheatre.com .If you have any questions about your order, ";
    $message .= "please email us at ";
    $message .= "<a href=mailto:$fromEmail>orders@naivetheatre.com</a> ";
    $message .= "and reference the order number listed above.Below is your order details: ";
    $message .= "</p>"."<br>";
   
// print  booking for this order by this customer
		    $sql4=" select Booking.ticket_no AS ticket ,Performance.title AS title,DATE(Performance.date_time) AS date, TIME(Performance.date_time) AS time,
			seat.area_name AS area, Booking.row_no AS seat , (Production.basicPrice * tarea.price_multiplier ) AS price 
			from seat  inner JOIN  Booking ON  seat.row_no = Booking.row_no
			left JOIN Performance ON  Booking.date_time  = Performance.date_time 
			left join Production ON  Performance.title = Production.title
			left join tarea ON  seat.area_name =tarea.name 
			where Booking.customer_name ='$name' AND order_Id ='$orderId' 
			GROUP BY Booking.ticket_no ,Performance.date_time order by Booking.ticket_no ,Performance.date_time,Performance.date_time,seat.area_name";   
			if (($conn->query($sql4)))
		   {
		    $message = $message ."<table CELLPADDING=3  border=0>  <tr bgcolor= gray > <th>Ticket No </th><th>Show</th><th>Date </th><th>Time</th><th>Area</th><th>Seat No</th><th>Price</th> ";
			 $cost=0;
			 foreach ($conn->query($sql4)as $row)	
	       {  $ticketx =$row['ticket'];
	          $titlex = $row['title'];
			  $datex = $row['date'];
			  $timex = $row['time'];
			  $areax = $row['area'];
			  $seatx = $row['seat'];
			  $pricex = $row['price'];
			  $formatdate=formatBritishDate($datex);
			  $formatpricex= sprintf("%01.2f",$pricex);
              $cost+= $pricex;
              $formatcost= sprintf("%01.2f",$cost);			  
            $message = $message ."<tr>";
	    $message = $message ." <td> $ticketx</td> ";
	    $message = $message . " <td> $titlex</td> ";  
            $message = $message . " <td> $formatdate</td> ";
            $message = $message . " <td> $timex </td> ";
            $message = $message . " <td> $areax </td> ";
            $message = $message . " <td> $seatx </td> ";
            $message = $message . " <td>$formatpricex</td> ";			
	        $message = $message ."</tr>";
			
			}
			$message = $message . "</table>";
			$message = $message . '<br />';
		    $message = $message . "<table CELLPADDING=3  > <tr ><th>Total Cost :</th><th></th><th></th><th></th><th>£</th><th>$formatcost</th></tr> ";
		    $message = $message . "</table>";		  
			$message = $message . '<br />';
           	$message = $message . '<b>'."THANKS FOR YOUR CUSTOM ..ENJOY YOUR SHOW !".'</b>';
	        }
         $message=$message . "</html>" ;
		 
		 //send email
	if (mail($to, $subject, $message, $headers))
	{
	 return true;
	}
	return false;
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
return isset($_SESSION['name'],$_SESSION['address']);
}
 function checkSet(){
  return isset($_POST['name'],$_POST['address']);
}
function queryMysql($query)
{
$result = mysql_query($query) or die (mysql_error());
return $result;
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
</body></html>
			