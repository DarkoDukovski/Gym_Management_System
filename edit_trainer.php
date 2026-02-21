<?php
require_once 'config.php';
require_once 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header('location: index.php');
    exit();
}


if (isset($_GET['trainer_id'])) {
    $trainer_id = $_GET['trainer_id'];
    $sql = "SELECT * FROM trainers WHERE trainer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $trainer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $trainer = $result->fetch_assoc();

    if (!$trainer) {
        die("Trainer not found");
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $trainer_id = $_POST['trainer_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];


    $photo_path = $_POST['photo_path'];

    $sql = "UPDATE trainers SET 
            first_name = ?, 
            last_name = ?, 
            email = ?, 
            phone_number = ?, 
            photo_path = ? 
            WHERE trainer_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $first_name, $last_name, $email, $phone_number, $photo_path, $trainer_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Trainer details updated successfully";
        header('location: admin_dashboard.php');
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
    header('location: admin_dashboard.php');
    exit();
}
?>

<?php require_once 'navbar.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Trainer - GymAdmin</title>
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css">
    <style>
        .page-wrap {
            max-width: 560px;
            margin: 0 auto;
            padding: 0 1rem 3rem;
        }

        .page-head {
            margin-bottom: 1.5rem;
        }

        .page-head h1 {
            color: #f9fafb;
            font-size: 1.35rem;
            font-weight: 700;
        }

        .page-head p {
            color: #6b7280;
            font-size: .85rem;
            margin-top: 2px;
        }

        .form-card {
            background: #151921;
            border: 1px solid #1e2430;
            border-radius: 16px;
            padding: 2rem;
        }

        .field {
            margin-bottom: 1.15rem;
        }

        .field label {
            display: block;
            color: #9ca3af;
            font-size: .78rem;
            font-weight: 500;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: .3px;
        }

        .field input,
        .field select {
            width: 100%;
            padding: 11px 14px;
            background: #0c0f16;
            border: 1px solid #1e2430;
            border-radius: 10px;
            color: #e5e7eb;
            font-size: .87rem;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }

        .field input::placeholder {
            color: #4b5563;
        }

        .field input:focus,
        .field select:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, .08);
        }

        .current-photo {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
            padding: 10px;
            background: #0c0f16;
            border-radius: 10px;
            border: 1px solid #1e2430;
        }

        .current-photo img {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
        }

        .current-photo span {
            font-size: .85rem;
            color: #9ca3af;
        }

        /* dropzone */
        .dropzone {
            background: #0c0f16 !important;
            border: 2px dashed #1e2430 !important;
            border-radius: 12px !important;
            min-height: 100px !important;
            padding: 1rem !important;
        }

        .dropzone:hover {
            border-color: #3b4150 !important;
        }

        .dropzone .dz-message {
            color: #6b7280 !important;
            font-size: .85rem !important;
            margin: 1em 0 !important;
        }

        .submit-row {
            display: flex;
            gap: 10px;
            margin-top: 1.25rem;
        }

        .btn-submit {
            flex: 1;
            padding: 12px;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: .87rem;
            font-weight: 600;
            cursor: pointer;
            transition: opacity .15s;
        }

        .btn-submit:hover {
            opacity: .9;
        }

        .btn-back {
            padding: 12px 20px;
            background: #1e2430;
            color: #9ca3af;
            border: 1px solid #2d3343;
            border-radius: 10px;
            font-size: .87rem;
            font-weight: 500;
            cursor: pointer;
            transition: background .15s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .btn-back:hover {
            background: #252b38;
            color: #d1d5db;
        }
    </style>
</head>

<body>
    <div class="page-wrap">
        <div class="page-head">
            <h1><i class="fas fa-user-pen" style="color:#f59e0b; font-size:1.1rem"></i>&nbsp; Edit Trainer</h1>
            <p>Update trainer details</p>
        </div>

        <div class="form-card">
            <form action="edit_trainer.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="trainer_id" value="<?php echo $trainer['trainer_id']; ?>">

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:0 14px">
                    <div class="field">
                        <label for="trainerFirstName">First Name</label>
                        <input type="text" id="trainerFirstName" name="first_name"
                            value="<?php echo htmlspecialchars($trainer['first_name']); ?>" required>
                    </div>
                    <div class="field">
                        <label for="trainerLastName">Last Name</label>
                        <input type="text" id="trainerLastName" name="last_name"
                            value="<?php echo htmlspecialchars($trainer['last_name']); ?>" required>
                    </div>
                </div>

                <div class="field">
                    <label for="trainerEmail">Email Address</label>
                    <input type="email" id="trainerEmail" name="email"
                        value="<?php echo htmlspecialchars($trainer['email']); ?>" required>
                </div>

                <div class="field">
                    <label for="trainerPhoneNumber">Phone Number</label>
                    <input type="tel" id="trainerPhoneNumber" name="phone_number"
                        value="<?php echo htmlspecialchars($trainer['phone_number']); ?>" pattern="[\+]?[0-9]+"
                        title="Only numbers allowed" required oninput="this.value=this.value.replace(/[^0-9+]/g,'')">
                </div>

                <div class="field">
                    <label>Trainer Photo</label>
                    <!-- Show current photo -->
                    <?php if (!empty($trainer['photo_path'])): ?>
                        <div class="current-photo">
                            <img src="<?php echo $trainer['photo_path']; ?>" alt="Current Photo">
                            <span>Current photo</span>
                        </div>
                    <?php endif; ?>

                    <div id="dropzone-upload" class="dropzone"></div>
                    <!-- Pre-fill hidden input with existing path -->
                    <input type="hidden" name="photo_path" id="photoPathInput"
                        value="<?php echo htmlspecialchars($trainer['photo_path']); ?>">
                </div>

                <div class="submit-row">
                    <a href="admin_dashboard.php" class="btn-back"><i class="fas fa-arrow-left"></i>&nbsp; Back</a>
                    <button type="submit" class="btn-submit"><i class="fas fa-floppy-disk"></i>&nbsp; Save
                        Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <script>
        Dropzone.autoDiscover = false;
        var myDropzone = new Dropzone("#dropzone-upload", {
            url: "upload_photo.php",
            paramName: "photo",
            maxFilesize: 5, // MB
            acceptedFiles: "image/*",
            maxFiles: 1,
            dictDefaultMessage: "Drop new photo here to update (optional)",
            init: function () {
                this.on("success", function (file, response) {
                    try {
                        var jsonResponse = JSON.parse(response);
                        if (jsonResponse.success) {
                            document.getElementById('photoPathInput').value = jsonResponse.photo_path;
                        } else {
                            console.error(jsonResponse.error);
                        }
                    } catch (e) {
                        console.error("Invalid JSON response", response);
                    }
                });
                this.on("addedfile", function () {
                    if (this.files[1] != null) {
                        this.removeFile(this.files[0]);
                    }
                });
            }
        });
    </script>
</body>

</html>