
<?php

require('fpdf.php');
require('../inc/connection.php');

class PDF extends FPDF
{    
    function MakeReport($widths, $headers, $data, $sectionIdentifier, $totalColumns)
    {  
        // Colors, line width and bold font
        $this->SetFillColor(153,102,51);
        $this->SetTextColor(255);
        $this->SetDrawColor(128,0,0);
        $this->SetLineWidth(.3);
        $this->SetFont('Arial','',10);      
        $this->AddPage();
  
        // display the header row
        for ($i=0; $i < count($headers) ; $i++)
        {
            $this->Cell($widths[$i], 7, $headers[$i], 1, 0, 'C', true);
        }
        $this->Ln();

        $this->SetTextColor(0);
        $this->SetFont('');
        $fill = false;
        $section = null;
        
        // set up the totals arrays for storing the totals
        $sectionTotals = array();
        $grandTotals = array();
        foreach ($totalColumns as $column => $value)
        {            
            $sectionTotals += array($column => 0);
            $grandTotals += array($column => 0);
        }
         
        for ($r = 0 ; $r < count($data) ; $r++) // go through every row of data
        {
            $row = $data[$r];
            
            // keep track of the totals
            foreach ($totalColumns as $column => $value)
            {         
                $sectionTotals[$column] += $row[$column];
                $grandTotals[$column] += $row[$column];                  
            }
            
            if ($section == null) $section = $row[$sectionIdentifier];      
            
            // display the rows data               
            $this->DrawRow(224, 235, 255, $row, $widths, $headers, null, $fill);               
            $fill = !$fill;          
            
            // find out of this is the last row
            $lastRow = ($r == (count($data) - 1));
            
            // check if need to do session total         
            // if last row of data or  next row's session is different
            if ($lastRow == true || $data[$r + 1][$sectionIdentifier] != $section)
            {   
                $section = null;                
                $this->DrawRow(255, 153, 51, $row, $widths, $headers, $sectionTotals, true);    
            }
            
            if ($lastRow == true) // display the grand totals
            {
                $this->DrawRow(241,65,65, $row, $widths, $headers, $grandTotals, true);                                  
            }
        }
        // Closing line
        $this->Cell(array_sum($widths),0,'','T');        
        
        $this->Output();    
    }
    
    function DrawRow($red, $green, $blue, $row, $widths, $headers, $keyExistsArray, $fill)
    {
        $this->SetFillColor($red, $green, $blue);

        $position = 0;
        foreach ($headers as $header)
        {   
            $value = '';
            if ($keyExistsArray != null)
            {
                if (array_key_exists($header, $keyExistsArray) == true) 
                {
                    $value = $keyExistsArray[$header];
                    $keyExistsArray[$header] = 0; // reset the section total
                }
            }
            else 
            {
                $value = $row[$header];
            }

            $this->Cell($widths[$position++], 6, $value,'LR',0,'L', $fill);
        }
        $this->Ln();
    }
    
