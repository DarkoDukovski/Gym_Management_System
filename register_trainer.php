<?php require_once 'config.php'; ?>

<?php

if (!isset($_SESSION['admin_id'])) {
    header('location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $photo_path = isset($_POST['photo_path']) ? $_POST['photo_path'] : '';

    $sql = "INSERT INTO trainers (first_name, last_name, email, phone_number, photo_path) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $first_name, $last_name, $email, $phone_number, $photo_path);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Trainer successfully added";
        $stmt->close();
        $conn->close();
        header('location: admin_dashboard.php');
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<?php require_once 'navbar.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Trainer - GymAdmin</title>
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        .page-wrap {
            max-width: 600px;
            margin: 0 auto;
            padding: 0 1rem 3rem;
            font-family: 'Inter', system-ui, sans-serif;
        }

        /* header */
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

        /* form card */
        .form-card {
            background: #151921;
            border: 1px solid #1e2430;
            border-radius: 18px;
            padding: 2rem;
        }

        /* section labels */
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
            margin-top: 1.25rem;
        }

        /* fields */
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

        .field input {
            width: 100%;
            padding: 12px 14px;
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

        .name-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0 12px;
        }

        /* dropzone */
        .dropzone {
            background: #0c0f16 !important;
            border: 2px dashed #1e2430 !important;
            border-radius: 12px !important;
            min-height: 120px !important;
            padding: 1.25rem !important;
            transition: border-color .2s !important;
        }

        .dropzone:hover {
            border-color: #3b4150 !important;
        }

        .dropzone .dz-message {
            color: #4b5563 !important;
            font-size: .82rem !important;
            margin: 1.5em 0 !important;
        }

        .dropzone .dz-preview .dz-image {
            border-radius: 8px !important;
        }

        /* buttons */
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

        @media (max-width: 480px) {
            .name-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <div class="page-wrap">
        <div class="page-head">
            <div class="page-head-icon"><i class="fas fa-chalkboard-teacher"></i></div>
            <div>
                <h1>Register New Trainer</h1>
                <p>Add a new trainer to your gym staff</p>
            </div>
        </div>

        <div class="form-card">
            <form action="register_trainer.php" method="post" enctype="multipart/form-data">

                <div class="section-label"><i class="fas fa-user"></i> Personal Information</div>

                <div class="name-row">
                    <div class="field">
                        <label for="trainerFirstName">First Name</label>
                        <input type="text" id="trainerFirstName" name="first_name" placeholder="Mike" required>
                    </div>
                    <div class="field">
                        <label for="trainerLastName">Last Name</label>
                        <input type="text" id="trainerLastName" name="last_name" placeholder="Johnson" required>
                    </div>
                </div>

                <div class="section-label"><i class="fas fa-envelope"></i> Contact Details</div>

                <div class="field">
                    <label for="trainerEmail">Email Address</label>
                    <input type="email" id="trainerEmail" name="email" placeholder="mike@gym.com" required>
                </div>
                <div class="field">
                    <label for="trainerPhoneNumber">Phone Number</label>
                    <input type="tel" id="trainerPhoneNumber" name="phone_number" placeholder="+385912345678"
                        pattern="[\+]?[0-9]+" title="Only numbers allowed" required
                        oninput="this.value=this.value.replace(/[^0-9+]/g,'')">
                </div>

                <div class="section-label" style="margin-top:2rem"><i class="fas fa-camera"></i> Trainer Photo</div>

                <div class="field" style="margin-bottom:0">
                    <div id="trainer-dropzone" class="dropzone"></div>
                    <input type="hidden" name="photo_path" id="trainerPhotoInput">
                </div>

                <div class="submit-row">
                    <a href="admin_dashboard.php" class="btn-back" style="text-decoration:none">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-check"></i> Register Trainer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>

    <script>
        Dropzone.options.trainerDropzone = {
            url: "upload_photo.php",
            paramName: "photo",
            maxFilesize: 20,
            acceptedFiles: "image/*",
            init: function () {
                this.on("success", function (file, response) {
                    var jsonResponse = JSON.parse(response);
                    if (jsonResponse.success) {
                        document.getElementById('trainerPhotoInput').value = jsonResponse.photo_path;
                    } else {
                        console.error(jsonResponse.error);
                    }
                });
            }
        };
    </script>

</body>

</html>