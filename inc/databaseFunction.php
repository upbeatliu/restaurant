<?php
class Booking
{
    function fetch_all()
    {
        global $pdo;
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = $pdo->prepare("SELECT * FROM re_booking ORDER BY tableNumber ASC");        
        $query->execute();    
        return $query->fetchAll();
    }
        
   function HasSessionBeenInitialized($date, $session)
   {
        global $pdo;
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = $pdo->prepare("SELECT * FROM re_booking where date = ? and session = ?");
        $query->bindValue(1, $date);
        $query->bindValue(2, $session);
        $query->execute();        
        $rows = $query->fetchAll();
       
	if (count($rows) > 0)
	{
	echo "Found existing rows for date ", $date;
	}
        return count($rows) > 0; // true if there are rows for the date and session, else false
   }
   
   function InitializeSession($date, $session, $tableCount, $defaultJoinedTables)
   {
       $tableNumber = 0;
       while ($tableCount > 0)
       {
            $useJoinedTables = $defaultJoinedTables; // make sure you don't add in over the table count
            if ($tableCount < $defaultJoinedTables)
            {
                $useJoinedTables = $tableCount;
            }           
            global $pdo;
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $query = $pdo->prepare("INSERT INTO re_booking (date, session, tableNumber, adults, children, customerId, completed, joinedTable)" .
                                   "VALUES (?, ?, ?, 0, 0, 0, 0, ?)");
            $query->bindValue(1, $date);
            $query->bindValue(2, $session);
            $query->bindValue(3, $tableNumber);  
            $query->bindValue(4, $useJoinedTables);
            $query->execute(); 
            $tableNumber++;
            $tableCount -= $useJoinedTables;
       }
   echo ("Adding in new session");
   }
   
   function GetSession($date, $session)
   {
        global $pdo;
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = $pdo->prepare("SELECT tableNumber, adults, children, joinedTable, customerId FROM re_booking where date = ? and session = ? ORDER BY tableNumber");
        $query->bindValue(1, $date);
        $query->bindValue(2, $session);
        $query->execute();
        return $query->fetchAll();
   }
   
   function MakeBooking($date, $session, $adults, $children, $tableId, $customerId)
   {
        global $pdo;
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = $pdo->prepare("UPDATE re_booking SET adults = ?, children = ?, customerId = ?, joinedTable = ? WHERE tableNumber = ? AND date = ? AND session = ?");
        
        $query->bindValue(1, $adults);
        $query->bindValue(2, $children);        
        $query->bindValue(3, $customerId);
        $query->bindValue(4, ($adults + $children) / 2);        
        $query->bindValue(5, $tableId);
        $query->bindValue(6, $date);        
        $query->bindValue(7, $session);
        $query->execute();
   }
   
   function ClearBooking()
   {
        unset($_SESSION["session"]);
        unset($_SESSION["selectedDate"]);
        unset($_SESSION["adults"]);
        unset($_SESSION["children"]);
        unset($_SESSION["previousSelection"]);
        unset($_SESSION["userId"]);
        unset($_SESSION["previousJoinedCount"]);
        unset($_SESSION["allBookings"]);
   }
   
   function DeleteBooking($date, $session, $customerId)
   {
        global $pdo;
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = $pdo->prepare("UPDATE re_booking SET adults = 0, children = 0, customerId = 0, joinedTable = 2 WHERE date = ? AND session = ? AND customerId = ?");
        $query->bindValue(1, $date);        
        $query->bindValue(2, $session);    
        $query->bindValue(3, $customerId);  
        $query->execute();        
   }
}
class Meal
{
    function Date()// distinct date from re_booking table
    {
        global $pdo;
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = $pdo->prepare("SELECT DISTINCT `date` FROM re_booking");                
		$query->execute();    
                return $query->fetchAll();  
    }
    function session()// distinct session from re_booking table
    {
        global $pdo;
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = $pdo->prepare("SELECT DISTINCT `session` FROM re_booking");                
		$query->execute();    
                return $query->fetchAll();  
    }
    function selectTableNum($mealdate, $mealSession)
    {
        global $pdo;
		$query = $pdo->prepare("SELECT * FROM re_booking JOIN re_users ON re_booking.customerId = re_users.id WHERE date = ? AND session = ? ORDER BY tableNumber");
		$query->bindValue(1, $mealdate);
                $query->bindValue(2, $mealSession);
		$query->execute();
		return $query->fetchAll();
    }
    function fetch_menu()
    {
        global $pdo;        
        $query = $pdo->prepare("SELECT * FROM re_menu");        
        $query ->execute();
        return $query->fetchAll();
    }
   
    function bookingMeal($session, $selectedDate, $customerId, $tableNumber, $menuIds, $portions, $prices)
    {
        global $pdo;        
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);       
        
        // get the booking id
        $query = $pdo->prepare("SELECT bookId FROM re_booking WHERE session = ? and date = ? and tableNumber = ? and customerId = ?");        
        $query->bindValue(1, $session);
        $query->bindValue(2, $selectedDate);        
        $query->bindValue(3, $tableNumber);
        $query->bindValue(4, $customerId); 
        $query->execute();          
        $bookId = $query->fetch()[0];
       
        // set the completed value for the booking table
        $query = $pdo->prepare("UPDATE re_booking SET completed = true WHERE bookId = ?");        
        $query->bindValue(1, $bookId);
        $query->execute();
       
        // add the meals
        for ($i = 0 ; $i < count($portions) ; $i++)
        {
            if ($portions[$i] > 0)
            {
                $query = $pdo->prepare("INSERT INTO re_meal (bookingId, menuId, portion, price) VALUES (?, ?, ?, ?)");           
                $query->bindValue(1, $bookId);
                $query->bindValue(2, $menuIds[$i]);        
                $query->bindValue(3, $portions[$i]);
                $query->bindValue(4, $prices[$i]);
                $query->execute();
            }
        }
    }
}
?>
