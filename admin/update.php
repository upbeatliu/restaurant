<?php
session_start();
include_once('../inc/connection.php');
include_once('../inc/function.php');
$nav = new Nav;
$navs = $nav->fetch_all();
$model = $nav->fetch_model();

if (isset($_SESSION['logged_in']))
{
	if (isset($_POST['menu_name']) == true &&
		isset($_POST['position']) == true &&
		isset($_POST['visible']) == true &&
		isset($_POST['content']) == true)
	{
		$id= $_GET['id'];
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
			$query = $pdo->prepare("UPDATE re_subjects SET menu_name = ?, position = ?, visible = ?, content = ? WHERE id = ?");
			$query->bindValue(1, $menu_name, PDO::PARAM_STR);
			$query->bindValue(2, $position, PDO::PARAM_INT);
			$query->bindValue(3, $visible, PDO::PARAM_BOOL);
			$query->bindValue(4, $content, PDO::PARAM_STR);
			$query->bindValue(5, $id, PDO::PARAM_INT);			
			$query->execute();

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
              <br/>
                <h4><?php echo "You have successfully updated id: " , $menu_name; ?></h4>
                <a href="index.php">&larr;Go Back Admin Main Page</a>
                <h4><?php echo "Check Public Main Page." ?></h4>
                <a href="../index.php">&larr;Go to Public index Page</a>
                >
      </div><!---end of structure-->
      <div id="footer"><?php echo $model[1]['body']; ?></div>
  </div><!--end of container-->
</body>
</html>

<?php 		
		}
	}
	else
	{
		echo "failed";
	}
}
else
{
	header("Location: index.php");
}

?>