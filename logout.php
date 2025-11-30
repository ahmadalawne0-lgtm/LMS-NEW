<?php
require_once("session_manager.php");
session_destroy();
header("Location: login.php");
exit;
?>
