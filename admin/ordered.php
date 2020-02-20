<?php
session_start();
include_once('../inc/connection.php');
include_once('../inc/function.php');
include_once('../inc/databaseFunction.php');
$nav = new Nav;

$date = new Meal;
$dates = $date->Date(); // distinct date from re_booking table
$time = new Meal;
$times = $time->session();// distinct session from re_booking table

$model = $nav->fetch_model();

$id = 0;


?>
<html>
   <head>
        <title> Yummy Restaurant</title>
        <link rel="stylesheet" href="../css/style.css">    
   </head>
   
<body>
	<div id="container">
           <div id="header"> <?php echo $model[0]['body'];  ?></div>      
                         
	   <div class="main">           
          <div class="orderMeal">
                 <br/>
                 <h4>Booking Meal--- Step <span>4</span> / 4</h4>
                <a href="adminIndex.php">&larr;Go Back Admin Main Page</a><br>
                <a href="orderMeal.php">&larr;Go Back Previous Page</a>
                <?php 
                                                      
$meal = new Meal();
$session = $_SESSION["mealSession"];
$selectedDate = $_SESSION["mealDate"];
$customerId = $_SESSION["customerId"];
$tableNumber = $_SESSION["tableNum"];
$menuIds =  $_POST["menuIds"];
$portions = $_POST["portions"];
$prices = $_POST["prices"];

$meal->bookingMeal($session, $selectedDate, $customerId, $tableNumber, $menuIds, $portions, $prices);
echo "<br><br>","<h3>Thank you for booking meal(s).</h3>";
echo "You have booked meal at ",$session, " on ", $selectedDate,"<br>";
echo "Table Number : ",$tableNumber;
?>
                    
           </div> 
	    </div><!---end of main-->
	    <div id="footer"><?php echo $model[1]['body']; ?></div>
	</div><!--end of container-->
</body>
</html>