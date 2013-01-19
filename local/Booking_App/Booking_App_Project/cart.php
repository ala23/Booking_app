<?php

$sessionId = session_id();
if (isset($_SESSION['name']))
$_SESSION['name'] == "" ? $username =$sessionId: $username=$_SESSION['name'];

function showCart($username,$conn)
{

			// display cart
			$sqltemp = " select row_no AS seat ,DATE(date_time)AS DATE,TIME(date_time) AS TIME ,area_name AS area ,title AS title ,price AS price
			from nwBooking where customer_name = '$username' ";	
  	      
 if ($res=$conn->query($sqltemp))
 {
		 
	$statement = $conn->prepare($sqltemp);
    $statement->execute();
    $count = $statement->rowCount();
 if ($count > 0)
  {
		  echo '<b>'."   YOUR BASKET " .'<b>'.'<br />'.'<br />';
		  echo "<table CELLPADDING=5 border=0> <tr bgcolor=gray border=0><th>SEAT</th><th>DATE</th> <th>TIME</th> <th>AREA</th>  <th> SHOW</th> <th>PRICE</th> ";
	       $cost=0;
		   $rowid=1;
		   $sqltemp = " select row_no AS seat ,DATE(date_time)AS DATE,TIME(date_time) AS TIME ,area_name AS area ,title AS title ,price AS price
			from nwBooking where customer_name = '$username' ";
	      foreach ($conn->query($sqltemp)as $row)	
	{
	$rowid++;
	 $cost += $row['price'];
	$originalDate = $row['DATE'];
	$timex =$row['TIME'];
	$seatx = $row['seat'];
	$areax =$row['area'];
	$titlex =$row['title'];
	$pricex =$row['price'] ;
    $newDate = date("d-m-Y", strtotime($originalDate));
      echo "<tr>";
	  echo " <td> $seatx </td> ";
	  echo " <td> $newDate </td> ";
	  echo " <td> $timex </td> ";
	   echo " <td> $areax </td> ";
	   echo " <td> $titlex</td> ";
	   echo " <td> $pricex </td> ";
	    
	  
	  echo " <td>".
	                 "<form action=\"remove.php\"  method=\"get\">". 
					  "<input type=\"hidden\"  name=\"seat\" value=". $row['seat'].">".
					  "<input type=\"hidden\"  name=\"area\" value=". $row['area'].">".
					  "<input type=\"hidden\"  name=\"price\" value=". $row['price'].">".
					  "<input type=\"hidden\"  name=\"title\" value=". $row['title'].">".
					  "<input type=\"hidden\"  name=\"date\" value=". $row['DATE'].">".
					  "<input type=\"hidden\"  name=\"time\" value=". $row['TIME'].">".
					  "<input type=\"submit\" name = $rowid value=\"REMOVE\" /></form>" ." </td>";
				echo "</tr>";	 
	}
	echo "</table>";
	setlocale(LC_MONETARY, 'en_GB');
	//echo money_format('%i', $cost) . "\n";
	echo "Total Cost for all Seats in Your Basket ".money_format('%i', $cost) . "\n";
	
	 
 echo <<<_END
         <br />
        Please Confirm Your Booking
        <br />
        <form method ="post" action="delivery.php">
		<input type ="submit"  value="Confirm Booking" />
       </form>
       <form method ="post" action="index.php#chooseseat">    
       <input type ="submit"  value="Add Seats" />
        </form>
       <form method ="post" action="cancel.php"> 
       <input type ="submit"  value="Cancel" />
      </form>
_END;
 
 }
    else 
	{	  
		    
			//delete record from cart
			$sql1 ="delete from nwBooking where customer_name = '$username' ";
            $st = $conn->prepare($sql1);
            $st->execute();
			echo " YOUR BASKET IS EMPTY";
 
	  echo "".'<br />' ." <form method =\"post\" action=\"index.php#chooseseat\"> ".
       "<input type =\"submit\"  value=\"Add Seats\" />".
        "</form>";
       
	  
     }
}
}
function formatDate($date)
{

 return  date("l F jS , Y - g:ia",strtotime($date));
}
?>