<?php
ob_start();
session_start();
session_unset();
session_destroy();
header('location: ../views/auth/login.php');
?>
