<?php
// session_manager.php
// Centralized session management to avoid duplicate session_start() calls

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>