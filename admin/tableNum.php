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
$tableNum = new Meal;
$selectName = new Meal;
// list all table on that date

$model = $nav->fetch_model();

$table = new Table_heading;
$form = new form;

if(isset($_POST['mealDate']) == true && isset($_POST['mealSession']) == true )
{   $mealdate = $_POST['mealDate'];
    $mealSession = $_POST['mealSession'];  
    $_SESSION['mealDate'] = $_POST['mealDate'];
    $_SESSION['mealSession'] = $_POST['mealSession'];   
}

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
                 <h4>Booking Meal--- Step <span>2</span> / 4</h4>                 
                     <a href="adminIndex.php">&larr;Go Back Admin Main Page</a><br>
                     <a href="dateTime.php">&larr;Go Back Previous Page</a>                               

              <p>Please select the table number below and check customer name.</p>
              
              <div class="mealTable"> 
                <form class="form" name="mealDateTime" method="post" action="orderMeal.php"> 
                         <table  width="680" border="1" cellpadding="2">
                                <tr>
                                  <td width="118">Table Number</td>
                                  <td width="1"></td>
                                  <td width="218">Customer Name</td>
                                  <td width="92">Adult</td>
                                  <td width="105">Children</td>
                                </tr>                                
                     <?php $tableNums = $tableNum->selectTableNum($mealdate, $mealSession);                      
                       
                          foreach ($tableNums as $tableNum)                         
                    {?> 
                              <tr>
                              <td><?php echo $tableNum['tableNumber']; ?><input name="tableNum" type="radio" value="<?php echo $tableNum['tableNumber']; ?>"></td>
                                  <td><input name="customerId[]" type="hidden" value="<?php echo $tableNum['customerId']; ?>"</td>
                                  <td><?php echo $tableNum['firstname']," ",$tableNum['lastname']; ?></td>
                                  <td><?php echo $tableNum['adults']; ?></td>
                                  <td><?php echo $tableNum['children']; ?></td>
                              </tr>
                     <?php } ?>
                              </table> 
                 <input type="submit" value="Selected Table" style="position:absolute; left: 650px; top: 647px;">
                </form>
              </div> 
           </div> 
	    </div><!---end of main-->
	    <div id="footer"><?php echo $model[1]['body']; ?></div>
	</div><!--end of container-->
</body>
</html>