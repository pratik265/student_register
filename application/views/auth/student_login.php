<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Login</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background-color: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .login-header {
            margin-bottom: 30px;
        }
        .login-header .icon {
            font-size: 48px;
            color: #2575fc;
            margin-bottom: 10px;
        }
        .login-header h2 {
            color: #333;
            font-weight: 700;
            font-size: 24px;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
        }
        .form-group select,
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 16px;
        }
        .login-btn {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 8px;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: white;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            transition: opacity 0.3s;
        }
        .login-btn:hover {
            opacity: 0.9;
        }
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-header">
        <div class="icon"><i class="fas fa-user-graduate"></i></div>
        <h2>Student Portal</h2>
    </div>

    <?php if(isset($error)): ?>
        <div class="error-message"><?php echo html_escape($error); ?></div>
    <?php endif; ?>

    <?php echo form_open('auth/do_login/student'); ?>
        <div class="form-group">
            <label for="class_id">Class</label>
            <select id="class_id" name="class_id" required>
                <option value="">Select Class</option>
                <?php foreach ($classes as $class): ?>
                    <option value="<?php echo $class->id; ?>"><?php echo html_escape($class->class_name); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="section_id">Section</label>
            <select id="section_id" name="section_id" required>
                <option value="">Select Class First</option>
            </select>
        </div>
        <div class="form-group">
            <label for="roll_no">Roll Number</label>
            <input type="text" id="roll_no" name="roll_no" placeholder="Enter your roll number" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
        </div>
        <button type="submit" class="login-btn">Login</button>
    <?php echo form_close(); ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#class_id').change(function() {
        const classId = $(this).val();
        const sectionSelect = $('#section_id');
        sectionSelect.html('<option value="">Loading...</option>');

        if (!classId) {
            sectionSelect.html('<option value="">Select Class First</option>');
            return;
        }

        $.get('<?php echo site_url("auth/get_sections_json"); ?>/' + classId, function(sections) {
            sectionSelect.html('<option value="">Select Section</option>');
            if (sections.length > 0) {
                sections.forEach(function(section) {
                    sectionSelect.append(`<option value="${section.id}">${section.section_name}</option>`);
                });
            }
        }, 'json');
    });
});
</script>

</body>
</html> 