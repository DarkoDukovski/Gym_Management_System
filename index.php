<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT admin_id, password FROM admins WHERE username = ?";

    $run = $conn->prepare($sql);
    $run->bind_param("s", $username);
    $run->execute();

    $results = $run->get_result();

    if ($results->num_rows == 1) {
        $admin = $results->fetch_assoc();

        if (password_verify($password, $admin['password'])) {
            session_start();

            $_SESSION['admin_id'] = $admin['admin_id'];

            $conn->close();

            header('location: admin_dashboard.php');
            exit();
        } else {
            $_SESSION['error'] = "Incorrect password!";
            header('location: index.php');
            exit();
        }
    } else {
        $_SESSION['error'] = "Incorrect username!";
        header('location: index.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GymAdmin - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0
        }

        body {
            font-family: 'Inter', system-ui, sans-serif;
        }

        .login-page {
            min-height: 100vh;
            background: #0c0f16;
            display: flex;
            flex-direction: column;
        }

        .login-hero {
            display: flex;
            width: 100%;
            background: linear-gradient(160deg, #0c0f16 0%, #1a1207 50%, #0c0f16 100%);
            position: relative;
            overflow: hidden;
            padding: 3rem 1.5rem;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        @media(min-width: 768px) {
            .login-page {
                flex-direction: row;
            }

            .login-hero {
                width: 50%;
                padding: 3rem;
            }

            .login-form-side {
                width: 50%;
                padding: 3rem;
            }

            .hero-content h1 {
                font-size: 2.5rem;
            }
        }

        .login-hero::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -20%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(245, 158, 11, .08) 0%, transparent 70%);
            border-radius: 50%;
        }

        .login-hero::after {
            content: '';
            position: absolute;
            bottom: -20%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(245, 158, 11, .05) 0%, transparent 70%);
            border-radius: 50%;
        }

        .hero-content {
            position: relative;
            z-index: 2
        }

        .hero-content h1 {
            font-size: 2rem;
            font-weight: 800;
            color: #fff;
            line-height: 1.1;
            margin-bottom: 1rem;
        }

        .hero-content h1 span {
            color: #f59e0b
        }

        .hero-content p {
            color: #6b7280;
            font-size: .95rem;
            line-height: 1.6;
            max-width: 360px;
            margin-left: auto;
            margin-right: auto;
        }

        .feature-list {
            margin-top: 2.5rem;
            list-style: none;
            padding: 0;
            display: inline-block;
            text-align: left;
        }

        .feature-list li {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
            color: #9ca3af;
            font-size: .87rem;
        }

        .feature-list li i {
            color: #f59e0b;
            font-size: .75rem;
            width: 28px;
            height: 28px;
            background: rgba(245, 158, 11, .1);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .login-form-side {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5rem 1.5rem;
            flex: 1;
        }

        .login-box {
            width: 100%;
            max-width: 400px;
        }

        .brand-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 2rem;
        }

        .brand-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .brand-name {
            color: #fff;
            font-weight: 700;
            font-size: 1.2rem
        }

        .login-box h2 {
            color: #f9fafb;
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: .35rem;
        }

        .login-box .sub {
            color: #6b7280;
            font-size: .87rem;
            margin-bottom: 2rem
        }

        .input-group {
            margin-bottom: 1.25rem
        }

        .input-group label {
            display: block;
            color: #9ca3af;
            font-size: .78rem;
            font-weight: 500;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: .3px;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #4b5563;
            font-size: .85rem;
        }

        .input-wrap input {
            width: 100%;
            padding: 12px 14px 12px 42px;
            background: #151921;
            border: 1px solid #1e2430;
            border-radius: 12px;
            color: #e5e7eb;
            font-size: .9rem;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }

        .input-wrap input::placeholder {
            color: #4b5563
        }

        .input-wrap input:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, .1);
        }

        .login-submit {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: .9rem;
            cursor: pointer;
            transition: opacity .2s;
            margin-top: .5rem;
            letter-spacing: .2px;
        }

        .login-submit:hover {
            opacity: .9
        }

        .err-alert {
            background: rgba(239, 68, 68, .08);
            border: 1px solid rgba(239, 68, 68, .2);
            color: #fca5a5;
            padding: 11px 14px;
            border-radius: 12px;
            font-size: .84rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .footer-txt {
            text-align: center;
            color: #374151;
            font-size: .75rem;
            margin-top: 2.5rem;
        }
    </style>
</head>

<body>

    <div class="login-page">

        <div class="login-hero">
            <div class="hero-content">
                <h1>Manage your<br><span>gym</span> with ease.</h1>
                <p>Track members, assign trainers, and manage plans - all from one dashboard.</p>
                <ul class="feature-list">
                    <li><i class="fas fa-users"></i> Member &amp; trainer management</li>
                    <li><i class="fas fa-dumbbell"></i> Training plan assignments</li>
                    <li><i class="fas fa-link"></i> Trainer-member assignments</li>
                </ul>
            </div>
        </div>

        <div class="login-form-side">
            <div class="login-box">
                <div class="brand-row">
                    <div class="brand-icon"><i class="fas fa-dumbbell text-white"></i></div>
                    <span class="brand-name">GymAdmin</span>
                </div>

                <h2>Welcome back</h2>
                <p class="sub">Sign in to your admin account to continue</p>

                <?php
                if (isset($_SESSION['error'])) {
                    echo '<div class="err-alert"><i class="fas fa-circle-exclamation"></i> ' . $_SESSION['error'] . '</div>';
                    unset($_SESSION['error']);
                }
                ?>

                <form action="" method="POST">
                    <div class="input-group">
                        <label for="username">Username</label>
                        <div class="input-wrap">
                            <i class="fas fa-user"></i>
                            <input type="text" id="username" name="username" placeholder="Enter your username" required>
                        </div>
                    </div>
                    <div class="input-group">
                        <label for="password">Password</label>
                        <div class="input-wrap">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="password" name="password" placeholder="Enter your password"
                                required>
                        </div>
                    </div>
                    <button type="submit" class="login-submit">
                        <i class="fas fa-arrow-right-to-bracket"></i>&nbsp; Sign In
                    </button>
                </form>

                <p class="footer-txt">&copy; <?php echo date('Y'); ?> GymAdmin - Gym Management System</p>
            </div>
        </div>

    </div>

</body>

</html>