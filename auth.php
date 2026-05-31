<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function check_admin() {
    if (!isset($_SESSION['superuser']) || $_SESSION['superuser'] !== true) {
        header("Location: login.php");
        exit;
    }
}
?>
