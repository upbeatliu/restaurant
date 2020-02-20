<?php
session_start();
include_once('inc/connection.php');
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
          header('location: index.php?id=5&message=$error');
          //echo 'It was not correct.';
      }

?>
