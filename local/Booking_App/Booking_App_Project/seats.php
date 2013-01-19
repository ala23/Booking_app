<html><title>Seats</title>
<ul><tab> | </tab> <a href =index.php>Home Page </a><tab> | </tab> <a href =index.php#chooseseat>Change Seating Area </a><tab> |</tab> </ul>
<body>
<?php

require_once 'login.php';
include 'menu.php';
ini_set('session.cache_limiter', 'private');

session_start();
$sessionId = session_id();
	
try {$conn = new PDO("mysql:host=$host;dbname=$dbname",$user, $pwd);
      if (checkset())
      {
         if(isset($_POST['title']) &&
     	 isset($_POST['date']) &&
		 isset($_POST['area']))
		 {
		    $title = sanitizeString($_POST['title']);    
		    $date = sanitizeString($_POST['date']); 		
		    $area= sanitizeString($_POST['area']); 
            
		   $_SESSION['title'] = $title;
		   $_SESSION['date'] = $date;
		   $_SESSION['area'] = $area;		 	
			$_SESSION['name'] = $sessionId;
		    $_SESSION['name'] == "" ? $username =$sessionId: $username=$_SESSION['name'];
		   
		   		   		   
		  
			
		 // first check number rows 
		   $sql = "SELECT COUNT(*)   
          from  seat , Performance, Production ,tarea
          where not exists (select Booking.row_no from Booking where Booking.row_no = seat.row_no) AND
          Performance.title = '$title' AND
          Performance.date_time= '$date'
          AND  seat.area_name = '$area' AND
          Production.title = Performance.title 
          AND seat.area_name = tarea.name  ";
		    
      
		  if ($res = $conn->query($sql)) 
		   {   
		       $count= 0;
			 
		      /* Check the number of rows that match the SELECT statement */
              if ($res->fetchColumn() > 0) 
			  {  
			     
				                 
		         /* actual SELECT statement */
                 $sql =  " select seat.row_no AS seat, seat.area_name AS area ,Performance.title  AS title ,DATE(Performance.date_time) AS date,TIME(Performance.date_time ) AS time ,(Production.basicPrice * tarea.price_multiplier ) AS price 
                 from  seat , Performance, Production ,tarea 
                 where not exists (select Booking.row_no from Booking where Booking.row_no = seat.row_no) AND
                 Performance.title = '$title' AND
                 Performance.date_time= '$date'
                 AND  seat.area_name = '$area' AND
                 Production.title = Performance.title 
                 AND seat.area_name = tarea.name limit 200 ";
		       
		         echo " Seats in the " .$area ." area " . " for ".$title. " show on " . formatDate($date). '<br />';
		         echo "".'Seat #' .'-'. "  area  " .'  -  '."  title  ".'   -   '."  date  " .'   -   '."  time  " .' - '."  price  ". '<br />';
				 echo"__________________________________________________________________________________________";
				  echo '<br />'.'<br />';
				  
                 foreach ($conn->query($sql) as $row) 
				  {
				     
                      print $row['seat'].' - '. $row['area'].' - '. $row['title'].' - '. $row['date'] .' - '. $row['time'] .' - '.'£'.$row['price'].'<br />';
					  $count++;
					  
					  
					 echo "<form action=\"booking.php\"  method=\"post\">".
					  "<input type=\"hidden\"  name=\"seat\" value=". $row['seat']."/>".
					  "<input type=\"hidden\"  name=\"area\" value=". $row['area']."/>".
					  "<input type=\"hidden\"  name=\"price\" value=". $row['price']."/>".
					  "<input type=\"hidden\"  name=\"title\" value=". $row['title']."/>".
					  "<input type=\"hidden\"  name=\"date\" value=". $row['date']."/>".
					  "<input type=\"hidden\"  name=\"date\" value=". $row['time']."/>".
					  "<input type=\"submit\" value=\"ADD TO CART\" /></form>";
					  echo '<br />';				  
					  
					  echo"_____________________________________________________________________________________";
					  echo '<br />'.'<br />';
					  }
				  
		          echo '<br />'. "There  are " .  $count . " seats avalaible ".'<br />';
				 	
		         }
			     else
			     {
			      print "No rows matched the query." .'<br />';				  
				  echo "<a href=index.php".'>' ." please check Theater Performance Times ".'</a> ';
				  die();
			
			     }
		   	
            }
		 
         }
	
	    }
	        else
            {
                   echo "please select time date and area for the performance" .'<br />';
	              echo "<a href=index.php".'>back '.'</a> ';
				  die();
            }
  }

 catch(PDOException $e) 
 {
 echo $e->getMessage();
 }

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
function formatDate($date)
{

 return  date("l F jS , Y - g:ia",strtotime($date));
}

function formatBritishDate($date)
{
 
return $newDate = date("d-m-Y", strtotime($date));
}
function foo($anArray)
 {
   if(empty($anArray))
   {
   
     return "empty";
   }
   foreach($anArray as $element)
   {
     echo $element;
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
  return isset($_POST['title'], $_POST['date'], $_POST['area']);
}
function queryMysql($query)
{
$result = mysql_query($query) or die (mysql_error());
return $result;
}
function getCount($count){
return $count;
}
?>
</body></html>