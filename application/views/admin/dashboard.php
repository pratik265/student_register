<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Gyanandeep Vidhyalaya</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700|Montserrat:700|Poppins:400,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', 'Roboto', sans-serif;
            background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%);
            min-height: 100vh;
            margin: 0;
            position: relative;
        }
        .sidebar {
            position: fixed;
            left: 0; top: 0; bottom: 0;
            width: 220px;
            background: linear-gradient(135deg, #2980b9 0%, #6dd5fa 100%);
            color: #fff;
            padding-top: 36px;
            box-shadow: 2px 0 12px rgba(41,128,185,0.08);
            z-index: 10;
        }
        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 22px;
            letter-spacing: 1px;
            font-weight: 800;
            color: #fff;
        }
        .sidebar a {
            display: block;
            color: #fff;
            text-decoration: none;
            padding: 15px 30px;
            font-size: 17px;
            transition: background 0.2s, color 0.2s;
            border-left: 4px solid transparent;
            margin-bottom: 4px;
        }
        .sidebar a:hover, .sidebar a.active {
            background: rgba(255,255,255,0.10);
            color: #ffe082;
            border-left: 4px solid #ffe082;
        }
        .sidebar i {
            margin-right: 12px;
            font-size: 1.2em;
        }
        .main-content {
            position: relative;
            z-index: 2;
            margin-left: 220px;
            padding: 0 40px 40px 40px;
        }
        .bg-layer {
            position: absolute;
            top: 0; left: 0; width: 100vw; height: 350px;
            background: linear-gradient(120deg, #667eea 0%, #764ba2 100%);
            background-image: url('https://images.unsplash.com/photo-1513258496099-48168024aec0?auto=format&fit=crop&w=1500&q=80');
            background-size: cover;
            background-repeat: no-repeat;
            background-blend-mode: overlay;
            z-index: 0;
            opacity: 0.85;
        }
        .dashboard-header {
            margin-top: 0;
            padding-top: 40px;
            color: #fff;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.12);
        }
        .dashboard-header h1 {
            font-size: 2.6rem;
            font-weight: 800;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }
        .dashboard-header p {
            font-size: 1.2rem;
            opacity: 0.95;
        }
        .stats-row {
            display: flex;
            flex-wrap: wrap;
            gap: 32px;
            margin-top: 40px;
        }
        .stat-card {
            flex: 1 1 220px;
            min-width: 220px;
            background: rgba(255,255,255,0.7);
            border-radius: 22px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.10);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.18);
            padding: 36px 28px 28px 28px;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-6px) scale(1.03);
            box-shadow: 0 16px 40px 0 rgba(31, 38, 135, 0.18);
        }
        .stat-icon {
            width: 60px; height: 60px;
            border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            font-size: 2.2rem;
            margin-bottom: 18px;
            color: #fff;
            box-shadow: 0 4px 16px rgba(102,126,234,0.18);
        }
        .stat-icon.students { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
        .stat-icon.teachers { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .stat-icon.stu-att { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .stat-icon.tea-att { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .stat-icon.leave { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: #fff; }
        .stat-label {
            font-size: 1.1rem;
            color: #333;
            font-weight: 600;
            margin-bottom: 6px;
            text-align: center;
        }
        .stat-value {
            font-size: 2.3rem;
            font-weight: 800;
            color: #222;
            margin-bottom: 2px;
            text-align: center;
        }
        @media (max-width: 1100px) {
            .main-content { margin-left: 0; padding: 0 10px 40px 10px; }
            .stats-row { gap: 18px; }
            .sidebar { position: static; width: 100%; height: auto; display: flex; flex-direction: row; }
            .sidebar h2 { display: none; }
            .sidebar a { flex: 1; text-align: center; padding: 12px 0; }
        }
        @media (max-width: 800px) {
            .stats-row { flex-direction: column; gap: 18px; }
            .main-content { padding: 0 2vw 40px 2vw; }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2><i class="fa fa-school"></i> Admin Panel</h2>
        <a href="<?php echo site_url('admin/dashboard'); ?>" class="<?php echo (uri_string() == 'admin/dashboard') ? 'active' : ''; ?>">
            <i class="fa fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="<?php echo site_url('admin/students'); ?>" class="<?php echo (uri_string() == 'admin/students') ? 'active' : ''; ?>">
            <i class="fa fa-users"></i> Students
        </a>
        <a href="<?php echo site_url('admin/teachers'); ?>" class="<?php echo (uri_string() == 'admin/teachers') ? 'active' : ''; ?>">
            <i class="fa fa-chalkboard-teacher"></i> Teachers
        </a>
        <a href="<?php echo site_url('admin/attendance'); ?>" class="<?php echo (uri_string() == 'admin/attendance') ? 'active' : ''; ?>">
            <i class="fa fa-calendar-check"></i> Attendance
        </a>
        <a href="<?php echo site_url('auth/logout'); ?>">
            <i class="fa fa-sign-out-alt"></i> Logout
        </a>
    </div>
    <div class="bg-layer"></div>
    <div class="main-content">
        <div class="dashboard-header">
            <h1>Gyanandeep Vidhyalaya</h1>
            <p>Welcome, admin!</p>
        </div>
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-icon students"><i class="fas fa-users"></i></div>
                <div class="stat-label">Total Students</div>
                <div class="stat-value"><?php echo $total_students; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon teachers"><i class="fas fa-chalkboard-teacher"></i></div>
                <div class="stat-label">Total Teachers</div>
                <div class="stat-value"><?php echo $total_teachers; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon stu-att"><i class="fas fa-user-check"></i></div>
                <div class="stat-label">Student Attendance Records</div>
                <div class="stat-value"><?php echo $total_student_attendance; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon tea-att"><i class="fas fa-user-tie"></i></div>
                <div class="stat-label">Teacher Attendance Records</div>
                <div class="stat-value"><?php echo $total_teacher_attendance; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon leave"><i class="fas fa-calendar-alt"></i></div>
                <div class="stat-label">Total Leave Reports</div>
                <div class="stat-value"><?php echo $total_leaves; ?></div>
            </div>
        </div>
    </div>
</body>
</html> 