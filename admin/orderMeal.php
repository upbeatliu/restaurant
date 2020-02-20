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

$meal = new Meal;
$mealName =$meal->fetch_menu(); // show meal name and price from re_menu


$model = $nav->fetch_model();

if(isset($_POST['mealDate']) == true && isset($_POST['mealSession']) == true )
{   $mealdate = $_POST['mealDate'];
    $mealSession = $_POST['mealSession'];  
    $_SESSION['mealDate'] = $_POST['mealDate'];
    $_SESSION['mealSession'] = $_POST['mealSession'];   
}

if (isset($_POST['tableNum']) == true)
{
    $_SESSION['tableNum'] = $_POST['tableNum'];  
    if (isset($_POST['customerId']) == true)
    {
        $_SESSION['customerId'] = $_POST['customerId'][$_SESSION['tableNum']];
    }    
}

function MoneyNumber($number)
{
    return number_format((float)$number, 2, '.', '');
}

?>

<html>
   <head>
        <title> Yummy Restaurant</title>
        <link rel="stylesheet" href="../css/style.css"> 
        
        <script type="text/javascript">            
            function SelectionChanged()
            {                     
                var total = 0;
                var table = document.getElementById('mealTable');
                for (var i = 1 ; i < table.rows.length - 1 ; i++) 
                {
                    var row = table.rows.item(i);                    
                    var portion = row.cells.item(2).childNodes[1].value;                  
                    var price = row.cells.item(3).childNodes[1].value;
                    var rowTotal = price * portion;
                    total += rowTotal;
                }              
                
                if (isNaN(total) == true)
                {
                    alert("Only numbers please");
                }
                
                // display the total
                table.rows.item(table.rows.length - 1).cells.item(1).innerHTML = total;
            }
        </script>
        <script type="text/javascript">
      
       function ConfirmCancel()
       {
           if(confirm("Are you sure you want to cancel?"))
               {
                   return ture;
               }
               else
               {
                   return false;
               }
       }
</script>    
   </head>
   
<body>
	<div id="container">
           <div id="header"> <?php echo $model[0]['body'];  ?></div>      
                         
	   <div class="main">           
          <div class="orderMeal">
                 <br/>
                 <h4>Booking Meal--- Step <span>3</span> / 4</h4>
                <a href="adminIndex.php">&larr;Go Back Admin Main Page</a><br>
                <a href="tableNum.php">&larr;Go Back Previous Page</a>
                <p>Please select a Date and a Section to order meal below.</p>
                <form class="form" name="bookingMeal" method="post" action="ordered.php">
                    <table id ="mealTable" width="600" border="1" cellpadding="4">
                          <tr>
                            <td width="72" align="center" bgcolor="#999999">Menu Id</td>                             
                            <td width="303" align="center" bgcolor="#999999">Meal Name</td>
                            <td width="67" align="center" bgcolor="#999999">Portion(s)</td>
                            <td width="60" colspan="2" align="center" bgcolor="#999999">Price ($)</td>
                          </tr>
                          
  
                          <?php
                          // display the table, each row is a different menu item
                          for ($row = 0 ; $row < count($mealName) ; $row++)
                          {
                          echo '
                          <tr>                              
                          <td align="center"> <input readonly id ="menuIds[]" name ="menuIds[]" size="2" value="';
                          echo $mealName[$row]['menuId'] ,'" style="border: none"></td>';
                          echo '<td align="center">';
                          echo $mealName[$row]['mealName'];
                          echo '</td>
                                <td align="center"> <input type= "number" step = "1" id="portions[]" size="4" name="portions[]"   value="0" onkeyup="SelectionChanged()" > </td>
                                <td align="center"> <input readonly       id="prices[]"   size="4" name="prices[]"    value="';
                          echo MoneyNumber($mealName[$row]['price']);
                          echo '" style="border: none"></td></tr>';
                          }
                          
                          // display the total row                          
                          echo 
                          ' <tr>
                            <td colspan="3" align="right"><h3>Total</h3></td>
                            <td width="144" align="center">&nbsp;</td>
                            </tr>                          
                          ';             
                          ?>
                      </table>
                        
                  <input type="submit" value="Booking Meal" style="position: absolute; left: 700px; top: 630px;">                   
                </form>
                <form id="formC" name="cancel" method="get" style="position: absolute; left: 630px; top:630px;" action="adminIndex.php?cancel=" onsubmit="return ConfirmCancel();">
                <input name="submit" type="submit" value="cancel" />
                </form>
           </div> 
	    </div><!---end of main-->
	    <div id="footer"><?php echo $model[1]['body']; ?></div>
	</div><!--end of container-->
</body>
</html>