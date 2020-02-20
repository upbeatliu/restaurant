<html>
   <head>
        <title> Yummy Restaurant</title>
        <link rel="stylesheet" href="../css/style.css">
   </head>
<body>
    <div id="voucher">
        <div id="voucherTitle">
            <h1>Yummy Restaurant</h1>
            <p class="currentDate"><?php echo date("d/m/Y"); ?></p>
        </div>
            <div id="vcontent">
            <p>Discount Voucher</p>
            <p>25% discount</p>
            <p>Voucher Code :</p>
            <?php
            $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $res = "";
            for ($i = 0; $i < 10; $i++) {
                $res .= $chars[mt_rand(0, strlen($chars)-1)];
            }
            echo "<h4>",$res,"</h4>";
            ?>
            </div>    
    </div>
    <a href="adminIndex.php">&larr;Go Back Admin Main Page</a>
</body>
</html>