    function DailyBooking($date)
    {
        global $pdo;
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = $pdo->prepare("SELECT date, session, username, adults, children, adults + children as total
                                FROM re_booking JOIN re_users on re_users.id = re_booking.customerId
                                WHERE re_booking.customerId <> 0
                                AND re_booking.date = ? 
                                ORDER BY session");
        $query->bindValue(1, $date);
        $query->execute();
        $data = $query->fetchAll();        
        $widths = array(20, 20, 20, 20, 20, 20); 
        $headers = array('date', 'session', 'username', 'adults', 'children', 'total');
        $totalColumns = array('adults' => 0, 'children' => 0, 'total' => 0);
        $sectionIdentifier = 'session';
        
        $this->MakeReport($widths, $headers, $data, $sectionIdentifier, $totalColumns);  
    }
    
    function DailyMeals($date)
    {
        global $pdo;
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = $pdo->prepare("SELECT date, session, username, adults, children, adults + children as total, mealName, portion, re_meal.price * portion as cost
                               FROM re_booking 
                               JOIN re_users ON re_users.id = re_booking.customerId
                               JOIN re_meal ON re_meal.bookingId = re_booking.bookId
                               JOIN re_menu ON re_menu.menuId = re_meal.menuId 
                               WHERE re_booking.customerId <> 0
                               AND re_booking.date = ?
                               ORDER BY session");

        $query->bindValue(1, $date);
        $query->execute();
        $data = $query->fetchAll();        
        $widths = array(20, 20, 20, 15, 15, 10, 65, 12, 12); 
        $headers = array('date', 'session', 'username', 'adults', 'children', 'total', 'mealName', 'portion', 'cost' );
        $totalColumns = array('adults' => 0, 'children' => 0, 'total' => 0, 'cost' => 0);
        $sectionIdentifier = 'session';
        
        $this->MakeReport($widths, $headers, $data, $sectionIdentifier, $totalColumns);  
    }
    
    function Weekly($date)
    {
        $startDate = strtotime($date);
        // one week in the future
        $endDate = strtotime("+6 day", $startDate);
        
        global $pdo;
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = $pdo->prepare("SELECT date, session, username, adults, children, adults + children as total, mealName, portion, re_meal.price * portion as cost
                               FROM re_booking 
                               JOIN re_users ON re_users.id = re_booking.customerId
                               JOIN re_meal ON re_meal.bookingId = re_booking.bookId
                               JOIN re_menu ON re_menu.menuId = re_meal.menuId 
                               WHERE re_booking.customerId <> 0
                               AND re_booking.date >= ?
                               AND re_booking.date <= ?
                               ORDER BY date, session, total, mealName");

        $query->bindValue(1, date('Y-m-d', $startDate));
        $query->bindValue(2, date('Y-m-d', $endDate));        
        
        $query->execute();
        $data = $query->fetchAll();        
        $widths = array(20, 20, 20, 15, 15, 10, 65, 12, 12); 
        $headers = array('date', 'session', 'username', 'adults', 'children', 'total', 'mealName', 'portion', 'cost' );
        $totalColumns = array('adults' => 0, 'children' => 0, 'total' => 0, 'cost' => 0);
        $sectionIdentifier = 'date';
        
        $this->MakeReport($widths, $headers, $data, $sectionIdentifier, $totalColumns);  
    }  
    
    function Monthly($date)
    {
        $startDate = strtotime($date);
        // one month in the future
        $endDate = strtotime("+1 month -1 day", $startDate);
        
        global $pdo;
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = $pdo->prepare("SELECT date, session, username, adults, children, adults + children as total, mealName, portion, re_meal.price * portion as cost
                               FROM re_booking 
                               JOIN re_users ON re_users.id = re_booking.customerId
                               JOIN re_meal ON re_meal.bookingId = re_booking.bookId
                               JOIN re_menu ON re_menu.menuId = re_meal.menuId 
                               WHERE re_booking.customerId <> 0
                               AND re_booking.date >= ?
                               AND re_booking.date <= ?
                               ORDER BY date, session, total, mealName");

        $query->bindValue(1, date('Y-m-d', $startDate));
        $query->bindValue(2, date('Y-m-d', $endDate));        
        
        $query->execute();
        $data = $query->fetchAll();        
        $widths = array(20, 20, 20, 15, 15, 10, 65, 12, 12); 
        $headers = array('date', 'session', 'username', 'adults', 'children', 'total', 'mealName', 'portion', 'cost' );
        $totalColumns = array('adults' => 0, 'children' => 0, 'total' => 0, 'cost' => 0);
        $sectionIdentifier = 'date';
        
        $this->MakeReport($widths, $headers, $data, $sectionIdentifier, $totalColumns);  
    }     
}

$pdf = new PDF();
$reportId = $_GET['id'];
$reportDate = $_GET['reportDate'];

if ($reportId == 1)
{
    $pdf->DailyBooking($reportDate);
}
else if ($reportId == 2)
{
    $pdf->DailyMeals($reportDate);
}
else if ($reportId == 3)
{
    $pdf->Weekly($reportDate);
}
else if ($reportId == 4)
{
    $pdf->Monthly($reportDate);
}

?>
