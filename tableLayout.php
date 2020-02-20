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
    $id = 1;
}
$data = $nav->fetch_data($id);
//$name = $nav->fetch_name($_SESSION);
$model = $nav->fetch_model();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title> Yummy Restaurant</title>
  <link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/style.css">

<script type="text/javascript">
       function ConfirmBooking(totalPeople,tableSelected)
       {
           if(confirm("Number of people: "+ totalPeople+ " ?" + "  Table number: "+tableSelected+" ?"))
               {
                   return ture;
               }
               else
               {
                   return false;
               }
       }
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
                           <div class="memberBtn"><a href="booking.php">Booking</a></div>
                           <div class="memberBtn"><a href="logout.php">Logout</a></div>                              
                        </ul>
                          
                      </nav>
                    <div class="content">
                        <div id="tables">
                        <?php  
                        $session = null;
                        $selectedDate = null;                                                
                        $adults = null;                         
                        $children = null;
                        $booking = new Booking();
                        $customerId = $_SESSION["customerId"];          
               
                        if (array_key_exists("submit", $_GET) == true && $_GET["submit"] == "cancel")
                        {
                            $booking->ClearBooking();
                            header('Location: memberIndex.php');
                        }
                        if (array_key_exists("session", $_POST) == true) // first time load
                        {
                            $session = $_POST["session"];
                            $_SESSION["session"] = $session;
                        }
                        else // after first load
                        {
                            $session = $_SESSION["session"];
                        }                      
                        if (array_key_exists("SelectedDate", $_POST) == true) // first time load
                        {
                            $selectedDate = $_POST["SelectedDate"];
                            $_SESSION["selectedDate"] = $selectedDate;
                        }
                        else // after first load
                        {
                            $selectedDate = $_SESSION["selectedDate"];
                        }                                 
                        if (array_key_exists("Adults", $_POST) == true) // first time load
                        {
                            $adults = $_POST["Adults"];
                            $_SESSION["adults"] = $adults;
                        }
                        else // after first load
                        {
                            $adults = $_SESSION["adults"];
                        }                        
                        if (array_key_exists("Children", $_POST) == true) // first time load
                        {
                            $children = $_POST["Children"];
                            $_SESSION["children"] = $children;
                        }
                        else // after first load
                        {
                            $children = $_SESSION["children"];
                        }       
                        $totalPeople = $adults + $children;                        
                        // if delete booking
                        if (array_key_exists("Delete_Booking", $_POST) == true && $_POST["Delete_Booking"] == "Delete Booking")
                        {                        
                            header('Location: bookingCancelled.php');
                        }
                        $initialized = $booking->HasSessionBeenInitialized($selectedDate, $session);
                        if ($initialized == false)
                        {
                            $booking->InitializeSession($selectedDate, $session, 40, 2);
                        }                        

                        $tableSelected = null;
                        $tableJoinedCount = null;
                        $previousSelection = null;
                        $previousJoinedCount = null;
                        if (array_key_exists("tableId", $_GET) == true)
                        {
                            $tableSelected = $_GET['tableId'];
                            $tableJoinedCount = $_GET['joinedCount'];
                            
                            if (array_key_exists("previousSelection", $_SESSION) == true && array_key_exists("previousJoinedCount", $_SESSION) == true)
                            {
                                $previousSelection = $_SESSION["previousSelection"];
                                $previousJoinedCount = $_SESSION["previousJoinedCount"];
                            }
                            
                            $_SESSION["previousSelection"] = $tableSelected;
                            $_SESSION["previousJoinedCount"] = $tableJoinedCount;                                                        
                        }    
                      
                        $allBookings = null;
                        if (array_key_exists("allBookings", $_SESSION) == false) // first time the page is loaded
                        {   
                            $allBookings = $booking->GetSession($selectedDate, $session);  
                            $_SESSION["allBookings"] = $allBookings;
                        }
                        else // after first time
                        {
                            $allBookings = $_SESSION["allBookings"];
                        }  
                        // reset the previously selected table                       
                        if ($previousSelection != null && $previousJoinedCount != null)
                        {       
                            $allBookings[$previousSelection]["joinedTable"] = $previousJoinedCount;
                        } 
                        // set the table size of the selected table                        
                        if ($tableSelected != null)
                        {
                            $allBookings[$tableSelected]["joinedTable"] = ceil($totalPeople / 2);
                        }
                        
                        function DrawTable($joinedCount, $out, $src, &$x, &$y, $src_w, $src_h, $width, $height, $booked, $number, $tableSelected)
                        {
                            $margin = 10;

                            $buttonWidth = $joinedCount * $src_w;
                            $totalWidth = $margin * 2 + $buttonWidth;

                            if (($x + $totalWidth) >= $width) // cant fit, need new row
                            {
                                $x = 0;
                                $y += $src_h + $margin * 2;
                            }                            
                            $button = new Button();
                            $x += $margin;
                            $button->AddButton($x, $y + $margin, $buttonWidth, $src_h, $number, $joinedCount, $booked, $tableSelected);
                            $x += $buttonWidth;
                            $x += $margin;                                                            
                        }

                        $srcfile = 'img/x.png';                        
                        $outfile = 'bg.jpg';
                        list($src_w, $src_h, $src_type) =  getimagesize($srcfile);                       

                        $width = 660;
                        $height = 500;         
                        $src = imagecreatefrompng($srcfile);                        
                        $out = imagecreatetruecolor($width, $height);
                        $backgoundColor = imagecolorallocate($out, 238, 228, 185);
                        imagefilledrectangle($out, 0, 0, $width, $height, $backgoundColor);

                        $x = 0;
                        $y = 40;                        
                        for ($t = 0 ; $t < count($allBookings) ; $t++)
                        {
                            $joinedCount = $allBookings[$t]['joinedTable'];
                            $booked = $allBookings[$t]['customerId'] != 0;
                            DrawTable($joinedCount, $out, $src, $x, $y, $src_w, $src_h, $width, $height, $booked, $t, $joinedCount, $tableSelected);
                        }
                        imagejpeg($out, $outfile, 100); // 100 is quality
                        imagedestroy($src);
                        imagedestroy($out);
                        ?>
                        </div>
                       
            <form id="formB" name="makebooking" method="post" style="position: absolute; right: 60px; bottom: 30px;" action="booked.php"  
                  onsubmit="return ConfirmBooking(<?php echo $totalPeople,",", $tableSelected + 1; ?>)">
            <input name="submit" type="submit" value="Make a Booking" />
            </form>
            <form id="formC" name="cancel" method="get" style="position: absolute; right: 186px; bottom: 30px;" action="tableLayout.php?cancel="  
                  onsubmit="return ConfirmCancel();">
            <input name="submit" type="submit" value="cancel" />
            </form>
            
                </div>
              </div><!--end of structure-->        
  	    <div id="footer"><?php echo $model[1]['body']; ?></div>
      </div><!--end of container-->      
    </body>
</html>
