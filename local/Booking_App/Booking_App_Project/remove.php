<?php
require_once 'login.php';
require_once 'createTemp.php';
// require_once 'booking.php';
include 'cart.php';
include 'navigation.php';
/* ini_set('session.cache_limiter', 'private'); */
session_start();
$sessionId = session_id();

try {$conn = new PDO("mysql:host=$host;dbname=$dbname",$user, $pwd);
         if (isset($_SESSION['name']))
		 {	
         $username = $_SESSION['name'];		 
		  //if ( checkGetSet())
		  
		 if(isset($_GET['seat']) && isset($_GET['area']) && isset($_GET['price']) && isset($_GET['title']) && isset($_GET['date']))
		{
		 
         $seat = $_GET['seat'];
		 $area = $_GET['area'];
		 $price =$_GET['price'];
		 $title = $_GET['title'];
		 $date =$_GET['date'];
		 $time =$_GET['time'];     
		 $datetime =$date." ".$time;
			    			 	
		   /*remove the seat booking from  cart    */
		     
			$sql1 ="delete from nwBooking where row_no ='$seat' AND title LIKE '$title%' AND date_time LIKE '%$datetime'  ";
            $st = $conn->prepare($sql1);
            $st->execute();
		    $count = $st->rowCount();
			if($count>0)
			{
			echo " Seat  No: ". $seat .  " for " . $title ."  Performance on ".formatDate($datetime). " was removed from your cart " ;
			echo "".'<br />'.'<br />';   
            showCart($username,$conn);
			//unset ($seat) ;
			}
			}
		    
						
		 }
			 	 		        
     else {          			    
		     	
		   echo " unable to delete from cart";
		}
		
		   echo " go to home page for booking ";

	  }
	
		 
             
       
catch(PDOException $e) 
 {
 echo $e->getMessage();
 }
 
  echo("<form method=\"post\" action=\"index.php\">\n");
            
            echo("</form>\n");

// security for sql injection

  function checkSessionName(){
return isset($_SESSION['name'],$_SESSION['address']);
}
 function checkGetSet(){
  return isset($_GET['seat'],$_GET['area'],$_GET['price'],$_GET['title'],$_GET['date']);
}

function droptable($table,$conn)
{
$sql1 = "drop table $table ";
             $st = $conn->prepare($sql1);
            $st->execute();		
		
 }


?>
