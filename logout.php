<?php
session_start();
ob_start();

unset($_SESSION["username"]);

$_SESSION["error"] = "You're logged out";
header("location:./index.php");
?>