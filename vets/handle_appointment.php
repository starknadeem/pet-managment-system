<?php
require '../config/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['role'] == 'vet') {
    $apt_id = $_POST['apt_id'];
    $action = $_POST['action'];
    $vet_id = $_SESSION['user_id'];

    if ($action == 'accept') {
        $link = $_POST['meeting_link'];
        $time = $_POST['meeting_time'];
        $pwd  = $_POST['meeting_pwd'] ?? '';
        $desc = $_POST['meeting_desc'] ?? '';

        $stmt = $pdo->prepare("UPDATE appointments SET 
            status = 'accepted', 
            meeting_link = ?, 
            meeting_time = ?, 
            meeting_pwd = ?, 
            meeting_desc = ? 
            WHERE id = ? AND vet_id = ?");
        
        if($stmt->execute([$link, $time, $pwd, $desc, $apt_id, $vet_id])) {
            $msg = "Appointment successfully accepted!";
        }
    } else {
        $stmt = $pdo->prepare("UPDATE appointments SET status = 'rejected' WHERE id = ? AND vet_id = ?");
        $stmt->execute([$apt_id, $vet_id]);
        $msg = "Appointment rejected.";
    }

    header("Location: dashboard.php?view=requests&msg=" . urlencode($msg));
    exit();
}