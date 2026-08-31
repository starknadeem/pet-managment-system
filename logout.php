<?php
session_start();

session_unset();
session_destroy();

if (isset($_GET['type']) && $_GET['type'] === 'admin') {
    header("Location: index.php");
} else {
    header("Location: login.php");
}
exit();
?>