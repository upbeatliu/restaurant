<?php
class Nav
{
    function fetch_all()
	{
		global $pdo;
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = $pdo->prepare("SELECT * FROM re_subjects WHERE visible = '1' ORDER BY position ASC");
		$query->execute();
		return $query->fetchAll();
	}
    function fetch_data($menu_id)
	{
		global $pdo;
		$query = $pdo->prepare("SELECT * FROM re_subjects WHERE id = ?");
		$query->bindValue(1, $menu_id);
		$query->execute();
		return $query->fetch();
	}
   function fetch_model()
	{
		global $pdo;		
		$query = $pdo->prepare("SELECT * FROM re_model WHERE visible = '1'");
		$query->execute();		
		return $query->fetchAll();
	}
   function fetch_modeldata($menu_id)
	{
		global $pdo;
		$query = $pdo->prepare("SELECT * FROM re_model WHERE id = ?");
		$query->bindValue(1, $menu_id);
		$query->execute();
		return $query->fetch();
	}
}
class Table_heading
{
	function BeginTable($width, $border,$cellpadding)
	{
		echo "<table width=\"", $width ,"\"", "border=\"", $border,"\"","cellpadding=\"", $cellpadding,"\">";
	}
	function EndTable()
	{
        echo "</table>";
	}
	function BeginHeading()
	{
		echo "<tr>";
	}
	function AddHeading($width, $bgcolor, $text)
	{
		echo "<td width=\"" , $width , "\"" , " bgcolor=\"" , $bgcolor , "\">" , "<small class=\"intable\">", $text ,"</small>" , "</td>";
	}
	function EndHeading()
	{
		echo "</tr>";
	}
}
class form
{	
	function BeginForm($id, $name, $method, $action, $onsubmit)
	{
		echo "<form id=\"", $id ,"\"", "name=\"", $name ,"\"", "method=\"", $method ,"\"", "action=\"", $action ,"\"", "onsubmit=\"", $onsubmit ,"\">";
	}
	function EndForm()
	{
        echo "</form>";
	}
	function Begintd()
	{
		echo "<td>";
	}
	function Endtd()
	{
		echo "</td>";
	}
	function Input($type, $name, $value, $size)
	{
		echo "<td><input type=\"", $type ,"\"", "name=\"", $name ,"\"", "value=\"", $value ,"\"", "size=\"", $size ,"\" /></td>";
	}
	function BeginTextarea($name, $rows, $cols, $value)
	{
		echo "<td><textarea name= \"", $name ,"\"", "rows=\"", $rows ,"\"", "cols=\"", $cols ,"\"", "value=\"", $value ,"\">";
	}
	function Endtextarea()
	{
		echo "</textarea></td>";
	}
	function specialInput($type, $name, $value, $size)
	{
		echo "<input type=\"", $type ,"\"", "name=\"", $name ,"\"", "value=\"", $value,"\"", "size=\"", $size ,"\" />";
	}
}

class ComboBox
{
    function AddComboBoxWithOnChange($tableRows, $selectedValue, $columnValue, $columnText, $redirectPage, $redirectId)
    {
        // the event for selection change, in javascript
        echo "<script type=\"text/javascript\">";
        echo "function selectionChanged(selection, redirectPage)";
        echo "{";	
        echo "var location = redirectPage + \"?" , $redirectId, "=\" + selection;";
        echo "window.location.href = location";	
        echo "}";
        echo "</script>";	
        // create the combo box, with all the items
        echo "<select onchange=\"selectionChanged(this.value, '" , $redirectPage , "');\">";			
        foreach ($tableRows as $tableRow) 
        {
            echo "<option ";
            // if selected, add the extra text to selected the value
            if ($tableRow[$columnValue] == $selectedValue)
            {
                echo "selected=\"selected\"";					
            }
            echo "value=\"" , $tableRow[$columnValue], "\">", $tableRow[$columnText], "</option>";
        }
        echo "</select>";
    }
}

