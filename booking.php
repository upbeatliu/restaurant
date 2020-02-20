<?php
session_start();
include_once('inc/connection.php');
include_once('inc/function.php');
$nav = new Nav;
$navs = $nav-> fetch_all();
if(isset($_GET['id']))
{
	$id = preg_replace("[^0-9]", "", $_GET['id']);
}
else
{
    $id = 1;
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
 <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

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
                    if(isset($_SESSION["customerId"]) == true && ($nav['menu_name'] != "Log In" && $nav['menu_name'] != "Sign Up"))
                    {
                      { ?>
                      <li class="selected">
                      	<a href="memberIndex.php?id=<?php echo $nav['id']; ?>">
                          <?php echo $nav['menu_name']; ?>
                        </a>                           
                      </li>
                <?php }
                    }?>                
                   <div class="memberBtn"><a href="logout.php">Logout</a></div>   
        	</ul>
                  
	      </nav>
            <div class="content">
            <div class="Box">
              <div class="BoxWTop"><h3>Table Booking</h3></div>
             <div class="BoxMiddle">
         <form class="Form" name="Booking" method="post" action ="tableLayout.php">
         <input type="hidden" value="1" name="rbSearch">

         <p>Date: <input type="text" id="SelectedDate" name="SelectedDate" /></p>  
         <p id="rbTimeBox">
         <label class="rbLabel">Hours&nbsp;</label>
         <select id="session" class="session" name="session">
         <option selected ="selected"value="6-8">6-8</option>         
         <option value="8-10">8-10</option>
         </select>
         </p>
         <p>
         <label class="rbLabel">Adults</label>
         <select id="Adults" class="rbSelect"  name="Adults">
         <option selected ="selected"value="1">1</option>         
         <option value="2">2</option>
         <option value="3">3</option>
         <option value="4">4</option>
         <option value="5">5</option>
         <option value="6">6</option>
         <option value="7">7</option>
         <option value="8">8</option>
         <option value="9">9</option>
         <option value="10">10</option>
         <option value="11">11</option>
         <option value="12">12</option>
         <option value="13">13</option>
         <option value="14">14</option>
         <option value="15">15</option>
         <option value="16">16</option>
         <option value="17">17</option>
         <option value="18">18</option>
         <option value="19">19</option>
         <option value="20">20</option>
         </select>
         </p>
         <p>
         <label class="rbLabel">Children</label>
         <select id="Children" class="rbSelect"  name="Children">
         <option selected ="selected"value="">--</option>
         <option value="1">1</option>
         <option value="2">2</option>
         <option value="3">3</option>
         <option value="4">4</option>
         <option value="5">5</option>
         <option value="6">6</option>
         <option value="7">7</option>
         <option value="8">8</option>
         <option value="9">9</option>
         <option value="10">10</option>
         <option value="11">11</option>
         <option value="12">12</option>
         <option value="13">13</option>
         <option value="14">14</option>
         <option value="15">15</option>
         <option value="16">16</option>
         <option value="17">17</option>
         <option value="18">18</option>
         <option value="19">19</option>
         <option value="20">20</option>
         </select>
         </p>
         <input type="submit" value="Make Booking" style="position: absolute; left: 180px; top: 220px;">      
         <input type="submit" name="Delete Booking" value="Delete Booking" style="position: absolute; left: 300px; top: 220px;" /> 
         </form>
                 
         <form id="formC" name="cancel" method="get" style="position: absolute; left: 110px; top: 220px;" action="memberIndex.php" >
         <input name="submit" type="submit" value="cancel" />
         </form>           
             </div>
            </div>
         </div>
      </div><!---end of structure-->  
  	    <div id="footer"><?php echo $model[1]['body']; ?></div>
	</div><!--end of container-->   
        <script>
            $(function() 
            {              
                $( "#SelectedDate" ).datepicker({ dateFormat: "yy/mm/dd", minDate: 0, maxDate: 30 }).datepicker("setDate", "0");
            });      
        </script>        
        
</body>
</html>

