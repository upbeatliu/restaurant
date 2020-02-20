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
        $id = 0;
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
		  </nav>
               
	           <div class="content">
		         <h3><?php echo $data['menu_name']; ?></h3>    
	                 <p><?php echo $data['content']; ?></p>
                         <?php
                    $signUp = new SignUp;      
                    $username = $_POST['username'];
                    $password = $_POST['password'];
                    $password_again = $_POST['password_again'];
                    $pw_hash = md5($password);
                    $firstname = $_POST['firstname'];
                    $lastname = $_POST['lastname']; 
                    $contactNum = $_POST['contactNum'];
                    $member = $_POST['submit'];
                    //echo "$username,$pw_hash,$firstname,$lastname,$contactNum";
                   
                    $errors = null;
                    
                    if($signUp->checkValidNewMember($username) == false)
                    {
                          echo "<br><p>Username is already taken, please choose a different one.</p>";
                    }
                    else if ($password != $password_again)
                    {
                          echo "<br><p>Password don't match.</p>";
                    }                 
                    else if (count(($errors = $signUp->checkMinLengths($username, $password, $firstname, $lastname, $contactNum))) != 0)
                    {
                        foreach ($errors as $error)
                        {
                          echo $error;
                        }
                    }
                    else
                    {
                          $signUp->createMember($username,$pw_hash,$firstname,$lastname,$contactNum);
                          echo "<h4>Created A Member</h4><br><p>Welcome to be our member.</p>";
                    }
                         ?>
	          </div><!---end of content-->
	    </div><!---end of structure-->
	<div id="footer"><?php echo $model[1]['body']; ?></div>
  </div><!--end of container-->

</body>
</html>