class Button
{
    function AddButton($x, $y, $width, $height, $number, $joinedCount, $disabled, $tableSelected)
    {       
        echo "<script type=\"text/javascript\">";
        echo "function Click" , $number , "(value)";        
        echo "{";
        echo "window.location.href = \"tableLayout.php?tableId=" , $number , "&joinedCount=" , $joinedCount , "\";";    
        echo "}";
        echo "</script>";        
        
        echo "<button type=\"button\" class=\"myBtn\" style=\"position: absolute; left: " , $x , "px; top: " , $y , "px;";
        echo "width: " , $width , "px;height: " , $height , "px; \" value=\"" , $number , "\" name=\"button" , $number , "\" onclick=\"Click" , $number , "(value) ";
        if ($disabled == true || $tableSelected == $number)
        {
            echo " disabled ";            
        }
        echo "\"/>";
        echo $number + 1;
        if ($disabled == true) echo " Booked";
    }
}

class SignUp
{	    
	  function GetUsername($username)
	  {
	  	 global $pdo;	  	 
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = $pdo->prepare("SELECT username FROM re_users WHERE username = ?");
		$query->bindValue(1, $username);
		$query->execute();
		return $query->fetchAll();
	  }

	  function createMember($username, $pw_hash, $firstname, $lastname, $contactNum)
	  {
	  	  global $pdo;	  	 
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = $pdo->prepare("INSERT INTO re_users (username, password, firstname, lastname, contactNum) VALUES (?,?,?,?,?)");
		$query->bindValue(1, $username);
		$query->bindValue(2, $pw_hash);
		$query->bindValue(3, $firstname);
		$query->bindValue(4, $lastname);
		$query->bindValue(5, $contactNum);
		$query->execute();
	  }
	  // true, can insert new member, false, already exists
	  function checkValidNewMember($username)
	  {
	  	global $pdo;	  	 
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = $pdo->prepare("SELECT * FROM re_users WHERE username = ?");
		$query->bindValue(1, $username);
		$query->execute();
		return count($query->fetchAll()) == 0;
	  }
          
          function checkSpecialChars($word)
          {
                preg_match_all("/[|!@#$%&*\/=?,;.:\-_+~^\\\]/", $word, $specialchars);
                if (sizeof($specialchars[0]) > 0)
                {
                    return false;
                }                
                return true;
          }

      function checkMinLengths($username,$password,$firstname,$lastname,$contactNum) 
      {     
          $errors = array();
  
          if (strlen($username) < 3)
          {
              array_push($errors, "<br><p>Username is not long enough</p>");
          }         
          if (strlen($password) < 3)
          {
              array_push($errors, "<br><p>Password is too short it must not be less than 3 characters</p>");
          }
          if (strlen($password) > 8)
          {
              array_push($errors, "<br><p>Password is too long it must not be longer than 8 characters</p>");
          }
          if (strlen($firstname) <= 2)
          {
              array_push($errors, "<br><p>Firstname is not long enough</p>");
          }
          if (strlen($lastname) <= 2)
          {
              array_push($errors, "<br><p>Lastname is not long enough</p>");
          }
          if (strlen($contactNum) < 8)
          {
              array_push($errors, "<br><p>Contact Number is not long enough</p>");
          }
          
          if ($this->checkSpecialChars($firstname) == false)
          {
              array_push($errors, "<br><p>The firstname can't use specialcharacters and numbers.</p>");              
          }
          
          if ($this->checkSpecialChars($lastname) == false)
          {
               array_push($errors, "<br><p>The last can't use specialcharacters and numbers.</p>");                
          }          
          
          if ($this->checkSpecialChars($username) == false)
          {
              array_push($errors, "<br><p>The username can't use specialcharacters and numbers.</p>");                 
          }          
          
          $numbers = null;          
          preg_match_all('/[0-9]/', $contactNum, $numbers);
          if (count($numbers[0]) != strlen($contactNum))
          {             
              array_push($errors, "<br><p>The contact number must be all numbers</p>");             
          }
          return $errors;
      }
}
?>