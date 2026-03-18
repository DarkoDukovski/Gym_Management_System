<?php require_once 'config.php'; ?>

<?php


if (!isset($_SESSION['admin_id'])) {
    header('location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];


    if (empty($username) || empty($password) || empty($confirm_password)) {
        $_SESSION['reg_error'] = "All fields are required.";
        header('location: register_admin.php');
        exit();
    }

    if ($password !== $confirm_password) {
        $_SESSION['reg_error'] = "Passwords do not match.";
        header('location: register_admin.php');
        exit();
    }

    if (strlen($password) < 6) {
        $_SESSION['reg_error'] = "Password must be at least 6 characters.";
        header('location: register_admin.php');
        exit();
    }


    $check = $conn->prepare("SELECT admin_id FROM admins WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $_SESSION['reg_error'] = "Username already exists.";
        $check->close();
        header('location: register_admin.php');
        exit();
    }
    $check->close();


    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $created_at = date('Y-m-d');

    $sql = "INSERT INTO admins (username, password, created_at) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $hashed_password, $created_at);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "New admin user registered successfully!";
        $stmt->close();
        $conn->close();
        header('location: admin_dashboard.php');
        exit();
    } else {
        $_SESSION['reg_error'] = "Error: " . $conn->error;
        header('location: register_admin.php');
        exit();
    }
}
?>

<?php require_once 'navbar.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin - GymAdmin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        .page-wrap {
            max-width: 500px;
            margin: 0 auto;
            padding: 3rem 1rem 3rem;
            font-family: 'Inter', system-ui, sans-serif;
        }

        .page-head {
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .page-head-icon {
            width: 46px;
            height: 46px;
            background: linear-gradient(135deg, rgba(245, 158, 11, .15), rgba(245, 158, 11, .05));
            border: 1px solid rgba(245, 158, 11, .2);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: #f59e0b;
            flex-shrink: 0;
        }

        .page-head h1 {
            color: #f9fafb;
            font-size: 1.4rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .page-head p {
            color: #6b7280;
            font-size: .82rem;
            margin-top: 2px;
        }

        .form-card {
            background: #151921;
            border: 1px solid #1e2430;
            border-radius: 18px;
            padding: 2rem;
        }

        .section-label {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #6b7280;
            font-size: .7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .8px;
            margin-bottom: 1rem;
            padding-bottom: 8px;
            border-bottom: 1px solid #1a1f2b;
        }

        .section-label i {
            font-size: .65rem;
            color: #f59e0b;
        }

        .section-label:not(:first-child) {
            margin-top: 1.5rem;
        }

        .field {
            margin-bottom: 1rem;
        }

        .field label {
            display: block;
            color: #9ca3af;
            font-size: .75rem;
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
            font-size: .82rem;
            pointer-events: none;
            transition: color .2s;
        }

        .field input {
            width: 100%;
            padding: 12px 14px 12px 42px;
            background: #0c0f16;
            border: 1px solid #1e2430;
            border-radius: 10px;
            color: #e5e7eb;
            font-size: .87rem;
            font-family: inherit;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }

        .field input::placeholder {
            color: #3b4150;
        }

        .field input:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, .08);
        }

        .field input:focus+i,
        .field .input-wrap:focus-within i {
            color: #f59e0b;
        }

        .submit-row {
            display: flex;
            gap: 10px;
            margin-top: 1.5rem;
        }

        .btn-submit {
            flex: 1;
            padding: 13px;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: .87rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: all .2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(245, 158, 11, .25);
        }

        .btn-back {
            padding: 13px 20px;
            background: #1e2430;
            color: #9ca3af;
            border: 1px solid #2d3343;
            border-radius: 12px;
            font-size: .87rem;
            font-weight: 500;
            font-family: inherit;
            cursor: pointer;
            transition: all .15s;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-back:hover {
            background: #252b38;
        }

        .err-alert {
            background: rgba(239, 68, 68, .08);
            border: 1px solid rgba(239, 68, 68, .2);
            color: #fca5a5;
            padding: 12px 14px;
            border-radius: 12px;
            font-size: .82rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: fadeIn .3s ease;
        }

        .err-alert i {
            flex-shrink: 0;
        }

        .info-note {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 12px 14px;
            background: rgba(59, 130, 246, .06);
            border: 1px solid rgba(59, 130, 246, .12);
            border-radius: 12px;
            margin-top: 1.5rem;
            color: #93c5fd;
            font-size: .78rem;
            line-height: 1.5;
        }

        .info-note i {
            margin-top: 2px;
            flex-shrink: 0;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

    <div class="page-wrap">
        <div class="page-head">
            <div class="page-head-icon"><i class="fas fa-user-shield"></i></div>
            <div>
                <h1>Register New Admin</h1>
                <p>Create a new admin account for the dashboard</p>
            </div>
        </div>

        <div class="form-card">
            <?php
            if (isset($_SESSION['reg_error'])) {
                echo '<div class="err-alert"><i class="fas fa-circle-exclamation"></i> ' . $_SESSION['reg_error'] . '</div>';
                unset($_SESSION['reg_error']);
            }
            ?>

            <form action="register_admin.php" method="POST">

                <div class="section-label"><i class="fas fa-user-gear"></i> Account Credentials</div>

                <div class="field">
                    <label for="adminUsername">Username</label>
                    <div class="input-wrap">
                        <input type="text" id="adminUsername" name="username" placeholder="Enter username" required>
                        <i class="fas fa-user"></i>
                    </div>
                </div>

                <div class="section-label"><i class="fas fa-shield-halved"></i> Security</div>

                <div class="field">
                    <label for="adminPassword">Password</label>
                    <div class="input-wrap">
                        <input type="password" id="adminPassword" name="password" placeholder="Min 6 characters"
                            required>
                        <i class="fas fa-lock"></i>
                    </div>
                </div>
                <div class="field">
                    <label for="adminConfirmPassword">Confirm Password</label>
                    <div class="input-wrap">
                        <input type="password" id="adminConfirmPassword" name="confirm_password"
                            placeholder="Confirm password" required>
                        <i class="fas fa-lock"></i>
                    </div>
                </div>

                <div class="submit-row">
                    <a href="admin_dashboard.php" class="btn-back" style="text-decoration:none">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-user-plus"></i> Register Admin
                    </button>
                </div>
            </form>

            <div class="info-note">
                <i class="fas fa-circle-info"></i>
                <span>The new admin will be able to log in and manage members, trainers, and all dashboard
                    features.</span>
            </div>
        </div>
    </div>

</body>

</html>