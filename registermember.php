<?php
require_once 'config.php';
if (!isset($_SESSION['admin_id'])) {
    header('location: index.php');
    exit();
}
require_once 'navbar.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Member - GymAdmin</title>
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
            overflow: visible;
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
            margin-top: 1.5rem;
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

        .field input,
        .field select {
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

        .field input:focus,
        .field select:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, .08);
        }

        .field select {
            appearance: none;
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%236b7280' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
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

        .name-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0 12px;
        }

        /* plan selector cards */
        .plan-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            margin-bottom: 1rem;
        }

        .plan-card {
            position: relative;
            cursor: pointer;
        }

        .plan-card input[type="radio"] {
            display: none;
        }

        .plan-card .plan-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 16px 8px;
            background: #0c0f16;
            border: 1.5px solid #1e2430;
            border-radius: 12px;
            cursor: pointer;
            transition: all .2s ease;
            text-align: center;
        }

        .plan-card .plan-label:hover {
            border-color: #2d3343;
            background: #111520;
        }

        .plan-card input[type="radio"]:checked+.plan-label {
            border-color: #f59e0b;
            background: rgba(245, 158, 11, .06);
            box-shadow: 0 0 0 3px rgba(245, 158, 11, .08);
        }

        .plan-duration {
            font-size: 1.15rem;
            font-weight: 700;
            color: #e5e7eb;
            line-height: 1.1;
        }

        .plan-card input[type="radio"]:checked+.plan-label .plan-duration {
            color: #f59e0b;
        }

        .plan-unit {
            font-size: .65rem;
            color: #6b7280;
            font-weight: 500;
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        /* date display */
        .valid-until-row .field {
            margin-bottom: 0;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(4px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
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

        /* responsive */
        .details-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }

        .details-grid .field select,
        .details-grid .field input {
            font-size: .82rem;
            padding: 11px 12px;
        }

        .details-grid .field select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' fill='%236b7280' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            padding-right: 28px;
        }

        @media (max-width: 600px) {
            .plan-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .name-row {
                grid-template-columns: 1fr;
            }

            .details-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <div class="page-wrap">
        <div class="page-head">
            <div class="page-head-icon"><i class="fas fa-user-plus"></i></div>
            <div>
                <h1>Register New Member</h1>
                <p>Fill in the details below to add a new gym member</p>
            </div>
        </div>

        <div class="form-card">
            <form action="register_member.php" method="post" enctype="multipart/form-data">

                <!-- Personal Info -->
                <div class="section-label"><i class="fas fa-user"></i> Personal Information</div>

                <div class="name-row">
                    <div class="field">
                        <label for="memberFirstName">First Name</label>
                        <input type="text" id="memberFirstName" name="first_name" placeholder="John" required>
                    </div>
                    <div class="field">
                        <label for="memberLastName">Last Name</label>
                        <input type="text" id="memberLastName" name="last_name" placeholder="Doe" required>
                    </div>
                </div>
                <div class="field">
                    <label for="memberEmail">Email Address</label>
                    <input type="email" id="memberEmail" name="email" placeholder="john.doe@email.com" required>
                </div>
                <div class="field">
                    <label for="memberPhoneNumber">Phone Number</label>
                    <input type="tel" id="memberPhoneNumber" name="phone_number" placeholder="+385912345678"
                        pattern="[\+]?[0-9]+" title="Only numbers allowed" required
                        oninput="this.value=this.value.replace(/[^0-9+]/g,'')">
                </div>

                <!-- Membership -->
                <div class="section-label"><i class="fas fa-crown"></i> Membership Plan</div>

                <div class="plan-grid">
                    <div class="plan-card">
                        <input type="radio" name="plan_select" id="plan1" value="1" onchange="calcValidUntil()">
                        <label for="plan1" class="plan-label">
                            <span class="plan-duration">1</span>
                            <span class="plan-unit">Month</span>
                        </label>
                    </div>
                    <div class="plan-card">
                        <input type="radio" name="plan_select" id="plan3" value="3" onchange="calcValidUntil()">
                        <label for="plan3" class="plan-label">
                            <span class="plan-duration">3</span>
                            <span class="plan-unit">Months</span>
                        </label>
                    </div>
                    <div class="plan-card">
                        <input type="radio" name="plan_select" id="plan6" value="6" onchange="calcValidUntil()">
                        <label for="plan6" class="plan-label">
                            <span class="plan-duration">6</span>
                            <span class="plan-unit">Months</span>
                        </label>
                    </div>
                    <div class="plan-card">
                        <input type="radio" name="plan_select" id="plan12" value="12" onchange="calcValidUntil()">
                        <label for="plan12" class="plan-label">
                            <span class="plan-duration">1</span>
                            <span class="plan-unit">Year</span>
                        </label>
                    </div>
                </div>

                <div class="field">
                    <label for="validUntil">Valid Until</label>
                    <div class="date-input-wrap">
                        <input type="text" id="validUntilDisplay" placeholder="Pick a date" readonly>
                        <input type="hidden" id="validUntil" name="valid_until" required>
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


                <!-- Photo -->
                <div class="section-label"><i class="fas fa-camera"></i> Member Photo</div>

                <div class="field" style="margin-bottom:0">
                    <div id="dropzone-upload" class="dropzone"></div>
                    <input type="hidden" name="photo_path" id="photoPathInput">
                </div>

                <!-- Submit -->
                <div class="submit-row">
                    <a href="admin_dashboard.php" class="btn-back" style="text-decoration:none">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-check"></i> Register Member
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>

    <script>
        Dropzone.options.dropzoneUpload = {
            url: "upload_photo.php",
            paramName: "photo",
            maxFilesize: 20,
            acceptedFiles: "image/*",
            init: function () {
                this.on("success", function (file, response) {
                    const jsonResponse = JSON.parse(response);
                    if (jsonResponse.success) {
                        document.getElementById('photoPathInput').value = jsonResponse.photo_path;
                    } else {
                        console.error(jsonResponse.error);
                    }
                });
            }
        };


        const calPopup = document.getElementById('calPopup');
        const calDays = document.getElementById('calDays');
        const calMonthYear = document.getElementById('calMonthYear');
        const calToggle = document.getElementById('calToggle');
        const calDisplay = document.getElementById('validUntilDisplay');
        const calHidden = document.getElementById('validUntil');
        const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        let calYear, calMonth, selectedDate = null;

        function initCal() {
            const now = new Date();
            calYear = now.getFullYear();
            calMonth = now.getMonth();
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
                const m = calMonth - 1;
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


        function calcValidUntil() {
            const checked = document.querySelector('input[name="plan_select"]:checked');
            if (!checked) return;
            const m = parseInt(checked.value);
            const d = new Date();
            d.setMonth(d.getMonth() + m);
            calYear = d.getFullYear();
            calMonth = d.getMonth();
            selectDay(d.getDate());
        }
    </script>

</body>

</html>