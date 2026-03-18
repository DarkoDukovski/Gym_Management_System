<?php
require_once 'config.php';
require_once 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header('location: index.php');
    exit();
}


if (isset($_GET['member_id'])) {
    $member_id = $_GET['member_id'];
    $sql = "SELECT * FROM members WHERE member_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $member_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $member = $result->fetch_assoc();

    if (!$member) {
        die("Member not found");
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $member_id = $_POST['member_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $valid_until = $_POST['valid_until'];

    $photo_path = $_POST['photo_path'];

    // Fetch old photo to delete if changed
    $sql_old = "SELECT photo_path FROM members WHERE member_id = ?";
    $stmt_old = $conn->prepare($sql_old);
    $stmt_old->bind_param("i", $member_id);
    $stmt_old->execute();
    $result_old = $stmt_old->get_result();
    if ($old_data = $result_old->fetch_assoc()) {
        $old_photo = $old_data['photo_path'];
        if (!empty($old_photo) && $old_photo !== $photo_path) {
            if (file_exists($old_photo)) {
                unlink($old_photo);
            }
        }
    }

    $sql = "UPDATE members SET 
            first_name = ?, 
            last_name = ?, 
            email = ?, 
            phone_number = ?, 
            valid_until = ?, 
            photo_path = ? 
            WHERE member_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $first_name, $last_name, $email, $phone_number, $valid_until, $photo_path, $member_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Member details updated successfully";
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
    <title>Edit Member - GymAdmin</title>
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css">
    <style>
        .page-wrap {
            max-width: 560px;
            margin: 0 auto;
            padding: 3rem 1rem 3rem;
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

        /* custom calendar */
        .date-input-wrap {
            position: relative;
        }

        .date-input-wrap input {
            padding-right: 44px;
            cursor: pointer;
        }

        .cal-toggle {
            position: absolute;
            right: 1px;
            top: 1px;
            bottom: 1px;
            width: 40px;
            background: transparent;
            border: none;
            color: #6b7280;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0 9px 9px 0;
            transition: color .15s;
        }

        .cal-toggle:hover {
            color: #f59e0b;
        }

        .cal-popup {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            z-index: 999;
            background: #151921;
            border: 1px solid #1e2430;
            border-radius: 16px;
            padding: 16px;
            box-shadow: 0 16px 48px rgba(0, 0, 0, .6);
            display: none;
            min-width: 300px;
            animation: calFadeIn .2s ease;
            font-family: 'Inter', sans-serif;
        }

        .cal-popup.open {
            display: block;
        }

        @keyframes calFadeIn {
            from {
                opacity: 0;
                transform: translateY(-6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .cal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .cal-header span {
            font-size: .9rem;
            font-weight: 600;
            color: #f9fafb;
        }

        .cal-nav {
            display: flex;
            gap: 4px;
        }

        .cal-nav button {
            width: 32px;
            height: 32px;
            border: none;
            background: #0c0f16;
            color: #9ca3af;
            border-radius: 8px;
            cursor: pointer;
            font-size: .75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all .15s;
        }

        .cal-nav button:hover {
            background: rgba(245, 158, 11, .1);
            color: #f59e0b;
        }

        .cal-weekdays {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            margin-bottom: 6px;
        }

        .cal-weekdays span {
            text-align: center;
            font-size: .65rem;
            font-weight: 600;
            color: #4b5563;
            text-transform: uppercase;
            letter-spacing: .5px;
            padding: 4px 0;
        }

        .cal-days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 2px;
        }

        .cal-day {
            width: 100%;
            aspect-ratio: 1;
            border: none;
            background: transparent;
            color: #d1d5db;
            border-radius: 10px;
            cursor: pointer;
            font-size: .82rem;
            font-weight: 500;
            font-family: inherit;
            transition: all .12s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cal-day:hover:not(.empty):not(.selected) {
            background: rgba(245, 158, 11, .08);
            color: #f59e0b;
        }

        .cal-day.empty {
            cursor: default;
        }

        .cal-day.other-month {
            color: #2d3343;
        }

        .cal-day.other-month:hover {
            color: #4b5563;
            background: rgba(255, 255, 255, .02);
        }

        .cal-day.today:not(.selected) {
            border: 1.5px solid rgba(245, 158, 11, .35);
            color: #f59e0b;
        }

        .cal-day.selected {
            background: #f59e0b;
            color: #0c0f16;
            font-weight: 700;
        }

        .cal-today-btn {
            display: block;
            width: 100%;
            margin-top: 10px;
            padding: 7px;
            background: transparent;
            border: 1px solid #1e2430;
            border-radius: 8px;
            color: #6b7280;
            font-size: .72rem;
            font-weight: 500;
            font-family: inherit;
            cursor: pointer;
            transition: all .15s;
        }

        .cal-today-btn:hover {
            border-color: #f59e0b;
            color: #f59e0b;
        }
    </style>
</head>

<body>
    <div class="page-wrap">
        <div class="page-head">
            <div class="page-head-icon"><i class="fas fa-user-pen"></i></div>
            <div>
                <h1>Edit Member</h1>
                <p>Update member details and membership validity</p>
            </div>
        </div>

        <div class="form-card">
            <form action="edit_member.php" method="post" enctype="multipart/form-data"
                  onkeydown="if(event.key === 'Enter' && event.target.tagName !== 'BUTTON') { event.preventDefault(); return false; }"
                  onsubmit="if(!document.getElementById('validUntil').value) { alert('Мора да одберете Valid Until датум! / Please pick a valid date.'); return false; }">
                <input type="hidden" name="member_id" value="<?php echo $member['member_id']; ?>">

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:0 14px">
                    <div class="field">
                        <label for="memberFirstName">First Name</label>
                        <input type="text" id="memberFirstName" name="first_name"
                            value="<?php echo htmlspecialchars($member['first_name']); ?>" required>
                    </div>
                    <div class="field">
                        <label for="memberLastName">Last Name</label>
                        <input type="text" id="memberLastName" name="last_name"
                            value="<?php echo htmlspecialchars($member['last_name']); ?>" required>
                    </div>
                </div>

                <div class="field">
                    <label for="memberEmail">Email Address</label>
                    <input type="email" id="memberEmail" name="email"
                        value="<?php echo htmlspecialchars($member['email']); ?>" required>
                </div>

                <div class="field">
                    <label for="memberPhoneNumber">Phone Number</label>
                    <input type="tel" id="memberPhoneNumber" name="phone_number"
                        value="<?php echo htmlspecialchars($member['phone_number']); ?>" pattern="[\+]?[0-9]+"
                        title="Only numbers allowed" required oninput="this.value=this.value.replace(/[^0-9+]/g,'')">
                </div>

                <div class="field">
                    <label for="validUntil">Membership Valid Until</label>
                    <div class="date-input-wrap">
                        <input type="text" id="validUntilDisplay" placeholder="Pick a date" readonly>
                        <input type="hidden" id="validUntil" name="valid_until"
                            value="<?php echo $member['valid_until']; ?>" required>
                        <button type="button" class="cal-toggle" id="calToggle"><i
                                class="fas fa-calendar-alt"></i></button>
                        <div class="cal-popup" id="calPopup">
                            <div class="cal-header">
                                <span id="calMonthYear"></span>
                                <div class="cal-nav">
                                    <button type="button" id="calPrev"><i class="fas fa-chevron-left"></i></button>
                                    <button type="button" id="calNext"><i class="fas fa-chevron-right"></i></button>
                                </div>
                            </div>
                            <div class="cal-weekdays">
                                <span>Sun</span><span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span>
                            </div>
                            <div class="cal-days" id="calDays"></div>
                            <button type="button" class="cal-today-btn" id="calToday">Today</button>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <label>Member Photo</label>
                    <!-- Show current photo -->
                    <?php if (!empty($member['photo_path'])): ?>
                        <div class="current-photo">
                            <img src="<?php echo $member['photo_path']; ?>" alt="Current Photo">
                            <span>Current photo</span>
                        </div>
                    <?php endif; ?>

                    <div id="dropzone-upload" class="dropzone"></div>
                    <!-- Pre-fill hidden input with existing path -->
                    <input type="hidden" name="photo_path" id="photoPathInput"
                        value="<?php echo htmlspecialchars($member['photo_path']); ?>">
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
            maxFilesize: 15,
            acceptedFiles: "image/*",
            maxFiles: 1,
            dictFileTooBig: "File is too big ({{filesize}}MB). Max filesize: {{maxFilesize}}MB.",
            dictDefaultMessage: "Drop new photo here to update (optional)",
            init: function () {
                this.on("success", function (file, response) {
                    try {
                        var jsonResponse = (typeof response === "string") ? JSON.parse(response) : response;
                        if (jsonResponse.success) {
                            document.getElementById('photoPathInput').value = jsonResponse.photo_path;
                        }
                    } catch (e) {}
                });
                this.on("error", function (file, message) {
                    document.getElementById('photoPathInput').value = '';
                    alert("Photo upload failed: " + message);
                });
                this.on("addedfile", function () {
                    if (this.files[1] != null) {
                        this.removeFile(this.files[0]);
                    }
                });
            }
        });

        // CUSTOM CALENDAR
        const calPopup = document.getElementById('calPopup');
        const calDays = document.getElementById('calDays');
        const calMonthYear = document.getElementById('calMonthYear');
        const calToggle = document.getElementById('calToggle');
        const calDisplay = document.getElementById('validUntilDisplay');
        const calHidden = document.getElementById('validUntil');
        const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        let calYear, calMonth, selectedDate = null;

        function initCal() {
            // Pre-select the existing valid_until date
            const existing = calHidden.value;
            if (existing) {
                const parts = existing.split('-');
                selectedDate = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]));
                calYear = selectedDate.getFullYear();
                calMonth = selectedDate.getMonth();
                const opts = { year: 'numeric', month: 'long', day: 'numeric' };
                calDisplay.value = selectedDate.toLocaleDateString('en-US', opts);
            } else {
                const now = new Date();
                calYear = now.getFullYear();
                calMonth = now.getMonth();
            }
        }
        initCal();

        function renderCal() {
            calMonthYear.textContent = months[calMonth] + ' ' + calYear;
            calDays.innerHTML = '';
            const firstDay = new Date(calYear, calMonth, 1).getDay();
            const daysInMonth = new Date(calYear, calMonth + 1, 0).getDate();
            const daysInPrev = new Date(calYear, calMonth, 0).getDate();
            const today = new Date();

            for (let i = firstDay - 1; i >= 0; i--) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'cal-day other-month';
                btn.textContent = daysInPrev - i;
                const d = daysInPrev - i;
                btn.onclick = () => { calMonth--; if (calMonth < 0) { calMonth = 11; calYear--; } selectDay(d); };
                calDays.appendChild(btn);
            }

            for (let d = 1; d <= daysInMonth; d++) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'cal-day';
                btn.textContent = d;
                if (d === today.getDate() && calMonth === today.getMonth() && calYear === today.getFullYear()) {
                    btn.classList.add('today');
                }
                if (selectedDate && d === selectedDate.getDate() && calMonth === selectedDate.getMonth() && calYear === selectedDate.getFullYear()) {
                    btn.classList.add('selected');
                }
                btn.onclick = () => selectDay(d);
                calDays.appendChild(btn);
            }

            const total = firstDay + daysInMonth;
            const remaining = (7 - (total % 7)) % 7;
            for (let d = 1; d <= remaining; d++) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'cal-day other-month';
                btn.textContent = d;
                btn.onclick = () => { calMonth++; if (calMonth > 11) { calMonth = 0; calYear++; } selectDay(d); };
                calDays.appendChild(btn);
            }
        }

        function selectDay(day) {
            selectedDate = new Date(calYear, calMonth, day);
            const yyyy = selectedDate.getFullYear();
            const mm = String(selectedDate.getMonth() + 1).padStart(2, '0');
            const dd = String(selectedDate.getDate()).padStart(2, '0');
            calHidden.value = yyyy + '-' + mm + '-' + dd;
            const opts = { year: 'numeric', month: 'long', day: 'numeric' };
            calDisplay.value = selectedDate.toLocaleDateString('en-US', opts);
            renderCal();
            calPopup.classList.remove('open');
        }

        calToggle.addEventListener('click', () => {
            calPopup.classList.toggle('open');
            if (calPopup.classList.contains('open')) renderCal();
        });
        calDisplay.addEventListener('click', () => {
            calPopup.classList.toggle('open');
            if (calPopup.classList.contains('open')) renderCal();
        });

        document.getElementById('calPrev').addEventListener('click', () => {
            calMonth--;
            if (calMonth < 0) { calMonth = 11; calYear--; }
            renderCal();
        });
        document.getElementById('calNext').addEventListener('click', () => {
            calMonth++;
            if (calMonth > 11) { calMonth = 0; calYear++; }
            renderCal();
        });

        document.getElementById('calToday').addEventListener('click', () => {
            const now = new Date();
            calYear = now.getFullYear();
            calMonth = now.getMonth();
            selectDay(now.getDate());
        });

        document.addEventListener('click', (e) => {
            if (!e.target.closest('.date-input-wrap')) {
                calPopup.classList.remove('open');
            }
        });

        document.querySelector('form').addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && e.target.tagName === 'INPUT') {
                e.preventDefault();
            }
        });

        document.querySelector('form').addEventListener('submit', function(e) {
            const validUntil = document.getElementById('validUntil');
            if (validUntil && !validUntil.value) {
                e.preventDefault();
                alert('Please pick a Valid Until date.');
            }
        });
    </script>
</body>

</html>