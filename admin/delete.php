<?php
session_start();
include_once('../inc/connection.php');
include_once('../inc/function.php');

$nav= new Nav;
$model = $nav->fetch_model();

if(isset($_GET['id']))
{
  $id = $_GET['id'];

}
$data = $nav->fetch_data($_GET['menu_id']);
$menu_name = $data['menu_name'];


	if(isset($_GET['menu_id']))
     {   
        $id = $_GET['menu_id'];
        $sql = "DELETE FROM re_subjects WHERE id = ?"; 
        $statement = $pdo->prepare($sql);
        $statement->bindParam(1, $id, PDO::PARAM_INT);
        $statement->execute();
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
                <h4><?php echo "You have successfully deleted id: " , $menu_name; ?></h4>
                <a href="index.php">&larr;Go Back Admin Main Page</a>
                
                >
      </div><!---end of structure-->
      <div id="footer"><?php echo $model[1]['body']; ?></div>
  </div><!--end of container-->
</body>
</html>

<?php } ?>
        
      

  
