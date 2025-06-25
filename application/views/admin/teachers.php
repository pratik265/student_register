<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teachers - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6f8; margin: 0; }
        .container { max-width: 1100px; margin: 40px auto; background: #fff; border-radius: 12px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); padding: 40px; }
        h2 { color: #2980b9; margin-bottom: 30px; }
        .add-btn { float: right; background: #8e44ad; color: #fff; border: none; padding: 10px 22px; border-radius: 6px; font-size: 16px; font-weight: bold; cursor: pointer; transition: background 0.2s; margin-bottom: 20px; }
        .add-btn:hover { background: #6c3483; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 14px 10px; text-align: left; border-bottom: 1px solid #eaeaea; }
        th { background: #f4f6f8; color: #8e44ad; font-size: 16px; }
        tr:hover { background: #f9fafb; }
        .actions button { background: none; border: none; cursor: pointer; margin-right: 8px; font-size: 18px; }
        .actions .edit { color: #f39c12; }
        .actions .delete { color: #e74c3c; }
        .actions .view { color: #8e44ad; }
        .avatar { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; margin-right: 8px; }
        /* Modal styles */
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background: rgba(0,0,0,0.3); }
        .modal-content { background: #fff; margin: 60px auto; padding: 30px 30px 20px 30px; border-radius: 10px; max-width: 500px; position: relative; }
        .close { position: absolute; right: 18px; top: 10px; font-size: 28px; color: #888; cursor: pointer; }
        .modal h3 { margin-top: 0; color: #8e44ad; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; margin-bottom: 6px; color: #34495e; }
        .form-group input, .form-group textarea { width: 100%; padding: 8px; border-radius: 5px; border: 1px solid #ccc; font-size: 15px; }
        .form-group input[type="file"] { padding: 3px; }
        .modal-btn { background: #8e44ad; color: #fff; border: none; padding: 10px 22px; border-radius: 6px; font-size: 16px; font-weight: bold; cursor: pointer; transition: background 0.2s; }
        .modal-btn:hover { background: #6c3483; }
        .img-preview { margin-top: 8px; max-width: 80px; border-radius: 8px; }
        @media (max-width: 900px) { .container { padding: 10px; } th, td { font-size: 14px; } }
    </style>
</head>
<body>
    <div class="container">
        <h2>Teachers Management
            <button class="add-btn" id="addTeacherBtn"><i class="fa fa-plus"></i> Add Teacher</button>
        </h2>
        <table id="teachersTable">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>Assigned Classes/Subjects</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Dynamic rows -->
            </tbody>
        </table>
    </div>

    <!-- Teacher Modal -->
    <div id="teacherModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeTeacherModal">&times;</span>
            <h3 id="teacherModalTitle">Add Teacher</h3>
            <form id="teacherForm" enctype="multipart/form-data">
                <input type="hidden" name="id" id="teacherId">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" id="teacher_name" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="teacher_email" required>
                </div>
                <div class="form-group">
                    <label>Contact</label>
                    <input type="text" name="contact" id="teacher_contact" required>
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <textarea name="address" id="teacher_address"></textarea>
                </div>
                <div class="form-group">
                    <label>Assigned Classes/Subjects</label>
                    <input type="text" name="assigned_classes" id="assigned_classes" placeholder="e.g. 10-A (Maths), 9-B (Science)">
                </div>
                <div class="form-group">
                    <label>Profile Image</label>
                    <input type="file" name="profile_image" id="teacher_profile_image">
                    <img id="teacherImgPreview" class="img-preview" style="display:none;"/>
                </div>
                <button type="submit" class="modal-btn">Save</button>
            </form>
        </div>
    </div>

    <script>
    // Modal logic
    const teacherModal = document.getElementById('teacherModal');
    const addTeacherBtn = document.getElementById('addTeacherBtn');
    const closeTeacherModal = document.getElementById('closeTeacherModal');
    const teacherForm = document.getElementById('teacherForm');
    const teacherImgPreview = document.getElementById('teacherImgPreview');
    let editingTeacherId = null;

    addTeacherBtn.onclick = function() {
        openTeacherModal();
    };
    closeTeacherModal.onclick = function() {
        closeTeacherModalFunc();
    };
    window.onclick = function(event) {
        if (event.target == teacherModal) closeTeacherModalFunc();
    };
    function openTeacherModal(teacher = null) {
        teacherModal.style.display = 'block';
        document.getElementById('teacherModalTitle').innerText = teacher ? 'Edit Teacher' : 'Add Teacher';
        teacherForm.reset();
        teacherImgPreview.style.display = 'none';
        editingTeacherId = null;
        if (teacher) {
            editingTeacherId = teacher.id;
            document.getElementById('teacherId').value = teacher.id;
            document.getElementById('teacher_name').value = teacher.name;
            document.getElementById('teacher_email').value = teacher.email;
            document.getElementById('teacher_contact').value = teacher.contact;
            document.getElementById('teacher_address').value = teacher.address;
            document.getElementById('assigned_classes').value = teacher.assigned_classes || '';
            if (teacher.profile_image) {
                teacherImgPreview.src = `/student_register/${teacher.profile_image}`;
                teacherImgPreview.style.display = 'block';
            }
        }
    }
    function closeTeacherModalFunc() {
        teacherModal.style.display = 'none';
        teacherForm.reset();
        teacherImgPreview.style.display = 'none';
        editingTeacherId = null;
    }
    document.getElementById('teacher_profile_image').onchange = function(e) {
        if (e.target.files && e.target.files[0]) {
            let reader = new FileReader();
            reader.onload = function(ev) {
                teacherImgPreview.src = ev.target.result;
                teacherImgPreview.style.display = 'block';
            };
            reader.readAsDataURL(e.target.files[0]);
        }
    };
    // Load teachers
    function loadTeachers() {
        fetch('teachers_json')
            .then(res => res.json())
            .then(teachers => {
                let rows = '';
                teachers.forEach(teacher => {
                    rows += `<tr>
                        <td>${teacher.profile_image ? `<img src='/student_register/${teacher.profile_image}' class='avatar'/>` : ''}</td>
                        <td>${teacher.name}</td>
                        <td>${teacher.email}</td>
                        <td>${teacher.contact}</td>
                        <td>${teacher.assigned_classes || ''}</td>
                        <td class='actions'>
                            <button class='view' title='View' onclick='viewTeacher(${teacher.id})'><i class='fa fa-eye'></i></button>
                            <button class='edit' title='Edit' onclick='editTeacher(${teacher.id})'><i class='fa fa-edit'></i></button>
                            <button class='delete' title='Delete' onclick='deleteTeacher(${teacher.id})'><i class='fa fa-trash'></i></button>
                        </td>
                    </tr>`;
                });
                document.querySelector('#teachersTable tbody').innerHTML = rows;
            });
    }
    loadTeachers();
    // Add/Edit teacher
    teacherForm.onsubmit = function(e) {
        e.preventDefault();
        let formData = new FormData(teacherForm);
        let url = editingTeacherId ? `update_teacher/${editingTeacherId}` : 'add_teacher';
        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                closeTeacherModalFunc();
                loadTeachers();
            } else {
                alert(data.msg || 'Error!');
            }
        });
    };
    // Edit teacher
    window.editTeacher = function(id) {
        fetch(`edit_teacher/${id}`)
            .then(res => res.json())
            .then(teacher => {
                openTeacherModal(teacher);
            });
    };
    // Delete teacher
    window.deleteTeacher = function(id) {
        if (confirm('Are you sure you want to delete this teacher?')) {
            fetch(`delete_teacher/${id}`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') loadTeachers();
                    else alert('Delete failed!');
                });
        }
    };
    // View teacher (for now, just alert info)
    window.viewTeacher = function(id) {
        fetch(`edit_teacher/${id}`)
            .then(res => res.json())
            .then(teacher => {
                alert(`Name: ${teacher.name}\nEmail: ${teacher.email}\nContact: ${teacher.contact}\nAssigned: ${teacher.assigned_classes || ''}`);
            });
    };
    </script>
</body>
</html> 