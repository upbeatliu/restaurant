<?php
session_start();
include_once('../inc/connection.php');
include_once('../inc/function.php');
$nav = new Nav;
$model = $nav->fetch_model();

if (isset($_SESSION['logged_in']))
{
	if (isset($_POST['menu_name'], $_POST['position'],$_POST['visible'],$_POST['content']))
	{
		$menu_name = $_POST['menu_name'];
		$position = $_POST['position'];
        $visible = $_POST['visible'];
		$content = nl2br($_POST['content']);

		if (empty($menu_name) || empty($position) || empty($visible) || empty($content))
		{
			$error = 'All fields are required.';
		}
		else
		{
			$query = $pdo->prepare("INSERT INTO re_subjects (menu_name, position, visible, content) VALUE (?, ?, ?, ?)");
			$query->bindValue(1, $menu_name);
			$query->bindValue(2, $position);
			$query->bindValue(3, $visible);
			$query->bindValue(4, $content);			
			$query->execute();
			header('Location: index.php');
		}
	}
	?>
<html>
   <head>
        <title> Yummy Restaurant</title>
        <link rel="stylesheet" href="../css/style.css">
        <script language="JavaScript" type="text/javascript" src="scripts/wysiwyg.js"></script>
   </head>
   
   <script language="javascript1.2">   

    var mysettings = new WYSIWYG.Settings();
	// define the location of the openImageLibrary addon
	mysettings.ImagePopupFile = "addons/imagelibrary/insert_image.php";
	// define the width of the insert image popup
	mysettings.ImagePopupWidth = 600;
	// define the height of the insert image popup
	mysettings.ImagePopupHeight = 245;

   //Image settings
   WYSIWYG.attach('content', mysettings);
</script>
   
<body>
	<div id="container">
	        <div id="header">
	        <?php echo $model[0]['body'];  ?>           
            </div>
	        <div class="main">    
	            <br/>
                <div class="form_area">
                <h4>Add New Content</h4>
                <a href="adminIndex.php">&larr;Go Back Admin Main Page</a>
                
                 <?php if (isset($error))
                       {?>
                       
			                <small style="color:#aa0000;"><?php echo $error; ?></small>
			                <br/>   
                  <?php } ?>
               
                <form action="add.php" method="post" autocomplete="on">
                	<p>Subject name: <input type="text" name="menu_name" placeholder="Menu Name" /><br>
                	
                     <p>Position: <input type="text" name="position" value="" id="position" size="1" /><br>
                    
                    Visible: <input type="radio" name="visible" value="0" /> No
			          &nbsp;
			        <input type="radio" name="visible" value="1" /> Yes<br>
                    
                	Content: <textarea id="content" rows="5" cols="100%" name="content" placeholder="content"></textarea></p>
                    
                    <div class="button"><input type="submit" value="Add Content"/></div>
                </form>
                </div>
	    </div><!---end of structure-->
	    <div id="footer"><?php echo $model[1]['body']; ?></div>
	</div><!--end of container-->
</body>
</html>
	<?php
}
else
{
	header("Location: index.php");
}
?>