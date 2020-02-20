<?php
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

//$model['name'];
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
                      { ?>
                      <li class="selected">
                      	<a href="index.php?id=<?php echo $nav['id']; ?>">
                          <?php echo $nav['menu_name']; ?>
                        </a>                           
                      </li>
                <?php }?>   
        	</ul>
                  <?php if(isset($_GET['logout']) && (preg_replace("[^0-9]", "", $_GET['logout']) == '0'))
                {
                    echo "<p style=\"position: absolute; left: 54px;\">You have logged out.</p>";
                } ?>
		  </nav>
               
	           <div class="content">
		         <h3><?php echo $data['menu_name']; ?></h3>    
	                 <p><?php echo $data['content']; ?></p>
                         <?php 
                         if(isset($_GET['message']))
                            {
                               $error=$_GET['message'];
                               echo "<p>Incorrect details. <br>Please try again.</p>";   
                            }
                            else
                            {
                               echo "";
                            }
                         ?>
	          </div><!---end of content-->
	    </div><!---end of structure-->
	<div id="footer"><?php echo $model[1]['body']; ?></div>
  </div><!--end of container-->

</body>
</html>
