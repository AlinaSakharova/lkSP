<?php 
require "boot.php";
$_SESSION['auth'] = null;
session_destroy();
header('Location: index.php');