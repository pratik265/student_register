<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Take Student Attendance</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Roboto', sans-serif; margin: 0; background-color: #f7f9fc; }
        .navbar { background-color: #34495e; overflow: hidden; }
        .navbar a { float: left; display: block; color: white; text-align: center; padding: 14px 20px; text-decoration: none; }
        .navbar a:hover { background-color: #2c3e50; }
        .navbar .logout { float: right; }
        .container { padding: 30px; max-width: 1100px; margin: auto; }
        .header { color: #2c3e50; font-size: 28px; font-weight: 700; margin-bottom: 20px; }
        .controls { display: flex; gap: 20px; margin-bottom: 20px; align-items: center; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .controls select, .controls input { padding: 10px; border-radius: 6px; border: 1px solid #ccc; font-size: 16px; }
        .save-btn { padding: 10px 20px; border: none; background-color: #27ae60; color: white; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: 700; }
        .save-btn:hover { background-color: #229954; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        th, td { padding: 15px; text-align: left; }
        th { background-color: #eaf1f8; color: #34495e; font-weight: 700; }
        tr:nth-child(even) { background-color: #f8fafc; }
        .avatar { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }
        .status-radio { display: flex; gap: 15px; align-items: center; }
        .status-radio label { cursor: pointer; }
    </style>
</head>
<body>

<div class="navbar">
    <a href="<?php echo site_url('teacher/dashboard'); ?>">Dashboard</a>
    <a href="<?php echo site_url('teacher/take_attendance'); ?>">Take Attendance</a>
    <a href="<?php echo site_url('auth/logout'); ?>" class="logout">Logout</a>
</div>

<div class="container">
    <h2 class="header">Take Student Attendance</h2>

    <div class="controls">
        <select id="schedule-select">
            <option value="">Select Class, Section & Subject</option>
            <?php if (!empty($schedule)): ?>
                <?php foreach($schedule as $item): ?>
                    <option value="<?php echo "{$item->class_id}|{$item->section_id}|{$item->subject_id}"; ?>">
                        <?php echo "Class {$item->class_name} - {$item->section_name} ({$item->subject_name})"; ?>
                    </option>
                <?php endforeach; ?>
            <?php else: ?>
                <option value="" disabled>No classes assigned to you</option>
            <?php endif; ?>
        </select>
        <input type="date" id="attendance-date" value="<?php echo date('Y-m-d'); ?>">
        <button class="save-btn" id="save-attendance-btn">Save Attendance</button>
    </div>

    <table>
        <thead>
            <tr>
                <th>Photo</th>
                <th>Name</th>
                <th>Present</th>
            </tr>
        </thead>
        <tbody id="student-list-body">
            <tr>
                <td colspan="4" style="text-align: center;">Please select a class to view students.</td>
            </tr>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    const defaultAvatar = 'https://ui-avatars.com/api/?name=User&background=2980b9&color=fff&rounded=true&size=64';

    function loadStudentList() {
        const selected = $('#schedule-select').val();
        const attendanceDate = $('#attendance-date').val();

        if (!selected || !attendanceDate) {
            $('#student-list-body').html('<tr><td colspan="3" style="text-align: center;">Please select a class and date to view students.</td></tr>');
            return;
        }

        const [classId, sectionId, subjectId] = selected.split('|');
        $('#student-list-body').html('<tr><td colspan="3" style="text-align: center;">Loading students...</td></tr>');

        $.get('<?php echo site_url("teacher/get_students_for_attendance"); ?>', { 
            class_id: classId, 
            section_id: sectionId,
            subject_id: subjectId,
            date: attendanceDate
        }, function(students) {
            if (students.length === 0) {
                $('#student-list-body').html('<tr><td colspan="3" style="text-align: center;">No students found for this section.</td></tr>');
                return;
            }

            const rows = students.map(student => `
                <tr data-student-id="${student.id}">
                    <td><img src="${student.profile_image ? '<?php echo base_url(); ?>' + student.profile_image : defaultAvatar}" class="avatar"></td>
                    <td>${student.name}</td>
                    <td>
                        <input type="checkbox" class="attendance-check" ${student.status === 'Present' ? 'checked' : ''}>
                    </td>
                </tr>
            `).join('');
            $('#student-list-body').html(rows);
        }, 'json');
    }

    $('#schedule-select').change(loadStudentList);
    $('#attendance-date').change(loadStudentList);

    $('#save-attendance-btn').click(function() {
        const selectedSchedule = $('#schedule-select').val();
        if (!selectedSchedule) {
            Swal.fire('Error', 'Please select a class and subject.', 'error');
            return;
        }

        const [classId, sectionId, subjectId] = selectedSchedule.split('|');
        const attendanceDate = $('#attendance-date').val();

        let attendanceData = [];

        $('#student-list-body tr').each(function() {
            const studentId = $(this).data('student-id');
            if (studentId) {
                attendanceData.push({
                    student_id: studentId,
                    status: $(this).find('.attendance-check').is(':checked') ? 'Present' : 'Absent'
                });
            }
        });

        if (attendanceData.length === 0) {
            Swal.fire('Error', 'No students to save attendance for.', 'error');
            return;
        }

        const payload = {
            attendance: attendanceData,
            date: attendanceDate,
            class_id: classId,
            section_id: sectionId,
            subject_id: subjectId,
        };

        $.post('<?php echo site_url("teacher/save_attendance"); ?>', payload, function(response) {
            if (response.status === 'success') {
                Swal.fire('Success!', 'Attendance has been saved successfully.', 'success');
            } else {
                Swal.fire('Error', 'Failed to save attendance. Please try again.', 'error');
            }
        }, 'json');
    });
});
</script>

</body>
</html> 