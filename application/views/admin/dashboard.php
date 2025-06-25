<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Gynandeep Vidhyalaya</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f4f6f8;
        }
        .sidebar {
            position: fixed;
            left: 0; top: 0; bottom: 0;
            width: 220px;
            background: #2980b9;
            color: #fff;
            padding-top: 40px;
            box-shadow: 2px 0 12px rgba(0,0,0,0.07);
        }
        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 22px;
            letter-spacing: 1px;
        }
        .sidebar a {
            display: block;
            color: #fff;
            text-decoration: none;
            padding: 15px 30px;
            font-size: 17px;
            transition: background 0.2s;
        }
        .sidebar a:hover, .sidebar a.active {
            background: #1abc9c;
        }
        .main {
            margin-left: 220px;
            padding: 0;
        }
        .header {
            background: url('https://images.unsplash.com/photo-1503676382389-4809596d5290?auto=format&fit=crop&w=1200&q=80') no-repeat center center;
            background-size: cover;
            padding: 40px 40px 30px 40px;
            color: #fff;
            border-bottom-left-radius: 30px;
            box-shadow: 0 4px 24px rgba(41,128,185,0.08);
        }
        .header h1 {
            margin: 0;
            font-size: 32px;
            letter-spacing: 1px;
        }
        .header .welcome {
            font-size: 18px;
            margin-top: 10px;
        }
        .stats {
            display: flex;
            gap: 30px;
            margin: 40px 40px 0 40px;
            flex-wrap: wrap;
        }
        .card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
            flex: 1 1 220px;
            min-width: 220px;
            padding: 30px 25px;
            display: flex;
            align-items: center;
            gap: 18px;
        }
        .card .icon {
            font-size: 36px;
            color: #2980b9;
            background: #eaf6fb;
            border-radius: 50%;
            padding: 16px;
        }
        .card .info {
            flex: 1;
        }
        .card .info .label {
            font-size: 15px;
            color: #888;
        }
        .card .info .value {
            font-size: 28px;
            font-weight: bold;
            color: #222;
        }
        @media (max-width: 900px) {
            .stats { flex-direction: column; gap: 20px; }
            .main { margin-left: 0; }
            .sidebar { position: static; width: 100%; height: auto; display: flex; flex-direction: row; }
            .sidebar h2 { display: none; }
            .sidebar a { flex: 1; text-align: center; padding: 12px 0; }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2><i class="fa fa-school"></i> Admin Panel</h2>
        <a href="<?php echo site_url('admin/dashboard'); ?>" class="<?php echo (uri_string() == 'admin/dashboard') ? 'active' : ''; ?>"><i class="fa fa-tachometer-alt"></i> Dashboard</a>
        <a href="<?php echo site_url('admin/students'); ?>" class="<?php echo (uri_string() == 'admin/students') ? 'active' : ''; ?>"><i class="fa fa-users"></i> Students</a>
        <a href="<?php echo site_url('admin/teachers'); ?>" class="<?php echo (uri_string() == 'admin/teachers') ? 'active' : ''; ?>"><i class="fa fa-chalkboard-teacher"></i> Teachers</a>
        <a href="<?php echo site_url('admin/attendance'); ?>" class="<?php echo (uri_string() == 'admin/attendance') ? 'active' : ''; ?>"><i class="fa fa-calendar-check"></i> Attendance</a>
        <a href="<?php echo site_url('auth/logout'); ?>"><i class="fa fa-sign-out-alt"></i> Logout</a>
    </div>
    <div class="main">
        <div class="header">
            <h1>Gynandeep Vidhyalaya</h1>
            <div class="welcome">Welcome, <?php echo $this->session->userdata('username'); ?>!</div>
        </div>
        <div class="stats">
            <div class="card">
                <div class="icon"><i class="fa fa-users"></i></div>
                <div class="info">
                    <div class="label">Total Students</div>
                    <div class="value"><?php echo isset($total_students) ? $total_students : 0; ?></div>
                </div>
            </div>
            <div class="card">
                <div class="icon"><i class="fa fa-chalkboard-teacher"></i></div>
                <div class="info">
                    <div class="label">Total Teachers</div>
                    <div class="value"><?php echo isset($total_teachers) ? $total_teachers : 0; ?></div>
                </div>
            </div>
            <div class="card">
                <div class="icon"><i class="fa fa-user-check"></i></div>
                <div class="info">
                    <div class="label">Student Attendance Records</div>
                    <div class="value"><?php echo isset($total_student_attendance) ? $total_student_attendance : 0; ?></div>
                </div>
            </div>
            <div class="card">
                <div class="icon"><i class="fa fa-user-tie"></i></div>
                <div class="info">
                    <div class="label">Teacher Attendance Records</div>
                    <div class="value"><?php echo isset($total_teacher_attendance) ? $total_teacher_attendance : 0; ?></div>
                </div>
            </div>
            <div class="card">
                <div class="icon"><i class="fa fa-calendar-alt"></i></div>
                <div class="info">
                    <div class="label">Total Leave Reports</div>
                    <div class="value"><?php echo isset($total_leaves) ? $total_leaves : 0; ?></div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 