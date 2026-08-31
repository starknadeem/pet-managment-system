<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --accent: #f43f5e;
            --bg: #f8fafc;
            --text: #0f172a;
            --nav-bg: rgba(255, 255, 255, 0.8);
            --card-bg: #ffffff;
            --border: rgba(226, 232, 240, 0.8);
            --footer-text: #475569;
        }

        [data-theme="dark"] {
            --bg: #0b0e14;
            --text: #f1f5f9;
            --nav-bg: rgba(11, 14, 20, 0.85);
            --card-bg: #161b22;
            --border: rgba(255, 255, 255, 0.1);
            --footer-text: #94a3b8;
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: var(--bg); 
            color: var(--text); 
            transition: all 0.3s ease;
            overflow-x: hidden;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar { 
            backdrop-filter: blur(20px); 
            background: var(--nav-bg); 
            border-bottom: 1px solid var(--border); 
            padding: 15px 0; 
        }

        .logo-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none !important;
            color: var(--text) !important;
            font-weight: 800;
            font-size: 1.6rem;
        }

        .logo-icon {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white !important;
            box-shadow: 0 5px 15px rgba(99, 102, 241, 0.3);
        }

        .theme-switch {
            width: 60px;
            height: 30px;
            background: #e2e8f0;
            border-radius: 50px;
            position: relative;
            cursor: pointer;
            transition: 0.3s;
            border: 1px solid var(--border);
        }

        [data-theme="dark"] .theme-switch { background: #334155; }

        .switch-circle {
            width: 22px;
            height: 22px;
            background: white;
            border-radius: 50%;
            position: absolute;
            top: 3px;
            left: 4px;
            transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            color: var(--primary);
        }

        [data-theme="dark"] .switch-circle { left: 32px; background: var(--primary); color: white; transform: rotate(360deg); }

        .nav-link { color: var(--text) !important; opacity: 0.7; font-weight: 600; }
        .nav-link:hover { opacity: 1; color: var(--primary) !important; }
        
        main { flex: 1 0 auto; padding-top: 100px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="logo-wrapper" href="index.php">
                <div class="logo-icon"><i class="fa-solid fa-shield-dog"></i></div>
                PetCare
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMain">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link px-3" href="./index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="./services.php">Services</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="./about.php">About</a></li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <div class="theme-switch" id="themeBtn">
                        <div class="switch-circle"><i class="fa-solid fa-sun"></i></div>
                    </div>
                    <a href="login.php" class="fw-bold text-decoration-none" style="color: var(--text); font-size: 0.9rem;">Log In</a>
                    <a href="signup.php" class="btn btn-primary rounded-pill px-4 fw-bold" style="background: var(--primary); border: none; font-size: 0.9rem;">Join</a>
                </div>
            </div>
        </div>
    </nav>
    <main>