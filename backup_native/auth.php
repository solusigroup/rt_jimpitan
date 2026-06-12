<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function check_admin() {
    $kunci_rahasia = "rt035jimpitan2026";
    $token_request = isset($_GET['token']) ? trim($_GET['token']) : '';

    if ($token_request === $kunci_rahasia) {
        $_SESSION['login'] = true;
        $_SESSION['superuser'] = true;
    }

    if (!isset($_SESSION['superuser']) || $_SESSION['superuser'] !== true) {
        header("Location: login.php");
        exit;
    }
}
?>
