<?php
session_start();
include_once('../inc/connection.php');
include_once('../inc/function.php');
$nav = new Nav;
$navs = $nav->fetch_all();
$model = $nav->fetch_model();

if (isset($_SESSION['logged_in']))
{
	 if (isset($_POST['name']) == true &&
	    isset($_POST['visible']) == true &&
		isset($_POST['body']) == true)
	{
		$id= $_GET['id'];
		$name = $_POST['name'];    		
 		$body = nl2br($_POST['body']);
        $visible = $_POST['visible'];
   	  if (empty($name) || empty($visible) || empty($body))
		{
			$error = 'All fields are required.';
		}
		else
		{
			$query = $pdo->prepare("UPDATE re_model SET name = ?, visible = ?, body = ? WHERE id = ?");
			$query->bindValue(1, $name, PDO::PARAM_STR);			
			$query->bindValue(2, $visible, PDO::PARAM_BOOL);
			$query->bindValue(3, $body, PDO::PARAM_STR);
			$query->bindValue(4, $id, PDO::PARAM_INT);			
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
                <h4><?php echo "You have successfully updated: " , $name; ?></h4>
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
}
else
{
	header("Location: index.php");
}

?>