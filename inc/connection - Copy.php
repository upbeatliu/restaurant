<?php
try{
	$pdo = new PDO('mysql:host=localhost;
		            dbname=chch1804_example','chch1804','jbhifi567');
   }
catch(PDOException $e)
   {
        exit('Database error.');
   }
?>