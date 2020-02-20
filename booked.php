<?php
session_start();
include_once('inc/connection.php');
include_once('inc/function.php');
include_once('inc/databaseFunction.php');
$nav = new Nav;
$navs = $nav-> fetch_all();
    if(isset($_GET['id']))
    {
            $id = preg_replace("[^0-9]", "", $_GET['id']);
    }
    else
    {
        $id = 0;
    }
$data = $nav->fetch_data($id);
$model = $nav->fetch_model();
?>
<!DOCTYPE HTML>
<html>
   <head>
   <meta charset="utf-8">
        <title> Yummy Restaurant</title>
        <link rel="stylesheet" href="css/style.css">
   </head>
<body>
	<div id="container">
     <div id="header">
	   <?php echo $model[0]['body'];  ?>
     </div>
	   <div class="structure">
	      <nav>
	        <ul>
                <?php foreach ($navs as $nav)
                    if (isset($_SESSION["customerId"]) == true && ($nav['menu_name'] != "Log In" && $nav['menu_name'] != "Sign Up"))
                    {
                      { ?>
                      <li class="selected">
                      	<a href="memberIndex.php?id=<?php echo $nav['id']; ?>">
                          <?php echo $nav['menu_name']; ?>
                        </a>                          
                      </li>
                <?php } 
                    }?>
                      <div class="memberBtn"><a href="booking.php">Booking Table</a></div>                      
                      <div class="memberBtn"><a href="logout.php">Logout</a></div>
                                   

                  <p style="color:#999;  padding:0px; border: 1px dotted #999;">
                      <?php echo "Hi! You have logged in.";                   
                          echo ("<br>");
                          echo("ID: " . $_SESSION['customerId'] . "<br>");
                          echo("User Level: " . $_SESSION['userlevel'] . "<br>");
                          echo("User Name: " . $_SESSION['username'] . "<br>"); 
                      ?></p>
        	</ul>            
		  </nav>
		<div class="content">
<?php 
$booking = new Booking();
$session = $_SESSION["session"];
$selectedDate = $_SESSION["selectedDate"];
$adults = $_SESSION["adults"];
$children = $_SESSION["children"];
$tableSelected = $_SESSION["previousSelection"];
$customerId = $_SESSION["customerId"];
$Booking=$_POST['submit'];
if(isset($Booking)==true)
{
        $booking->MakeBooking($selectedDate, $session, $adults, $children, $tableSelected, $customerId);
        $booking->ClearBooking();
        echo "<h4>Booked Result</h4><br><p>Thank you for booking.</p>";
}
else 
 {
    echo 'something wrong.';
 }
?>			    	
	        </div><!---end of content-->
	    </div><!---end of structure-->
	<div id="footer"><?php echo $model[1]['body']; ?></div>
  </div><!--end of container--> 
</body>
</html>
