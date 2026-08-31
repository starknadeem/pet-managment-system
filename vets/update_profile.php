<?php
require '../config/db.php';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'vet') {
    die("Unauthorized access.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uid   = $_SESSION['user_id'];
    $name  = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $spec  = trim($_POST['spec']);
    $fee   = (int)$_POST['fee'];
    $pay   = trim($_POST['payment_info']);
    $exp   = trim($_POST['exp']);

    try {
 
        $pdo->beginTransaction();

       
        $stmt1 = $pdo->prepare("UPDATE users SET full_name = ?, email = ? WHERE id = ?");
        $stmt1->execute([$name, $email, $uid]);
        

        $_SESSION['name'] = $name;


        $profile_pic = null;
        if (!empty($_FILES['profile_pic']['name'])) {
            $filename = time() . "_" . basename($_FILES['profile_pic']['name']);
            $target = "../assets/uploads/" . $filename;
            
       
            if (!is_dir('../assets/uploads/')) {
                mkdir('../assets/uploads/', 0777, true);
            }

            if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target)) {
                $profile_pic = $filename;
            }
        }

    
        if ($profile_pic) {
         
            $stmt2 = $pdo->prepare("UPDATE vet_profiles SET 
                specialization = ?, 
                consultation_fee = ?, 
                payment_info = ?, 
                experience_details = ?, 
                profile_pic = ? 
                WHERE user_id = ?");
            $stmt2->execute([$spec, $fee, $pay, $exp, $profile_pic, $uid]);
        } else {
            
            $stmt2 = $pdo->prepare("UPDATE vet_profiles SET 
                specialization = ?, 
                consultation_fee = ?, 
                payment_info = ?, 
                experience_details = ? 
                WHERE user_id = ?");
            $stmt2->execute([$spec, $fee, $pay, $exp, $uid]);
        }

        $pdo->commit();
        
   
        header("Location: dashboard.php?view=profile&status=success");
        exit();
        
    } catch (Exception $e) {
  
        $pdo->rollBack();
        die("Critical Error updating profile: " . $e->getMessage());
    }
} else {
    header("Location: dashboard.php");
    exit();
}