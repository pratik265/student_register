<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Login - Gynandeep Vidhyalaya</title>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
        }
        .bg-image {
            background-image: url('https://images.pexels.com/photos/4144179/pexels-photo-4144179.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1');
            filter: blur(4px);
            -webkit-filter: blur(4px);
            height: 100%;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            position: absolute;
            width: 100%;
            z-index: -1;
        }
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }
        .login-box {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .school-title {
            font-size: 28px;
            font-weight: bold;
            color: #5e2ced; /* A modern purple */
            margin-bottom: 10px;
        }
        .login-box h2 {
            margin-top: 0;
            margin-bottom: 20px;
            color: #333;
        }
        .login-box p {
            color: #666;
            margin-bottom: 30px;
        }
        .login-box input[type="text"],
        .login-box input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box; /* Important */
        }
        .login-box button {
            width: 100%;
            padding: 12px;
            border: none;
            background-color: #5e2ced;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .login-box button:hover {
            background-color: #4b23b8;
        }
        .error {
            color: #e74c3c;
            background: #fdd;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="bg-image"></div>

    <div class="login-container">
        <div class="login-box">
            <div class="school-title">Gynandeep Vidhyalaya</div>
            <h2>Teacher Login</h2>
            <p>Please log in to continue</p>
            
            <?php if(isset($error)) { echo '<p class="error">'.$error.'</p>'; } ?>

            <form action="<?php echo base_url('auth/do_login/teacher'); ?>" method="post">
                <input type="text" name="username" placeholder="Enter Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>
        </div>
    </div>

</body>
</html> 