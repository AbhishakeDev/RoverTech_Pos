<?php
session_start();

session_destroy();

header('location:index.php');//redirecting to index.php(login page)

?>