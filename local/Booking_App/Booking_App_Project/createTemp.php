<?php

require_once 'login.php';


// will be use as temporal table  for cart

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
             
  
			
        $sql2="Alter table nwBooking add column area_name char(12) NOT NULL ,add column title varchar(32) NOT NULL,add column price decimal(5,2) NOT NULL";
		$st = $conn->prepare($sql2);
            $st->execute();
		    $count = $st->rowCount();
    	  //  print("Number of rows afffected $count rows.\n");

     }
	 
catch(PDOException $e) 
 {
 echo $e->getMessage();
 }
 try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname",$user, $pwd);
      if(!$conn)
	  { 
	   die('Could not connect: ' . mysql_error());
	  }
	  $sql3 = "CREATE TABLE IF NOT EXISTS Orders  (order_Id int NOT NULL AUTO_INCREMENT,
             cus_Id int  NOT NULL, email varchar(55) NOT NULL,trans_date  date  NOT NULL ,
             PRIMARY KEY (order_Id),
             FOREIGN KEY (cus_Id)
             REFERENCES Customer(cus_Id)         
                  on update cascade
                  on delete cascade)";
       
            $st = $conn->prepare($sql3);
            $st->execute();
		    $count = $st->rowCount();
    	   // print("Number of rows afffected $count rows.\n");	  
            			
        $sql4="CREATE TABLE IF NOT EXISTS Customer 
                    (  cus_Id int  NOT NULL AUTO_INCREMENT,name varchar(35) NOT NULL,
                       address varchar(25) NOT NULL,email varchar(25) unique NOT NULL,
                    PRIMARY KEY (cus_Id))";
		    $st = $conn->prepare($sql4);
            $st->execute();
		    $count = $st->rowCount();
    	  //  print("Number of rows afffected $count rows.\n");
		  
		  $sql5="Alter table Booking Add column cus_Id  int not null";
		  $st = $conn->prepare($sql5);
            $st->execute();
		    $count = $st->rowCount();
		
    	  //  print("Number of rows afffected $count rows.\n");
		  
		   $sql6=" Alter table Booking Add column order_Id  int not null";
		  $st = $conn->prepare($sql6);
            $st->execute();
		    $count = $st->rowCount();
    	  //  print("Number of rows afffected $count rows.\n");
		  
		   $sql7="ALTER TABLE Booking ADD  FOREIGN KEY(order_Id) 
		   references Orders(order_Id) on update cascade on delete cascade";
		  $st = $conn->prepare($sql7);
            $st->execute();
		    $count = $st->rowCount();
    	  //  print("Number of rows afffected $count rows.\n");
		  
		   $sql8="ALTER TABLE Booking ADD FOREIGN KEY(cus_Id) references Customer(cus_Id) on update cascade on delete cascade";
		  $st = $conn->prepare($sql8);
            $st->execute();
		    $count = $st->rowCount();
			
    	  //  print("Number of rows afffected $count rows.\n");
		   $sql9="  create table if not exists Logon 
                 (email varchar(25) NOT NULL,username varchar(25) NOT NULL UNIQUE,
                pwd varchar(250) NOT NULL ,cus_Id int NOT NULL , PRIMARY KEY (email), 
                FOREIGN KEY (cus_Id ) references Customer(cus_Id) on delete cascade on update cascade,
                index(email),
                index (username) )";
		  $st = $conn->prepare($sql9);
            $st->execute();
		    $count = $st->rowCount();
    	  //  print("Number of rows afffected $count rows.\n");
		   $sql5="";
		  $st = $conn->prepare($sql5);
            $st->execute();
		    $count = $st->rowCount();
    	  //  print("Number of rows afffected $count rows.\n");

     }
	 
catch(PDOException $e) 
 {
 echo $e->getMessage();
 }
 
 
 
 
 
 ?>