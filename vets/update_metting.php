<?php
require '../config/db.php';
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['role'] == 'vet') {
    $apt_id = $_POST['apt_id'];
    $link = $_POST['meeting_link'];

    $stmt = $pdo->prepare("UPDATE appointments SET meeting_link = ? WHERE id = ?");
    if($stmt->execute([$link, $apt_id])) {
        header("Location: dashboard.php?view=schedule&msg=LinkSent");
    }
}