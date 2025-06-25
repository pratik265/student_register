<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700|Montserrat:700|Poppins:400,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
        }
        
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 15px 0;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }
        
        .navbar a {
            color: #333;
            text-decoration: none;
            padding: 10px 20px;
            margin: 0 10px;
            border-radius: 25px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .navbar a:hover {
            background: #667eea;
            color: white;
        }
        
        .navbar .logout {
            background: #ff6b6b;
            color: white;
        }
        
        .navbar .logout:hover {
            background: #ff5252;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px;
        }
        
        .welcome-section {
            text-align: center;
            margin-bottom: 40px;
            color: white;
        }
        
        .welcome-section h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .welcome-section p {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .tab-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
        }
        
        .tab-nav {
            display: flex;
            background: rgba(102, 126, 234, 0.1);
            border-bottom: 1px solid rgba(102, 126, 234, 0.2);
        }
        
        .tab-btn {
            flex: 1;
            padding: 20px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            color: #666;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .tab-btn.active {
            color: #667eea;
            background: white;
        }
        
        .tab-btn.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: #667eea;
        }
        
        .tab-btn:hover {
            background: rgba(255, 255, 255, 0.5);
        }
        
        .tab-content {
            display: none;
            padding: 40px;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
        }
        
        .card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }
        
        .card-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .card-icon {
            width: 50px;
            height: 50px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1.5rem;
            color: white;
        }
        
        .lectures-icon { background: linear-gradient(45deg, #667eea, #764ba2); }
        .attendance-icon { background: linear-gradient(45deg, #f093fb, #f5576c); }
        .holidays-icon { background: linear-gradient(45deg, #4facfe, #00f2fe); }
        .profile-icon { background: linear-gradient(45deg, #43e97b, #38f9d7); }
        
        .card-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #333;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        
        .stat-item {
            text-align: center;
            padding: 15px;
            background: rgba(102, 126, 234, 0.1);
            border-radius: 15px;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: #666;
            font-weight: 500;
        }
        
        .profile-card {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(45deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            font-weight: 700;
        }
        
        .profile-info h3 {
            font-size: 1.5rem;
            margin-bottom: 5px;
            color: #333;
        }
        
        .profile-info p {
            color: #666;
            margin-bottom: 3px;
        }
        
        .holidays-list {
            max-height: 400px;
            overflow-y: auto;
        }
        
        .holiday-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            margin-bottom: 10px;
            background: rgba(102, 126, 234, 0.05);
            border-radius: 10px;
            border-left: 4px solid #667eea;
        }
        
        .holiday-name {
            font-weight: 500;
            color: #333;
        }
        
        .holiday-date {
            color: #667eea;
            font-weight: 600;
        }
        
        .subject-attendance {
            max-height: 300px;
            overflow-y: auto;
        }
        
        .subject-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 15px;
            margin-bottom: 8px;
            background: rgba(102, 126, 234, 0.05);
            border-radius: 10px;
            border-left: 4px solid #667eea;
        }
        
        .subject-name {
            font-weight: 500;
            color: #333;
        }
        
        .subject-count {
            background: #667eea;
            color: white;
            padding: 4px 12px;
            border-radius: 15px;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .attendance-detail-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .attendance-detail-card h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 1.2rem;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }
        
        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
            
            .welcome-section h1 {
                font-size: 2rem;
            }
            
            .tab-nav {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<div class="navbar">
    <a href="<?php echo site_url('student/dashboard'); ?>"><i class="fas fa-home"></i> Dashboard</a>
    <a href="<?php echo site_url('auth/logout'); ?>" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="container">
    <div class="welcome-section">
        <h1>Welcome, <?php echo html_escape($username); ?>!</h1>
        <p>Your Academic Journey Dashboard</p>
    </div>
    
    <div class="tab-container">
        <div class="tab-nav">
            <button class="tab-btn active" onclick="showTab('dashboard')">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </button>
            <button class="tab-btn" onclick="showTab('attendance')">
                <i class="fas fa-calendar-check"></i> Attendance
            </button>
            <button class="tab-btn" onclick="showTab('profile')">
                <i class="fas fa-user-graduate"></i> Profile
            </button>
            <button class="tab-btn" onclick="showTab('holidays')">
                <i class="fas fa-calendar-alt"></i> Holidays
            </button>
        </div>
        
        <!-- Dashboard Tab -->
        <div id="dashboard" class="tab-content active">
            <div class="dashboard-grid">
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon lectures-icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <h3 class="card-title">Today's Lectures</h3>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?php echo $today_lectures; ?></div>
                        <div class="stat-label">Total Lectures</div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon attendance-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <h3 class="card-title">Quick Stats</h3>
                    </div>
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-number"><?php echo $attendance_stats['today_present']; ?>/<?php echo $attendance_stats['today_total']; ?></div>
                            <div class="stat-label">Today</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number"><?php echo $attendance_stats['month_percentage']; ?>%</div>
                            <div class="stat-label">This Month</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number"><?php echo $attendance_stats['year_percentage']; ?>%</div>
                            <div class="stat-label">This Year</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Attendance Tab -->
        <div id="attendance" class="tab-content">
            <div class="attendance-detail-card">
                <h3><i class="fas fa-calendar-check"></i> Today's Attendance Summary</h3>
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-number"><?php echo $attendance_stats['today_present']; ?>/<?php echo $attendance_stats['today_total']; ?></div>
                        <div class="stat-label">Total Today</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?php echo $attendance_stats['today_percentage']; ?>%</div>
                        <div class="stat-label">Today's %</div>
                    </div>
                </div>
            </div>
            
            <div class="attendance-detail-card">
                <h3><i class="fas fa-book"></i> Subject-wise Attendance</h3>
                <?php if (!empty($subject_attendance)): ?>
                    <div class="subject-attendance">
                        <?php foreach ($subject_attendance as $subject): ?>
                            <div class="subject-item">
                                <span class="subject-name"><?php echo html_escape($subject->subject_name); ?></span>
                                <span class="subject-count"><?php echo $subject->attended; ?>/<?php echo $subject->total_lectures; ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-data">
                        <i class="fas fa-info-circle"></i> No attendance data available for today.
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Profile Tab -->
        <div id="profile" class="tab-content">
            <div class="card">
                <div class="card-header">
                    <div class="card-icon profile-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h3 class="card-title">Student Profile</h3>
                </div>
                <?php if ($student): ?>
                    <div class="profile-card">
                        <div class="profile-avatar">
                            <?php echo strtoupper(substr($student->name, 0, 1)); ?>
                        </div>
                        <div class="profile-info">
                            <h3><?php echo html_escape($student->name); ?></h3>
                            <p><strong>Roll No:</strong> <?php echo html_escape($student->roll_no); ?></p>
                            <p><strong>Class:</strong> <?php echo html_escape($class_name); ?></p>
                            <p><strong>Section:</strong> <?php echo html_escape($section_name); ?></p>
                            <p><strong>Student ID:</strong> <?php echo html_escape($student->id); ?></p>
                            <?php if (!empty($student->parent_name)): ?>
                                <p><strong>Parent Name:</strong> <?php echo html_escape($student->parent_name); ?></p>
                            <?php endif; ?>
                            <?php if (!empty($student->contact)): ?>
                                <p><strong>Contact:</strong> <?php echo html_escape($student->contact); ?></p>
                            <?php endif; ?>
                            <?php if (!empty($student->address)): ?>
                                <p><strong>Address:</strong> <?php echo html_escape($student->address); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="no-data">
                        <i class="fas fa-exclamation-triangle"></i> Student profile not found.
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Holidays Tab -->
        <div id="holidays" class="tab-content">
            <div class="card">
                <div class="card-header">
                    <div class="card-icon holidays-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h3 class="card-title">School Holidays <?php echo date('Y'); ?></h3>
                </div>
                <?php if (!empty($holidays)): ?>
                    <div class="holidays-list">
                        <?php foreach ($holidays as $holiday): ?>
                            <div class="holiday-item">
                                <div>
                                    <span class="holiday-name"><?php echo html_escape($holiday['name']); ?></span>
                                    <small style="color: #999; display: block; font-size: 0.8rem;">
                                        <?php echo html_escape($holiday['description']); ?>
                                        <?php if ($holiday['source'] == 'school'): ?>
                                            <span style="color: #667eea;">(School Holiday)</span>
                                        <?php endif; ?>
                                    </small>
                                </div>
                                <span class="holiday-date"><?php echo date('d M', strtotime($holiday['date'])); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-data">
                        <i class="fas fa-info-circle"></i> No holidays available for this year.
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Upcoming Holidays Section -->
            <?php if (!empty($upcoming_holidays)): ?>
                <div class="card" style="margin-top: 20px;">
                    <div class="card-header">
                        <div class="card-icon" style="background: linear-gradient(45deg, #ff6b6b, #ffa726);">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3 class="card-title">Upcoming Holidays</h3>
                    </div>
                    <div class="holidays-list">
                        <?php foreach ($upcoming_holidays as $holiday): ?>
                            <div class="holiday-item" style="border-left-color: #ff6b6b;">
                                <div>
                                    <span class="holiday-name"><?php echo html_escape($holiday['holiday_name']); ?></span>
                                    <small style="color: #999; display: block; font-size: 0.8rem;">
                                        <?php echo html_escape($holiday['description']); ?>
                                    </small>
                                </div>
                                <span class="holiday-date" style="color: #ff6b6b;"><?php echo date('d M', strtotime($holiday['holiday_date'])); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tab contents
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(content => {
        content.classList.remove('active');
    });
    
    // Remove active class from all tab buttons
    const tabButtons = document.querySelectorAll('.tab-btn');
    tabButtons.forEach(button => {
        button.classList.remove('active');
    });
    
    // Show selected tab content
    document.getElementById(tabName).classList.add('active');
    
    // Add active class to clicked button
    event.target.classList.add('active');
}
</script>

</body>
</html> 