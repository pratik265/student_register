<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background-color: #f4f4f4; }
        .navbar { background-color: #333; overflow: hidden; }
        .navbar a { float: left; display: block; color: white; text-align: center; padding: 14px 20px; text-decoration: none; }
        .navbar a:hover { background-color: #ddd; color: black; }
        .navbar .logout { float: right; }
        .content { padding: 20px; max-width: 500px; margin: auto; background-color: white; margin-top: 20px; border-radius: 8px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; }
        .form-group input { width: 100%; padding: 8px; box-sizing: border-box; }
        .btn { background-color: #333; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>

<div class="navbar">
    <a href="<?php echo site_url('teacher/dashboard'); ?>">Dashboard</a>
    <a href="<?php echo site_url('teacher/change_password'); ?>">Change Password</a>
    <a href="<?php echo site_url('auth/logout'); ?>" class="logout">Logout</a>
</div>

<div class="content">
    <h2>Change Your Password</h2>

    <?php if(isset($success_message)): ?>
        <p class="success"><?php echo $success_message; ?></p>
    <?php endif; ?>

    <?php echo validation_errors('<p class="error">', '</p>'); ?>

    <?php echo form_open('teacher/change_password'); ?>
        <div class="form-group">
            <label for="new_password">New Password</label>
            <input type="password" name="new_password" id="new_password">
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm New Password</label>
            <input type="password" name="confirm_password" id="confirm_password">
        </div>
        <button type="submit" class="btn">Update Password</button>
    </form>
</div>

</body>
</html> 