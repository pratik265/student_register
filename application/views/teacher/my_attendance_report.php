<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Attendance Report</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Roboto', sans-serif; margin: 0; background-color: #f7f9fc; }
        .navbar { background-color: #34495e; overflow: hidden; }
        .navbar a { float: left; display: block; color: white; text-align: center; padding: 14px 20px; text-decoration: none; }
        .navbar a:hover { background-color: #2c3e50; }
        .navbar .logout { float: right; }
        .container { padding: 30px; max-width: 900px; margin: auto; }
        .header { color: #2c3e50; font-size: 28px; font-weight: 700; margin-bottom: 20px; text-align: center; }
        .summary-cards { display: flex; gap: 20px; justify-content: center; }
        .card { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: center; flex-grow: 1; }
        .card-title { font-size: 18px; color: #7f8c8d; margin-bottom: 10px; }
        .card-value { font-size: 36px; font-weight: 700; }
        .card.present .card-value { color: #27ae60; }
        .card.absent .card-value { color: #e74c3c; }
        .card.leave .card-value { color: #f39c12; }
    </style>
</head>
<body>

<div class="navbar">
    <a href="<?php echo site_url('teacher/dashboard'); ?>">Dashboard</a>
    <a href="<?php echo site_url('teacher/my_schedule'); ?>">My Schedule</a>
    <a href="<?php echo site_url('teacher/my_attendance_report'); ?>">My Attendance Report</a>
    <a href="<?php echo site_url('auth/logout'); ?>" class="logout">Logout</a>
</div>

<div class="container">
    <h2 class="header">My Attendance Summary (Current Month)</h2>
    <?php
        $summary = ['Present' => 0, 'Absent' => 0, 'Leave' => 0];
        if (!empty($teacher_summary)) {
            foreach ($teacher_summary as $item) {
                if (isset($summary[$item->status])) {
                    $summary[$item->status] = $item->count;
                }
            }
        }
    ?>
    <div class="summary-cards">
        <div class="card present">
            <div class="card-title">Present</div>
            <div class="card-value"><?php echo $summary['Present']; ?></div>
        </div>
        <div class="card absent">
            <div class="card-title">Absent</div>
            <div class="card-value"><?php echo $summary['Absent']; ?></div>
        </div>
        <div class="card leave">
            <div class="card-title">On Leave</div>
            <div class="card-value"><?php echo $summary['Leave']; ?></div>
        </div>
    </div>
</div>

</body>
</html> 