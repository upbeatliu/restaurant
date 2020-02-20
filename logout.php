<?php
session_start();
session_destroy();
header('Location: index.php?id=1&logout=0');

?>