<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Students - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6f8; margin: 0; }
        .container { max-width: 1100px; margin: 40px auto; background: #fff; border-radius: 12px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); padding: 40px; }
        h2 { color: #2980b9; margin-bottom: 30px; }
        .add-btn { float: right; background: #27ae60; color: #fff; border: none; padding: 10px 22px; border-radius: 6px; font-size: 16px; font-weight: bold; cursor: pointer; transition: background 0.2s; margin-bottom: 20px; }
        .add-btn:hover { background: #219150; }
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
        .modal-content { background: #fff; margin: 60px auto; padding: 30px 30px 20px 30px; border-radius: 10px; max-width: 500px; position: relative; }
        .close { position: absolute; right: 18px; top: 10px; font-size: 28px; color: #888; cursor: pointer; }
        .modal h3 { margin-top: 0; color: #2980b9; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; margin-bottom: 6px; color: #34495e; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 8px; border-radius: 5px; border: 1px solid #ccc; font-size: 15px; }
        .form-group input[type="file"] { padding: 3px; }
        .modal-btn { background: #27ae60; color: #fff; border: none; padding: 10px 22px; border-radius: 6px; font-size: 16px; font-weight: bold; cursor: pointer; transition: background 0.2s; }
        .modal-btn:hover { background: #219150; }
        .img-preview { margin-top: 8px; max-width: 80px; border-radius: 8px; }
        @media (max-width: 900px) { .container { padding: 10px; } th, td { font-size: 14px; } }
    </style>
</head>
<body>
    <div class="container">
        <h2>Students Management
            <button class="add-btn" id="addStudentBtn"><i class="fa fa-plus"></i> Add Student</button>
        </h2>
        <table id="studentsTable">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Roll No</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Dynamic rows -->
            </tbody>
        </table>
    </div>

    <!-- Student Modal -->
    <div id="studentModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeModal">&times;</span>
            <h3 id="modalTitle">Add Student</h3>
            <form id="studentForm" enctype="multipart/form-data">
                <input type="hidden" name="id" id="studentId">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" id="name" required>
                </div>
                <div class="form-group">
                    <label>Class</label>
                    <select name="class_id" id="class_id" required></select>
                </div>
                <div class="form-group">
                    <label>Section</label>
                    <select name="section_id" id="section_id" required></select>
                </div>
                <div class="form-group">
                    <label>Roll No</label>
                    <input type="text" name="roll_no" id="roll_no" required>
                </div>
                <div class="form-group">
                    <label>Gender</label>
                    <select name="gender" id="gender" required>
                        <option value="">Select</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>DOB</label>
                    <input type="date" name="dob" id="dob">
                </div>
                <div class="form-group">
                    <label>Parent Name</label>
                    <input type="text" name="parent_name" id="parent_name">
                </div>
                <div class="form-group">
                    <label>Parent Contact</label>
                    <input type="text" name="parent_contact" id="parent_contact">
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <textarea name="address" id="address"></textarea>
                </div>
                <div class="form-group">
                    <label>Profile Image</label>
                    <input type="file" name="profile_image" id="profile_image">
                    <img id="imgPreview" class="img-preview" style="display:none;"/>
                </div>
                <button type="submit" class="modal-btn">Save</button>
            </form>
        </div>
    </div>

    <script>
    // Modal logic
    const modal = document.getElementById('studentModal');
    const addBtn = document.getElementById('addStudentBtn');
    const closeModal = document.getElementById('closeModal');
    const studentForm = document.getElementById('studentForm');
    const imgPreview = document.getElementById('imgPreview');
    let editingId = null;

    addBtn.onclick = function() {
        openModal();
    };
    closeModal.onclick = function() {
        closeStudentModal();
    };
    window.onclick = function(event) {
        if (event.target == modal) closeStudentModal();
    };
    // Load classes and sections for dropdowns
    let allSections = [];
    function loadClassesAndSections(selectedClassId = null, selectedSectionId = null) {
        fetch('classes_json')
            .then(res => res.json())
            .then(classes => {
                let classOptions = '<option value="">Select Class</option>';
                classes.forEach(cls => {
                    classOptions += `<option value="${cls.id}" ${selectedClassId == cls.id ? 'selected' : ''}>${cls.class_name}</option>`;
                });
                document.getElementById('class_id').innerHTML = classOptions;
                if (selectedClassId) {
                    loadSections(selectedClassId, selectedSectionId);
                } else {
                    document.getElementById('section_id').innerHTML = '<option value="">Select Section</option>';
                }
            });
        fetch('sections_json')
            .then(res => res.json())
            .then(sections => {
                allSections = sections;
            });
    }
    function loadSections(classId, selectedSectionId = null) {
        let sectionOptions = '<option value="">Select Section</option>';
        allSections.filter(sec => sec.class_id == classId).forEach(sec => {
            sectionOptions += `<option value="${sec.id}" ${selectedSectionId == sec.id ? 'selected' : ''}>${sec.section_name}</option>`;
        });
        document.getElementById('section_id').innerHTML = sectionOptions;
    }
    document.getElementById('class_id').onchange = function() {
        loadSections(this.value);
    };
    // Update openModal to set dropdowns
    function openModal(student = null) {
        modal.style.display = 'block';
        document.getElementById('modalTitle').innerText = student ? 'Edit Student' : 'Add Student';
        studentForm.reset();
        imgPreview.style.display = 'none';
        editingId = null;
        loadClassesAndSections(student ? student.class_id : null, student ? student.section_id : null);
        if (student) {
            editingId = student.id;
            document.getElementById('studentId').value = student.id;
            document.getElementById('name').value = student.name;
            document.getElementById('roll_no').value = student.roll_no;
            document.getElementById('gender').value = student.gender;
            document.getElementById('dob').value = student.dob;
            document.getElementById('parent_name').value = student.parent_name;
            document.getElementById('parent_contact').value = student.parent_contact;
            document.getElementById('address').value = student.address;
            if (student.profile_image) {
                imgPreview.src = `/student_register/${student.profile_image}`;
                imgPreview.style.display = 'block';
            }
        }
    }
    // On page load, preload classes/sections for add
    loadClassesAndSections();
    function closeStudentModal() {
        modal.style.display = 'none';
        studentForm.reset();
        imgPreview.style.display = 'none';
        editingId = null;
    }
    document.getElementById('profile_image').onchange = function(e) {
        if (e.target.files && e.target.files[0]) {
            let reader = new FileReader();
            reader.onload = function(ev) {
                imgPreview.src = ev.target.result;
                imgPreview.style.display = 'block';
            };
            reader.readAsDataURL(e.target.files[0]);
        }
    };
    // Load students
    function loadStudents() {
        fetch('students_json')
            .then(res => res.json())
            .then(students => {
                let rows = '';
                students.forEach(stu => {
                    rows += `<tr>
                        <td>${stu.profile_image ? `<img src='/student_register/${stu.profile_image}' class='avatar'/>` : ''}</td>
                        <td>${stu.name}</td>
                        <td>${stu.class_id}</td>
                        <td>${stu.section_id}</td>
                        <td>${stu.roll_no}</td>
                        <td class='actions'>
                            <button class='view' title='View' onclick='viewStudent(${stu.id})'><i class='fa fa-eye'></i></button>
                            <button class='edit' title='Edit' onclick='editStudent(${stu.id})'><i class='fa fa-edit'></i></button>
                            <button class='delete' title='Delete' onclick='deleteStudent(${stu.id})'><i class='fa fa-trash'></i></button>
                        </td>
                    </tr>`;
                });
                document.querySelector('#studentsTable tbody').innerHTML = rows;
            });
    }
    loadStudents();
    // Add/Edit student
    studentForm.onsubmit = function(e) {
        e.preventDefault();
        let formData = new FormData(studentForm);
        let url = editingId ? `update_student/${editingId}` : 'add_student';
        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                closeStudentModal();
                loadStudents();
            } else {
                alert(data.msg || 'Error!');
            }
        });
    };
    // Edit student
    window.editStudent = function(id) {
        fetch(`edit_student/${id}`)
            .then(res => res.json())
            .then(stu => {
                openModal(stu);
            });
    };
    // Delete student
    window.deleteStudent = function(id) {
        if (confirm('Are you sure you want to delete this student?')) {
            fetch(`delete_student/${id}`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') loadStudents();
                    else alert('Delete failed!');
                });
        }
    };
    // View student (for now, just alert info)
    window.viewStudent = function(id) {
        fetch(`edit_student/${id}`)
            .then(res => res.json())
            .then(stu => {
                alert(`Name: ${stu.name}\nClass: ${stu.class_id}\nSection: ${stu.section_id}\nRoll No: ${stu.roll_no}`);
            });
    };
    </script>
</body>
</html> 