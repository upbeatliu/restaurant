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
if(isset($_GET['id']))
{
   $id = preg_replace("[^0-9]", "", $_GET['id']);
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
                 <h4>Booking Meal--- Step <span>1</span> / 4</h4>
                <a href="adminIndex.php">&larr;Go Back Admin Main Page</a>
                <p>Please select a Date and a Section to order meal below.</p>
                <form class="form" name="mealDateTime" method="post" action="tableNum.php">
                    <label>Date</label>
                    <select name="mealDate">
<!--                        <option selected="selected" value="">--</option>-->
                        <?php foreach ($dates as $date)// distinct date from re_booking table
                           { ?>
                        <option value="<?php echo $date['date']; ?>"><?php echo $date['date'],"<br>"; ?></option>
                        <?php  
                           } ?>
                    </select>
                    <br><br><br>
                    <label>Session</label>
                    <select name="mealSession">
<!--                        <option selected="selected" value="">--</option>-->
                         <?php
                           foreach ($times as $time)// distinct session from re_booking table
                           {?>
                         <option value="<?php echo $time['session']; ?>"><?php echo $time['session'],"<br>"; ?></option>
                        <?php  
                           } ?>
                    </select>      
                 <input type="submit" value="Selected Date & Session" style="position:absolute; left: 650px; top: 380px;">
                </form> 
           </div> 
	    </div><!---end of main-->
	    <div id="footer"><?php echo $model[1]['body']; ?></div>
	</div><!--end of container-->
</body>
</html>