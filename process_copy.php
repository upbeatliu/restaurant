<?php
session_start();
include_once('inc/connection.php');
include_once('inc/function.php');

$name = $_POST['username'];
$pw = $_POST['password'];
$password = md5($pw);
//echo $name;
//echo $password;
$query = $pdo->prepare("SELECT * FROM re_users WHERE username= ? AND password = ?");
			$query-> bindValue(1, $name);
			$query-> bindValue(2, $password);
			$query-> execute();
                        $rowData = $query->fetch();
			$num = $query->rowCount();
    
if($num == 1)
      {
            $_SESSION['logged_in'] = true;
            $_SESSION['customerId'] = $rowData['id']; 
            $_SESSION['userlevel'] = $rowData['userlevel'];
            $_SESSION['username'] = $rowData['username'];
              if($_SESSION['userlevel'] == 1)
               {
                   header('Location: memberIndex.php');
               }
        else if ($_SESSION['userlevel'] == 3)
              {
                   header('Location: admin/adminIndex.php');
               }
      }
 else {
          $_SESSION['logged_in'] = false;
          //header('location: index.php?id=5&message'); 
      ?>     
<?php
$nav = new Nav;
$navs = $nav-> fetch_all();

if(isset($_GET['id']))
{
	$id = preg_replace("[^0-9]", "", $_GET['id']);
}
else
{
    $id = 0;
}
$data = $nav->fetch_data($id);

$model = $nav->fetch_model();
?>
        <!DOCTYPE HTML>
        <html>
           <head>
                <title> Member Area</title>
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
                                      <a href="memberIndex.php?id=<?php echo $nav['id']; ?>">
                                        <?php echo $nav['menu_name']; ?>
                                      </a>
                                    </li>
                            <?php }?>                             
                            </ul>            
                         </nav>
                           <div class="content">
                               <?php "<p>Incorrect details. Please try again.</p>";  ?>
                           </div><!---end of content-->
                </div><!---end of structure-->
                <div id="footer"><?php echo $model[1]['body']; ?></div>
            </div><!--end of container-->
  </body>
</html>    
     <?php     
      }

?>
