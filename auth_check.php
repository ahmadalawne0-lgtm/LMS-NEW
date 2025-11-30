<?php
// auth_check.php
// Authentication helper for controllers

require_once 'session_manager.php';

// Function to check admin authentication
function requireAdmin() {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
        header('Location: login.php');
        exit;
    }
}

// Function to check teacher authentication
function requireTeacher() {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'teacher') {
        header('Location: login.php');
        exit;
    }
}

// Function to check admin or teacher authentication
function requireAdminOrTeacher() {
    if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'teacher'])) {
        header('Location: login.php');
        exit;
    }
}

// Function to check student authentication
function requireStudent() {
    if (!isset($_SESSION['student_id'])) {
        header('Location: login.php');
        exit;
    }
}
?>