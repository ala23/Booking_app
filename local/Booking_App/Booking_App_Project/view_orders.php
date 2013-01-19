<html><title>MyOrders</title>
<ul><a href =index.php#chooseseat>Book Seats</a> <tab> | </tab> <a href =index.php>Home Page </a> </ul>
<body>

<?php
require_once 'login.php';
ini_set('session.cache_limiter', 'private');
session_start();
$sessionId = session_id();

try {$conn = new PDO("mysql:host=$host;dbname=$dbname",$user, $pwd);

      $email =$_SESSION['email'];
      $cus_Id= $_SESSION['cus_Id'];	
      $username =$_SESSION['username'];
      $name = $_SESSION['name'];
   if (!(empty($email)&& empty($cus_Id)))
  {
  printBooking($email,$cus_Id,$username,$name,$conn);
  }
  else{
  exit("hoops! not valid email / customer Id");
  }

}

catch(PDOException $e) 
 {
 echo $e->getMessage();
 }
function printBooking($email,$cus_Id,$username,$name,$conn)
{          echo '<br />'."<tr><td><b>  ORDERS HISTORY </b></td></tr>".'<br />'.'<br />';
           echo '<br />'."<tr><td><b>  SHOWING ALL YOUR BOOKING HISTORY MOST RECENT FIRST</b></td></tr>".'<br />'.'<br />';
           echo "<tr>Email : $email </tr>".'<br />';
		   echo "<tr>Username : $username </tr>".'<br />';
		   echo "<tr>Customer Name : $name </tr>".'<br />';
		   echo"<tr>Customer Id : $cus_Id </tr>".'<br />'.'<br />';
           $sqlorders="select order_Id ,trans_date from Orders where cus_Id ='$cus_Id'  order by order_Id DESC ";  
	       $STH = $conn->query($sqlorders); 
		  $STH->setFetchMode(PDO::FETCH_ASSOC);
		  while($row = $STH->fetch()) 
	    
		 {   $trans_date =$row['trans_date'];
		     $order_Id =$row['order_Id'] ;
		     echo "<tr><b>Order Date :". formatBritishDate($trans_date )."</b></tr>".'<br />'.'<br />';
		     echo "<tr>Order ID : $order_Id </tr>".'<br />'.'<br />';
			  
			 $sqlbooking=" select Booking.ticket_no AS ticket ,Booking.order_Id AS orderId, Performance.title AS title,DATE(Performance.date_time) AS date, 
			TIME(Performance.date_time) AS time, seat.area_name AS area, Booking.row_no AS seat , 
			(Production.basicPrice * tarea.price_multiplier ) AS price  from seat  
			inner JOIN  Booking ON  seat.row_no = Booking.row_no 
			left JOIN Performance ON  Booking.date_time  = Performance.date_time 
			left join Production ON  Performance.title = Production.title left join tarea ON  seat.area_name =tarea.name 
			where Booking.cus_Id ='$cus_Id' and Booking.order_Id = '$order_Id'
			GROUP BY Booking.ticket_no ,Performance.date_time order by Performance.date_time,Performance.date_time,seat.area_name "; 
			 if ($err = checkEmpty( $conn->query($sqlbooking)))
			 {  		     		      
			 echo "<table CELLPADDING=14 border=0><tr bgcolor=gray border=0 ><th>Order Id </th><th>Ticket No </th><th>Show</th><th>Show Date</th><th>Time</th><th>Area</th><th>Seat No</th><th>Price</th> ";
			 $cost=0;
			 foreach ($conn->query($sqlbooking)as $row)	
	       {  
		      $orderId = $row['orderId'];
		      $ticketx =$row['ticket'];
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
			echo " <td> $orderId</td> ";
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
		    echo "<table CELLPADDING=20><tr><th>Total Cost:</th><th></th><th></th><th>£</th><th>$formatcost</th> ";
		    echo "</table>";		  
			echo "".'<br />'.'<br />';
			}			  
		 
		 }  			
  }
   
 function formatBritishDate($date)
{
 
return $newDate = date("d-m-Y", strtotime($date));
}
function formatDate($date)
{

 return  date("l F jS , Y - g:ia",strtotime($date));
}
function checkEmpty($anArray)
 {
   if(empty($anArray))
   {
   
     return "empty ";
   }
  
   return true;
 }

?>
</body>
</html> 