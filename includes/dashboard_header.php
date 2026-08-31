<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Portal | PetCare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f1f5f9; }
        .top-bar {
            background: white;
            padding: 15px 40px;
            margin-left: 280px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        @media (max-width: 768px) { .top-bar { margin-left: 70px; } }
    </style>
</head>
<body>

<header class="top-bar sticky-top">
    <div class="search-area d-none d-md-block">
        <h5 class="mb-0 fw-bold text-primary">PetCare Portal</h5>
    </div>
    <div class="user-profile d-flex align-items-center">
        <div class="text-end me-3">
            <p class="mb-0 fw-bold small"><?php echo $_SESSION['name']; ?></p>
            <span class="badge bg-primary-subtle text-primary x-small" style="font-size: 10px;">
                <i class="fa fa-crown me-1"></i><?php echo strtoupper($_SESSION['role']); ?>
            </span>
        </div>
        <div class="avatar bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; border: 2px solid #e2e8f0;">
            <i class="fa-solid fa-user text-secondary"></i>
        </div>
    </div>
</header>