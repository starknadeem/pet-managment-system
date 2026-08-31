<?php
require '../config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action == 'signup') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $role = $_POST['role'];

  
    $checkEmail = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $checkEmail->execute([$email]);
    
    if ($checkEmail->rowCount() > 0) {
        $_SESSION['error'] = "This email is already registered."; 
        header("Location: ../signup.php");
        exit();
    }

    if (strlen($password) < 8) {
        $_SESSION['error'] = "Password must be at least 8 characters long.";
        header("Location: ../signup.php");
        exit();
    }

    $hashed_pass = password_hash($password, PASSWORD_BCRYPT);
    
    try {
        $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, role, phone) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $hashed_pass, $role, $phone]);
        
        $_SESSION['success'] = "Account created! Please sign in.";
        header("Location: ../login.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "System failure. Please try again later.";
        header("Location: ../signup.php");
        exit();
    }
} 

elseif ($action == 'login') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $selected_role = $_POST['login_role'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = ? AND status = 'active'");
    $stmt->execute([$email, $selected_role]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
  
        if (password_verify($password, $user['password'])) {
         
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['full_name'];
            $_SESSION['logged_in'] = true;

            if ($user['role'] === 'admin') {
                header("Location: ../admin/admindashboard.php");
            } elseif ($user['role'] === 'vet') {
                header("Location: ../vets/vetdashboard.php");
            } else {
                header("Location: ../users/userdashboard.php");
            }
            exit();
        } else {
           
            $_SESSION['error'] = "The password you entered is incorrect.";
            header("Location: ../login.php");
            exit();
        }
    } else {
    
        $_SESSION['error'] = "No $selected_role account found with that email.";
        header("Location: ../login.php");
        exit();
    }
}

elseif ($action == 'logout') {
    session_unset();
    session_destroy();
    session_start(); 
    $_SESSION['success'] = "You have been logged out successfully.";
    header("Location: ../login.php");
    exit();
}
?>