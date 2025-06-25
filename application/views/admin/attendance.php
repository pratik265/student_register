<?php $this->load->view('layouts/sidebar'); ?>
<?php $this->load->view('layouts/topbar'); ?>

<div style="margin-left:220px; padding-top:64px; min-height:100vh; background:#f7f9fb;">
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6f8; margin: 0; }
        .container { max-width: 1200px; margin: 40px auto; background: #fff; border-radius: 12px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); padding: 40px; }
        h2 { color: #2980b9; margin-bottom: 30px; }
        .filter-bar { margin-bottom: 20px; display: flex; gap: 16px; align-items: center; }
        .filter-bar input, .filter-bar select { padding: 8px 12px; border-radius: 5px; border: 1px solid #ccc; font-size: 15px; }
        .filter-bar button { background: #2980b9; color: #fff; border: none; padding: 8px 18px; border-radius: 5px; font-size: 15px; cursor: pointer; }
        .filter-bar button:hover { background: #1abc9c; }
        .add-attendance-btn { float: right; background: #2980b9; color: #fff; border: none; padding: 10px 22px; border-radius: 6px; font-size: 16px; font-weight: bold; cursor: pointer; transition: background 0.2s; margin-bottom: 20px; }
        .add-attendance-btn:hover { background: #1abc9c; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 14px 10px; text-align: left; border-bottom: 1px solid #eaeaea; }
        th { background: #f4f6f8; color: #2980b9; font-size: 16px; }
        tr:hover { background: #f9fafb; }
        .actions button { background: none; border: none; cursor: pointer; margin-right: 8px; font-size: 18px; }
        .actions .edit { color: #f39c12; }
        .actions .delete { color: #e74c3c; }
        .actions .view { color: #2980b9; }
        .avatar { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; margin-right: 8px; }
        /* Modal styles */
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background: rgba(0,0,0,0.3); }
        .modal-content { background: #fff; margin: 60px auto; padding: 30px 30px 20px 30px; border-radius: 10px; max-width: 700px; position: relative; }
        .close { position: absolute; right: 18px; top: 10px; font-size: 28px; color: #888; cursor: pointer; }
        .modal h3 { margin-top: 0; color: #2980b9; }
        .tabs { display: flex; border-bottom: 2px solid #eaeaea; margin-bottom: 20px; }
        .tab-btn { flex: 1; padding: 12px 0; background: none; border: none; font-size: 18px; color: #2980b9; cursor: pointer; border-bottom: 3px solid transparent; transition: border 0.2s, color 0.2s; }
        .tab-btn.active { color: #1abc9c; border-bottom: 3px solid #1abc9c; font-weight: bold; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        .attendance-table th, .attendance-table td { padding: 10px 8px; }
        .attendance-table th { color: #1abc9c; }
        .attendance-table .avatar { width: 32px; height: 32px; }
        .attendance-radio { margin-right: 10px; }
        @media (max-width: 900px) { .container { padding: 10px; } th, td { font-size: 14px; } .filter-bar { flex-direction: column; gap: 8px; align-items: flex-start; } .modal-content { padding: 10px; } }
    </style>
</head>
<body>
    <div class="container">
        <h2>Attendance Management
            <button class="add-attendance-btn" id="addAttendanceBtn"><i class="fa fa-plus"></i> Add Attendance</button>
        </h2>
        <div class="filter-bar">
            <input type="date" id="filterDate" value="<?php echo html_escape($filters['date']); ?>">
            <select id="filterClass">
                <option value="">All Classes</option>
            </select>
            <select id="filterSection">
                <option value="">All Sections</option>
            </select>
            <select id="filterRole">
                <option value="">All Roles</option>
                <option value="Student">Student</option>
                <option value="Teacher">Teacher</option>
            </select>
            <button id="filterBtn"><i class="fa fa-search"></i> Filter</button>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Lecture</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="attendanceTableBody">
                <?php if (!empty($attendance_records)): ?>
                    <?php foreach ($attendance_records as $record): ?>
                        <tr>
                            <td><?php echo html_escape($record->name); ?></td>
                            <td><?php echo html_escape($record->role); ?></td>
                            <td><?php echo html_escape($record->date); ?></td>
                            <td><?php echo html_escape($record->status); ?></td>
                            <td><?php echo html_escape($record->lecture); ?></td>
                            <td class="actions">
                                <button class="view" onclick="viewAttendance(<?php echo $record->id; ?>)"><i class="fa fa-eye"></i></button>
                                <button class="edit" onclick="editAttendance(<?php echo $record->id; ?>)"><i class="fa fa-edit"></i></button>
                                <button class="delete" onclick="deleteAttendance(<?php echo $record->id; ?>)"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align:center;">No attendance records found for the selected date.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Attendance Modal -->
    <div id="attendanceModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeAttendanceModal">&times;</span>
            <h3>Mark Attendance</h3>
            <div id="student-filters">
                <div class="filter-bar">
                    <input type="date" id="attendanceDate" value="<?php echo date('Y-m-d'); ?>">
                    <select id="attendanceClass">
                        <option value="">Select Class</option>
                    </select>
                    <select id="attendanceSection">
                        <option value="">Select Section</option>
                    </select>
                </div>
            </div>
            <div class="tabs">
                <button class="tab-btn active" id="studentTabBtn">Student</button>
                <button class="tab-btn" id="teacherTabBtn">Teacher</button>
            </div>
            <div class="tab-content active" id="studentTab">
                <table class="attendance-table" style="width:100%">
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Lecture</th>
                        </tr>
                    </thead>
                    <tbody id="studentAttendanceBody">
                        <!-- Dynamic student rows -->
                    </tbody>
                </table>
            </div>
            <div class="tab-content" id="teacherTab">
                <table class="attendance-table" style="width:100%">
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Lecture</th>
                        </tr>
                    </thead>
                    <tbody id="teacherAttendanceBody">
                        <!-- Dynamic teacher rows -->
                    </tbody>
                </table>
            </div>
            <button class="add-attendance-btn" id="saveAttendanceBtn" style="margin-top:20px;">Save Attendance</button>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.js"></script>
    <script>
    $(document).ready(function() {
        const defaultAvatar = 'https://ui-avatars.com/api/?name=User&background=2980b9&color=fff&rounded=true&size=64';
        let currentTab = 'student';

        const teacherTab = $('#teacherTab');
        const studentAttendanceBody = $('#studentAttendanceBody');
        const teacherAttendanceBody = $('#teacherAttendanceBody');
        const studentFilters = $('#student-filters');

        // Load initial data
        loadClasses();
        // Attendance data is now pre-loaded by PHP. 
        // We only need to load it dynamically when filters change.

        // Event listeners
        $('#filterBtn').click(loadAttendanceData);
        
        $('#addAttendanceBtn').click(() => {
            $('#attendanceModal').show();
            // Reset to student tab view
            $('#studentTabBtn').addClass('active').siblings().removeClass('active');
            $('#studentTab').addClass('active').siblings().removeClass('active');
            studentFilters.show();
            studentAttendanceBody.html('<tr><td colspan="4" style="text-align:center;">Please select a class to see students.</td></tr>');
            teacherAttendanceBody.html('');
            currentTab = 'student';
        });

        $('#closeAttendanceModal').click(() => $('#attendanceModal').hide());
        $(window).click(e => { if (e.target.id == 'attendanceModal') $('#attendanceModal').hide(); });

        $('#studentTabBtn, #teacherTabBtn').click(function() {
            $('.tab-btn').removeClass('active');
            $(this).addClass('active');
            $('.tab-content').removeClass('active');
            currentTab = this.id === 'studentTabBtn' ? 'student' : 'teacher';

            if(currentTab === 'student') {
                $('#studentTab').addClass('active');
                studentFilters.show();
                studentAttendanceBody.html('<tr><td colspan="4" style="text-align:center;">Please select a class to see students.</td></tr>');
            } else {
                $('#teacherTab').addClass('active');
                studentFilters.hide();
                loadTeacherAttendanceList();
            }
        });

        $('#attendanceClass').change(function() {
            loadSections($(this).val(), () => {
                if(currentTab === 'student') {
                    loadStudentAttendanceList();
                }
            });
        });

        $('#attendanceSection').change(() => {
            if(currentTab === 'student') {
                loadStudentAttendanceList();
            }
        });

        $('#saveAttendanceBtn').click(saveAttendance);

        function loadClasses() {
            $.get('<?php echo base_url("admin/classes_json"); ?>', function(classes) {
                // Ensure we have a valid array
                if (!Array.isArray(classes)) {
                    console.error("Failed to load classes: Invalid data format.");
                    return;
                }

                const classOptions = classes.map(c => {
                    // Fallback for missing name to prevent "undefined"
                    const name = c.name || `Class #${c.id}`;
                    return `<option value="${c.id}">${name}</option>`;
                }).join('');

                // Clear existing options (keeping the placeholder) and add new ones
                $('#filterClass, #attendanceClass').each(function() {
                    const placeholder = $(this).find('option:first-child');
                    $(this).empty().append(placeholder).append(classOptions);
                });
            }, 'json');
        }

        function loadSections(classId, callback) {
            if(!classId) {
                $('#filterSection, #attendanceSection').html('<option value="">Select Section</option>');
                if (callback) callback();
                return;
            };
            $.get(`<?php echo base_url("admin/sections_by_class_json/"); ?>${classId}`, function(sections) {
                const sectionOptions = sections.map(s => {
                    const name = s.section_name || `Section #${s.id}`;
                    return `<option value="${s.id}">${name}</option>`;
                }).join('');
                $('#filterSection, #attendanceSection').html('<option value="">Select Section</option>' + sectionOptions);
                 if (callback) callback();
            }, 'json');
        }

        function loadAttendanceData() {
            const filters = {
                date: $('#filterDate').val(),
                class_id: $('#filterClass').val(),
                section_id: $('#filterSection').val(),
                role: $('#filterRole').val()
            };
            
            $.get('<?php echo base_url("admin/get_attendance_json"); ?>', filters, function(data) {
                const rows = data.map(record => `
                    <tr>
                        <td>${record.name}</td>
                        <td>${record.role}</td>
                        <td>${record.date}</td>
                        <td>${record.status}</td>
                        <td>${record.lecture}</td>
                        <td class="actions">
                            <button class="view" onclick="viewAttendance(${record.id})"><i class="fa fa-eye"></i></button>
                            <button class="edit" onclick="editAttendance(${record.id})"><i class="fa fa-edit"></i></button>
                            <button class="delete" onclick="deleteAttendance(${record.id})"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                `).join('');
                $('#attendanceTableBody').html(rows);
            }, 'json');
        }

        function loadStudentAttendanceList() {
            const classId = $('#attendanceClass').val();
            const sectionId = $('#attendanceSection').val();
            
            if(!classId) {
                studentAttendanceBody.html('<tr><td colspan="4" style="text-align:center;">Please select a class to see students.</td></tr>');
                return;
            }
            
            studentAttendanceBody.html('<tr><td colspan="4" style="text-align:center;">Loading...</td></tr>');
            $.get('<?php echo base_url("admin/all_students_json"); ?>', { class_id: classId, section_id: sectionId }, function(students) {
                if(students.length === 0){
                    studentAttendanceBody.html('<tr><td colspan="4" style="text-align:center;">No students found.</td></tr>');
                    return;
                }
                const rows = students.map(stu => `
                    <tr data-id="${stu.id}">
                        <td><img src="${stu.profile_image ? '<?php echo base_url(); ?>' + stu.profile_image : defaultAvatar}" class="avatar"></td>
                        <td>${stu.name}</td>
                        <td>
                            <label><input type="radio" name="stu${stu.id}_status" value="Present" class="attendance-radio"> Present</label>
                            <label><input type="radio" name="stu${stu.id}_status" value="Absent" class="attendance-radio"> Absent</label>
                            <label><input type="radio" name="stu${stu.id}_status" value="Leave" class="attendance-radio"> Leave</label>
                        </td>
                        <td><input type="number" min="1" max="10" value="1" style="width:60px;" class="lecture-input"></td>
                    </tr>
                `).join('');
                $('#studentAttendanceBody').html(rows);
            }, 'json');
        }

        function loadTeacherAttendanceList() {
            teacherAttendanceBody.html('<tr><td colspan="4" style="text-align:center;">Loading...</td></tr>');
            $.get('<?php echo base_url("admin/all_teachers_json"); ?>', function(teachers) {
                if(teachers.length === 0){
                    teacherAttendanceBody.html('<tr><td colspan="4" style="text-align:center;">No teachers found.</td></tr>');
                    return;
                }
                const rows = teachers.map(teach => `
                    <tr data-id="${teach.id}">
                        <td><img src="${teach.profile_image ? '<?php echo base_url(); ?>' + teach.profile_image : defaultAvatar}" class="avatar"></td>
                        <td>${teach.name}</td>
                        <td>
                            <label><input type="radio" name="teach${teach.id}_status" value="Present" class="attendance-radio"> Present</label>
                            <label><input type="radio" name="teach${teach.id}_status" value="Absent" class="attendance-radio"> Absent</label>
                            <label><input type="radio" name="teach${teach.id}_status" value="Leave" class="attendance-radio"> Leave</label>
                        </td>
                        <td><input type="number" min="1" max="10" value="1" style="width:60px;" class="lecture-input"></td>
                    </tr>
                `).join('');
                $('#teacherAttendanceBody').html(rows);
            }, 'json');
        }

        function saveAttendance() {
            const date = $('#attendanceDate').val();
            const classId = $('#attendanceClass').val();
            const sectionId = $('#attendanceSection').val();
            
            if(currentTab === 'student' && (!classId || !sectionId)) {
                Swal.fire('Error', 'Please select class and section', 'error');
                return;
            }

            const data = {
                date: date,
                class_id: classId,
                section_id: sectionId
            };

            if(currentTab === 'student') {
                data.student_ids = [];
                data.status = {};
                data.lecture = {};
                
                $('#studentAttendanceBody tr').each(function() {
                    const id = $(this).data('id');
                    const status = $(this).find('input[type="radio"]:checked').val();
                    const lecture = $(this).find('.lecture-input').val();
                    
                    if(status) {
                        data.student_ids.push(id);
                        data.status[id] = status;
                        data.lecture[id] = lecture;
                    }
                });
            } else {
                data.teacher_ids = [];
                data.status = {};
                data.lecture = {};
                
                $('#teacherAttendanceBody tr').each(function() {
                    const id = $(this).data('id');
                    const status = $(this).find('input[type="radio"]:checked').val();
                    const lecture = $(this).find('.lecture-input').val();
                    
                    if(status) {
                        data.teacher_ids.push(id);
                        data.status[id] = status;
                        data.lecture[id] = lecture;
                    }
                });
            }

            $.post('<?php echo base_url("admin/add_attendance_json"); ?>', data, function(response) {
                if(response.status === 'success') {
                    Swal.fire('Success', 'Attendance saved successfully', 'success');
                    $('#attendanceModal').hide();
                    loadAttendanceData();
                } else {
                    Swal.fire('Error', 'Failed to save attendance', 'error');
                }
            }, 'json');
        }

        window.viewAttendance = function(id) {
            $.get(`<?php echo base_url("admin/get_attendance_by_id_json/"); ?>${id}`, function(attendance) {
                Swal.fire({
                    title: 'Attendance Details',
                    html: `
                        <div style="text-align: left;">
                            <p><strong>Name:</strong> ${attendance.name}</p>
                            <p><strong>Date:</strong> ${attendance.date}</p>
                            <p><strong>Status:</strong> ${attendance.status}</p>
                            <p><strong>Lecture:</strong> ${attendance.lecture}</p>
                        </div>
                    `,
                    icon: 'info'
                });
            }, 'json');
        };

        window.editAttendance = function(id) {
            $.get(`<?php echo base_url("admin/get_attendance_by_id_json/"); ?>${id}`, function(attendance) {
                Swal.fire({
                    title: 'Edit Attendance',
                    html: `
                        <div style="text-align: left;">
                            <p><strong>Status:</strong></p>
                            <label><input type="radio" name="edit_status" value="Present" ${attendance.status === 'Present' ? 'checked' : ''}> Present</label>
                            <label><input type="radio" name="edit_status" value="Absent" ${attendance.status === 'Absent' ? 'checked' : ''}> Absent</label>
                            <label><input type="radio" name="edit_status" value="Leave" ${attendance.status === 'Leave' ? 'checked' : ''}> Leave</label>
                            <p><strong>Lecture:</strong></p>
                            <input type="number" id="edit_lecture" value="${attendance.lecture}" min="1" max="10">
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Save',
                    preConfirm: () => {
                        const status = document.querySelector('input[name="edit_status"]:checked').value;
                        const lecture = document.getElementById('edit_lecture').value;
                        return { status, lecture };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post('<?php echo base_url("admin/update_attendance_json"); ?>', {
                            id: id,
                            status: result.value.status,
                            lecture: result.value.lecture
                        }, function(response) {
                            if(response.status === 'success') {
                                Swal.fire('Success', 'Attendance updated successfully', 'success');
                                loadAttendanceData();
                            } else {
                                Swal.fire('Error', 'Failed to update attendance', 'error');
                            }
                        }, 'json');
                    }
                });
            }, 'json');
        };

        window.deleteAttendance = function(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('<?php echo base_url("admin/delete_attendance_json"); ?>', { id: id }, function(response) {
                        if(response.status === 'success') {
                            Swal.fire('Deleted!', 'Attendance record has been deleted.', 'success');
                            loadAttendanceData();
                        } else {
                            Swal.fire('Error', 'Failed to delete attendance', 'error');
                        }
                    }, 'json');
                }
            });
        };
    });
    </script>
</body>
</html>
</div> 