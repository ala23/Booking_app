<?php
//reponsible  cart with booking details allow to remove items from cart
require_once 'login.php';
include 'cart.php';
include 'navigation.php';
session_start();
$sessionId = session_id();
try {$conn = new PDO("mysql:host=$host;dbname=$dbname",$user, $pwd);
 if (isset($_SESSION['name']))
 {
   $username = $_SESSION['name'];
   showCart($username,$conn);
  }
 else 
 {
   $username=$sessionId;
   showCart($username,$conn);
 
 }
}		
catch(PDOException $e) 
 {
 echo $e->getMessage();
 }
 ?>