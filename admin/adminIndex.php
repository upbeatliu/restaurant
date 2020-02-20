<?php
session_start();
include_once('../inc/connection.php');
include_once('../inc/function.php');
include_once('../inc/databaseFunction.php');
$nav = new Nav;
$model = $nav->fetch_model();
$date = new Meal;
$dates = $date->Date(); // distinct date from re_booking table
?>
<html>
   <head>
        <title> Yummy Restaurant</title>
        <link rel="stylesheet" href="../css/style.css">
   </head>
<body>
	<div id="container">
		   <div id="header">
		   <?php echo $model[0]['body'];  ?>
	       </div>
	   <div class="main">        	
	            <br/><br/>
                <div class="form_area">          
              <table width="800" border="0" cellpadding="2">
                  <tr>
                    <td colspan="2"> <h4>Admin Control Panel</h4></td>
                  </tr>                  
                  <tr>
                    <td colspan="2" bgcolor="#FFFFFF">
                    <ul>
                      <li><a href="add.php">Add New Content Page</a></li>
                      <li><a href="edit.php">Edit / Delete Existing Content Page</a></li>
                      <li><a href="editmodel.php">Edit Existing Header or Footer Page</a></li>
                    </ul></td>
                  </tr>
                  <tr>
                    <td width="239" rowspan="3" bgcolor="#FFD1A4">                                        
                     
                    <li style="width: 120px; margin-left:66px; line-height: 30px;"><a href="dateTime.php">Booking Meal <br/>for Customer</a></li></td>
                    <td bgcolor="#FFFFCC">
                    <div id="report">
                    <form class="form" name="reportDate" method="get" action="report.php">
                    <label>Date</label>
                    <select name="reportDate">
                    <!-- <option selected="selected" value="">--</option>-->
                        <?php foreach ($dates as $date)// distinct date from re_booking table
                           { ?>
                        <option value="<?php echo $date['date']; ?>"><?php echo $date['date'],"<br>"; ?></option>
                        <?php  
                           } ?>
                    </select>
                    <br/></br/>
                    <label>Report Type</label>
                    <br/></br/>
                        <input type="radio" name="id" id="1" value="1">
                        <label> Daily Report (People) &nbsp;</label>
                        <input type="submit" value="Submit(People)">
                        <br/></br/>
                        <input type="radio" name="id" id="2" value="2">
                        <label>Daily Report (Income)</label> 
                        <input type="submit" value="Submit(Income)">
                        <br/></br/>                        
                        <input type="radio" name="id" id="3" value="3">
                        <label>Weekly Report</label> 
                        <input type="submit" value="Weekly Report">
                        <br/></br/>                        
                        <input type="radio" name="id" id="4" value="4">
                        <label>Monthly Report</label> 
                        <input type="submit" value="Monthly Report">                        
                     </form>
                    </div> 
                    </td>
                  </tr>
                  <tr>
                    <td bgcolor="#FFFFCC"><ul><li><a href="voucher.php">Generate a Discount Voucher</a></li></ul></td>
                  </tr>
                  
                  <tr>
                    <td colspan="2">
                    <ul>
                      <li><a href="../index.php" target="_blank">Check Public index Page</a></li>                
                      <li><a href="logout.php">Logout</a></li>
                    </ul></td>
                  </tr>
                </table>
                    <?php 
//                      echo ("<br>");
//                      echo("ID: " . $_SESSION['customerId'] . "<br>");
//                      echo("User Level: " . $_SESSION['userlevel'] . "<br>");
//                      echo("User Name: " . $_SESSION['username'] . "<br>");
                    ?>
                </div> 
                <br/><br>              
      </div>
	   <!---end of structure-->
	    <div id="footer"><?php echo $model[1]['body']; ?></div>
	</div><!--end of container-->
</body>
</html>
