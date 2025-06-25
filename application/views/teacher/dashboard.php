<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Dashboard</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body { font-family: 'Roboto', sans-serif; margin: 0; background-color: #f7f9fc; }
        .navbar { background-color: #34495e; overflow: hidden; }
        .navbar a { float: left; display: block; color: white; text-align: center; padding: 14px 20px; text-decoration: none; }
        .navbar a:hover { background-color: #2c3e50; }
        .navbar .logout { float: right; }
        .container { padding: 30px; }
        .header { color: #2c3e50; font-size: 28px; font-weight: 700; margin-bottom: 20px; }
        .welcome { font-size: 20px; margin-bottom: 30px; }
        .dashboard-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
        .card { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-decoration: none; color: inherit; display: flex; align-items: center; gap: 20px; transition: transform 0.2s, box-shadow 0.2s; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
        .card .icon { font-size: 36px; color: #3498db; }
        .card-content h3 { margin: 0 0 5px 0; color: #2c3e50; }
        .card-content p { margin: 0; color: #7f8c8d; }
    </style>
</head>
<body>

<div class="navbar">
    <a href="<?php echo site_url('teacher/dashboard'); ?>">Dashboard</a>
    <a href="<?php echo site_url('teacher/change_password'); ?>">Change Password</a>
    <a href="<?php echo site_url('auth/logout'); ?>" class="logout">Logout</a>
</div>

<div class="container">
    <h2 class="header">Teacher Dashboard</h2>
    <p class="welcome">Welcome, <?php echo html_escape($username); ?>!</p>
    
    <div class="dashboard-cards">
        <a href="<?php echo site_url('teacher/my_schedule'); ?>" class="card">
            <div class="icon"><i class="fas fa-calendar-alt"></i></div>
            <div class="card-content">
                <h3>My Schedule</h3>
                <p>View your assigned classes and subjects.</p>
            </div>
        </a>
        <a href="<?php echo site_url('teacher/take_attendance'); ?>" class="card">
            <div class="icon"><i class="fas fa-user-check"></i></div>
            <div class="card-content">
                <h3>Take Attendance</h3>
                <p>Mark student attendance for your lectures.</p>
            </div>
        </a>
        <a href="<?php echo site_url('teacher/my_attendance_report'); ?>" class="card">
            <div class="icon"><i class="fas fa-chart-pie"></i></div>
            <div class="card-content">
                <h3>My Attendance Report</h3>
                <p>See a summary of your own attendance.</p>
            </div>
        </a>
    </div>
</div>

</body>
</html> 