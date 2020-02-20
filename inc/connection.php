<?php
try{
	$pdo = new PDO('mysql:host=localhost;
		            dbname=chch1804_example','root','');
   }
catch(PDOException $e)
   {
        exit('Database error.');
   }
?>