<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Schedule</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Roboto', sans-serif; margin: 0; background-color: #f7f9fc; }
        .navbar { background-color: #34495e; overflow: hidden; }
        .navbar a { float: left; display: block; color: white; text-align: center; padding: 14px 20px; text-decoration: none; }
        .navbar a:hover { background-color: #2c3e50; }
        .navbar .logout { float: right; }
        .container { padding: 30px; max-width: 900px; margin: auto; }
        .header { color: #2c3e50; font-size: 28px; font-weight: 700; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        th, td { padding: 15px; text-align: left; }
        th { background-color: #eaf1f8; color: #34495e; font-weight: 700; }
        tr:nth-child(even) { background-color: #f8fafc; }
        tr:hover { background-color: #f1f5f9; }
        .empty-state { text-align: center; padding: 50px; background: white; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

<div class="navbar">
    <a href="<?php echo site_url('teacher/dashboard'); ?>">Dashboard</a>
    <a href="<?php echo site_url('teacher/my_schedule'); ?>">My Schedule</a>
    <a href="<?php echo site_url('teacher/my_attendance_report'); ?>">My Attendance</a>
    <a href="<?php echo site_url('auth/logout'); ?>" class="logout">Logout</a>
</div>

<div class="container">
    <h2 class="header">My Teaching Schedule</h2>
    <?php if (!empty($schedule)): ?>
        <table>
            <thead>
                <tr>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Subject</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($schedule as $item): ?>
                    <tr>
                        <td><?php echo html_escape($item->class_name); ?></td>
                        <td><?php echo html_escape($item->section_name); ?></td>
                        <td><?php echo html_escape($item->subject_name); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="empty-state">
            <p>Your teaching schedule has not been assigned yet.</p>
        </div>
    <?php endif; ?>
</div>

</body>
</html> 