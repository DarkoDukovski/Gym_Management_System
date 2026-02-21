<?php
require_once 'config.php';

// admin check — must be BEFORE navbar (which outputs HTML)
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
    <title>Dashboard - GymAdmin</title>
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
    <style>
        .dash {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1rem 3rem
        }

        /* page heading */
        .page-head {
            margin-bottom: 1.75rem;
            background: #151921;
            border: 1px solid #1e2430;
            border-radius: 16px;
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .page-head-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, rgba(245, 158, 11, .15), rgba(245, 158, 11, .05));
            border: 1px solid rgba(245, 158, 11, .2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .page-head-icon i {
            color: #f59e0b;
            font-size: 1rem;
        }

        .page-head h1 {
            color: #f9fafb;
            font-size: 1.3rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .page-head p {
            color: #6b7280;
            font-size: .82rem;
            margin-top: 2px;
        }

        /* stat cards row */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: #151921;
            border: 1px solid #1e2430;
            border-radius: 14px;
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .stat-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .stat-icon.amber {
            background: rgba(245, 158, 11, .1);
            color: #fbbf24
        }

        .stat-icon.blue {
            background: rgba(59, 130, 246, .1);
            color: #60a5fa
        }

        .stat-icon.green {
            background: rgba(16, 185, 129, .1);
            color: #34d399
        }

        .stat-icon.purple {
            background: rgba(168, 85, 247, .1);
            color: #c084fc
        }

        .stat-info .stat-num {
            font-size: 1.5rem;
            font-weight: 700;
            color: #f9fafb;
            line-height: 1
        }

        .stat-info .stat-label {
            font-size: .78rem;
            color: #6b7280;
            margin-top: 4px
        }

        /* section panel */
        .panel {
            background: #151921;
            border: 1px solid #1e2430;
            border-radius: 16px;
            margin-bottom: 1.75rem;
            overflow: hidden;
        }

        .panel-top {
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: .75rem;
            border-bottom: 1px solid #1e2430;
        }

        .panel-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #f3f4f6;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .panel-title-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, rgba(245, 158, 11, .15), rgba(245, 158, 11, .05));
            border: 1px solid rgba(245, 158, 11, .2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .panel-title-icon i {
            color: #f59e0b;
            font-size: .85rem;
        }

        .panel-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap
        }

        /* search box */
        .tbl-search {
            padding: 7px 12px 7px 34px;
            background: #0c0f16;
            border: 1px solid #1e2430;
            border-radius: 8px;
            color: #d1d5db;
            font-size: .8rem;
            outline: none;
            width: 200px;
            transition: border-color .2s;
        }

        .tbl-search:focus {
            border-color: #f59e0b
        }

        .tbl-search::placeholder {
            color: #4b5563
        }

        .search-wrap {
            position: relative
        }

        .search-wrap i {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            color: #4b5563;
            font-size: .75rem;
        }


        /* table */
        .tbl-wrap {
            overflow-x: auto
        }

        .tbl {
            width: 100%;
            border-collapse: collapse;
            font-size: .84rem
        }

        .tbl th {
            background: #0f1219;
            color: #6b7280;
            font-size: .7rem;
            font-weight: 600;
            letter-spacing: .05em;
            text-transform: uppercase;
            padding: .75rem 1rem;
            text-align: left;
            white-space: nowrap;
        }

        .tbl td {
            padding: .55rem 1rem;
            color: #c9cdd5;
            border-top: 1px solid #1a1f2b;
            vertical-align: middle;
        }

        .tbl tbody tr {
            transition: background .1s
        }

        .tbl tbody tr:hover {
            background: rgba(255, 255, 255, .02)
        }

        .cell-name {
            color: #f3f4f6;
            font-weight: 600
        }

        /* member avatar */
        .avatar {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            object-fit: cover;
            border: 2px solid #1e2430;
        }

        /* badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: .72rem;
            font-weight: 500;
        }

        .badge i {
            font-size: .55rem
        }

        .b-green {
            background: rgba(16, 185, 129, .1);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, .15)
        }

        .b-gray {
            background: rgba(107, 114, 128, .1);
            color: #9ca3af;
            border: 1px solid rgba(107, 114, 128, .15)
        }

        .b-amber {
            background: rgba(245, 158, 11, .1);
            color: #fbbf24;
            border: 1px solid rgba(245, 158, 11, .15)
        }

        .b-red {
            background: rgba(239, 68, 68, .1);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, .15)
        }

        /* action icon buttons */
        .actions {
            display: flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap
        }

        .act-btn {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            border: 1px solid transparent;
            background: transparent;
            color: #4b5563;
            font-size: .82rem;
            cursor: pointer;
            transition: all .15s;
        }

        .act-btn:hover {
            background: rgba(255, 255, 255, .04)
        }

        .act-btn.act-view:hover {
            color: #60a5fa;
            border-color: rgba(96, 165, 250, .2);
            background: rgba(96, 165, 250, .06)
        }

        .act-btn.act-edit:hover {
            color: #fbbf24;
            border-color: rgba(251, 191, 36, .2);
            background: rgba(251, 191, 36, .06)
        }

        .act-btn.act-del:hover {
            color: #f87171;
            border-color: rgba(248, 113, 113, .2);
            background: rgba(248, 113, 113, .06)
        }

        .act-btn[title] {
            position: relative
        }

        .btn-primary {
            padding: 10px 28px;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: .87rem;
            font-weight: 600;
            cursor: pointer;
            transition: opacity .15s;
        }

        .btn-primary:hover {
            opacity: .9
        }

        /* bottom cards grid */
        .bottom-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 2rem;
        }

        .bottom-grid .panel {
            box-shadow: 0 4px 24px rgba(0, 0, 0, .25);
            display: flex;
            flex-direction: column;
        }

        .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #1e2430;
            text-align: center;
        }

        .card-header-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: .5rem;
        }

        .card-header-title {
            color: #f3f4f6;
            font-weight: 700;
            font-size: .95rem;
        }

        .card-header-sub {
            color: #6b7280;
            font-size: .78rem;
            margin-top: 2px;
        }

        .card-body {
            padding: 2rem 1.5rem 1.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        /* membership overview card */
        .membership-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .membership-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 14px;
            background: #0c0f16;
            border: 1px solid #1e2430;
            border-radius: 10px;
        }

        .membership-item .mi-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: .82rem;
            color: #9ca3af;
        }

        .membership-item .mi-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .membership-item .mi-count {
            font-size: .95rem;
            font-weight: 700;
            color: #f3f4f6;
        }

        /* distribution summary */
        .mo-summary {
            margin-top: auto;
            padding-top: 20px;
            border-top: 1px solid #1e2430;
        }

        .mo-bar-label {
            font-size: .62rem;
            font-weight: 700;
            color: #3b4150;
            text-transform: uppercase;
            letter-spacing: .8px;
            margin-bottom: 8px;
        }

        .mo-bar {
            display: flex;
            height: 8px;
            border-radius: 6px;
            overflow: hidden;
            background: #1e2430;
            gap: 2px;
        }

        .mo-bar-seg {
            border-radius: 4px;
            transition: width .6s ease;
            min-width: 2px;
        }

        .mo-insights {
            display: flex;
            justify-content: space-between;
            margin-top: 14px;
        }

        .mo-insight {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
        }

        .mo-insight-val {
            font-size: .95rem;
            font-weight: 700;
        }

        .mo-insight-label {
            font-size: .62rem;
            color: #4b5563;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        /* quick actions card */
        .quick-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .qa-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            background: #0c0f16;
            border: 1px solid #1e2430;
            border-radius: 10px;
            color: #9ca3af;
            text-decoration: none;
            font-size: .82rem;
            font-weight: 500;
            transition: all .2s;
        }

        .qa-link:hover {
            border-color: rgba(245, 158, 11, .3);
            color: #f59e0b;
            background: rgba(245, 158, 11, .04);
        }

        .qa-link i {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .75rem;
            flex-shrink: 0;
        }

        /* revenue tracker card */
        .revenue-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .rev-plan {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .rev-plan-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .rev-plan-name {
            font-size: .78rem;
            color: #9ca3af;
            font-weight: 500;
        }

        .rev-plan-amount {
            font-size: .78rem;
            color: #f3f4f6;
            font-weight: 700;
        }

        .rev-plan-detail {
            font-size: .62rem;
            color: #4b5563;
            margin-top: -2px;
        }

        .rev-bar-track {
            height: 8px;
            background: #1a1f2b;
            border-radius: 4px;
            overflow: hidden;
        }

        .rev-bar-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 1s ease;
        }

        .rev-total {
            margin-top: 6px;
            padding: 14px;
            background: #0c0f16;
            border: 1px solid #1e2430;
            border-radius: 12px;
            text-align: center;
        }

        .rev-total-amount {
            font-size: 1.5rem;
            font-weight: 800;
            color: #34d399;
            line-height: 1.2;
        }

        .rev-total-label {
            font-size: .62rem;
            color: #4b5563;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .8px;
            margin-top: 2px;
        }


        @media (max-width: 768px) {
            .bottom-grid {
                grid-template-columns: 1fr;
            }
        }

        .form-label {
            display: block;
            color: #9ca3af;
            font-size: .78rem;
            font-weight: 500;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: .3px;
        }

        .form-select {
            width: 100%;
            padding: 11px 14px;
            background: #0c0f16;
            border: 1px solid #1e2430;
            border-radius: 10px;
            color: #e5e7eb;
            font-size: .87rem;
            outline: none;
            appearance: none;
            margin-bottom: 1.1rem;
            transition: border-color .2s;
            cursor: pointer;
        }

        .form-select:focus {
            border-color: #f59e0b
        }

        /* toast */
        .toast-notif {
            padding: 14px 18px;
            background: #151921;
            border: 1px solid rgba(16, 185, 129, .25);
            border-radius: 12px;
            color: #34d399;
            font-size: .85rem;
            font-weight: 500;
            box-shadow: 0 8px 30px rgba(0, 0, 0, .4);
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideIn .3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateX(120%);
                opacity: 0
            }

            to {
                transform: translateX(0);
                opacity: 1
            }
        }

        .toast-x {
            margin-left: auto;
            background: none;
            border: none;
            color: #4b5563;
            cursor: pointer;
            font-size: 1.1rem;
            padding: 0 4px;
        }

        .toast-x:hover {
            color: #9ca3af
        }

        /* delete confirm modal */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .6);
            backdrop-filter: blur(4px);
            z-index: 300;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .modal-overlay.active {
            display: flex
        }

        .modal-box {
            background: #151921;
            border: 1px solid #1e2430;
            border-radius: 16px;
            padding: 2rem;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        .modal-box .modal-icon {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: rgba(239, 68, 68, .1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: #f87171;
            font-size: 1.3rem;
        }

        .modal-box h3 {
            color: #f3f4f6;
            font-size: 1.15rem;
            font-weight: 700;
            margin-bottom: .5rem
        }

        .modal-box p {
            color: #6b7280;
            font-size: .85rem;
            margin-bottom: 1.5rem
        }

        .modal-btns {
            display: flex;
            gap: 10px;
            justify-content: center
        }

        .modal-cancel {
            padding: 9px 22px;
            background: #1e2430;
            color: #9ca3af;
            border: 1px solid #2d3343;
            border-radius: 10px;
            font-size: .85rem;
            font-weight: 500;
            cursor: pointer;
            transition: background .15s;
        }

        .modal-cancel:hover {
            background: #252b38
        }

        .modal-confirm {
            padding: 9px 22px;
            background: #dc2626;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: .85rem;
            font-weight: 600;
            cursor: pointer;
            transition: opacity .15s;
        }

        .modal-confirm:hover {
            opacity: .9
        }

        /* date column */
        .date-cell {
            white-space: nowrap;
            color: #6b7280;
            font-size: .8rem
        }
    </style>
</head>

<body>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div style="position:fixed; top:90px; left:0; width:100%; z-index:200; pointer-events:none">
            <div style="max-width:1280px; margin:0 auto; padding:0 1rem; display:flex; justify-content:flex-end">
                <div id="successMessage" class="toast-notif" style="pointer-events:auto">
                    <i class="fas fa-check-circle"></i>
                    <?php
                    echo $_SESSION['success_message'];
                    unset($_SESSION['success_message']);
                    ?>
                    <button class="toast-x"
                        onclick="document.getElementById('successMessage').style.display='none'">&times;</button>
                </div>
            </div>
        </div>
        <script>
            setTimeout(function () {
                var msg = document.getElementById('successMessage');
                if (msg) {
                    msg.style.transition = 'opacity 1s';
                    msg.style.opacity = '0';
                    setTimeout(function () {
                        msg.style.display = 'none';
                    }, 1000);
                }
            }, 5000);
        </script>
    <?php endif; ?>

    <!-- delete confirmation modal -->
    <div id="deleteModal" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-icon"><i class="fas fa-trash-alt"></i></div>
            <h3>Confirm Delete</h3>
            <p>Are you sure you want to delete this record? This action cannot be undone.</p>
            <div class="modal-btns">
                <button class="modal-cancel" onclick="closeDeleteModal()">Cancel</button>
                <button class="modal-confirm" id="confirmDeleteBtn">Yes, Delete</button>
            </div>
            <form id="deleteForm" method="POST" style="display:none">
                <input type="hidden" name="" id="deleteInput">
            </form>
        </div>
    </div>

    <div class="dash">


        <div class="page-head">
            <div class="page-head-icon"><i class="fas fa-chart-pie"></i></div>
            <div>
                <h1>Dashboard</h1>
                <p>Overview of your gym members, trainers, and assignments</p>
            </div>
        </div>

        <!-- stats -->
        <?php
        $sql = "SELECT members.*, 
                   trainers.first_name AS trainer_first_name, 
                   trainers.last_name AS trainer_last_name
            FROM `members`
            LEFT JOIN `trainers` ON members.trainer_id = trainers.trainer_id;";

        $run = $conn->query($sql);
        $results = $run->fetch_all(MYSQLI_ASSOC);
        $select_members = $results;

        $sql = "SELECT * FROM trainers";
        $run = $conn->query($sql);
        $results = $run->fetch_all(MYSQLI_ASSOC);

        $select_trainers = $results;

        $total_members = count($select_members);
        $total_trainers = count($select_trainers);
        $assigned_count = 0;
        foreach ($select_members as $m) {
            if ($m['trainer_first_name'])
                $assigned_count++;
        }
        ?>

        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-icon amber"><i class="fas fa-users"></i></div>
                <div class="stat-info">
                    <div class="stat-num"><?php echo $total_members; ?></div>
                    <div class="stat-label">Total Members</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fas fa-chalkboard-teacher"></i></div>
                <div class="stat-info">
                    <div class="stat-num"><?php echo $total_trainers; ?></div>
                    <div class="stat-label">Active Trainers</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-link"></i></div>
                <div class="stat-info">
                    <div class="stat-num"><?php echo $assigned_count; ?></div>
                    <div class="stat-label">Assigned Members</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon purple"><i class="fas fa-user-xmark"></i></div>
                <div class="stat-info">
                    <div class="stat-num"><?php echo ($total_members - $assigned_count); ?></div>
                    <div class="stat-label">Unassigned</div>
                </div>
            </div>
        </div>

        <!-- Members Section -->
        <div class="panel">
            <div class="panel-top">
                <div class="panel-title">
                    <div class="panel-title-icon"><i class="fas fa-users"></i></div> Members
                </div>
                <div class="panel-actions">
                    <div class="search-wrap">
                        <i class="fas fa-search"></i>
                        <input type="text" class="tbl-search" id="memberSearch" placeholder="Search members..."
                            onkeyup="filterTable('memberSearch','memberTbody')">
                    </div>

                </div>
            </div>
            <div class="tbl-wrap">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Trainer</th>
                            <th>Photo</th>
                            <th>Status</th>
                            <th>Valid Until</th>
                            <th>Joined</th>
                            <th style="text-align:right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="memberTbody">
                        <?php foreach ($select_members as $result): ?>
                            <tr>
                                <td class="cell-name"><?php echo $result['first_name'] . ' ' . $result['last_name']; ?></td>
                                <td><?php echo $result['email']; ?></td>
                                <td><?php echo $result['phone_number']; ?></td>
                                <td><?php
                                if ($result['trainer_first_name']) {
                                    echo '<span class="badge b-green"><i class="fas fa-circle"></i>' . $result['trainer_first_name'] . " " . $result['trainer_last_name'] . '</span>';
                                } else {
                                    echo '<span class="badge b-gray"><i class="fas fa-circle"></i>None</span>';
                                }
                                ?></td>
                                <td><img class="avatar" src="<?php echo $result['photo_path']; ?>"></td>
                                <td><?php
                                $valid_until = $result['valid_until'];
                                if ($valid_until && strtotime($valid_until) >= strtotime('today')) {
                                    echo '<span class="badge b-green"><i class="fas fa-circle"></i>Active</span>';
                                } else {
                                    echo '<span class="badge b-red"><i class="fas fa-circle"></i>Expired</span>';
                                }
                                ?></td>
                                <td class="date-cell"><?php
                                if ($valid_until) {
                                    echo date('M j, Y', strtotime($valid_until));
                                } else {
                                    echo '<span style="color:#4b5563">—</span>';
                                }
                                ?></td>
                                <td class="date-cell"><?php echo date("M j, Y", strtotime($result['created_at'])); ?></td>
                                <td>
                                    <div class="actions" style="justify-content:flex-end">

                                        <a href="edit_member.php?member_id=<?php echo $result['member_id']; ?>"
                                            class="act-btn act-edit" title="Edit"><i class="fas fa-pen"></i></a>
                                        <button type="button" class="act-btn act-del" title="Delete"
                                            onclick="openDeleteModal('delete_member.php','member_id','<?php echo $result['member_id']; ?>')"><i
                                                class="fas fa-trash-alt"></i></button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Trainers Section -->
        <div class="panel">
            <div class="panel-top">
                <div class="panel-title">
                    <div class="panel-title-icon"><i class="fas fa-chalkboard-teacher"></i></div> Trainers
                </div>
                <div class="panel-actions">
                    <div class="search-wrap">
                        <i class="fas fa-search"></i>
                        <input type="text" class="tbl-search" id="trainerSearch" placeholder="Search trainers..."
                            onkeyup="filterTable('trainerSearch','trainerTbody')">
                    </div>

                </div>
            </div>
            <div class="tbl-wrap">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Photo</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Joined</th>
                            <th style="text-align:right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="trainerTbody">
                        <?php foreach ($select_trainers as $result): ?>
                            <tr>
                                <td class="cell-name"><?php echo $result['first_name'] . ' ' . $result['last_name']; ?></td>
                                <td><?php if (!empty($result['photo_path'])) {
                                    echo '<img class="avatar" src="' . $result['photo_path'] . '">';
                                } else {
                                    echo '<div class="avatar" style="background:#1e2430;display:flex;align-items:center;justify-content:center"><i class="fas fa-user" style="color:#4b5563;font-size:.8rem"></i></div>';
                                } ?>
                                </td>
                                <td><?php echo $result['email']; ?></td>
                                <td><?php echo $result['phone_number']; ?></td>
                                <td class="date-cell"><?php echo date("M j, Y", strtotime($result['created_at'])); ?></td>
                                <td>
                                    <div class="actions" style="justify-content:flex-end">
                                        <a href="edit_trainer.php?trainer_id=<?php echo $result['trainer_id']; ?>"
                                            class="act-btn act-edit" title="Edit"><i class="fas fa-pen"></i></a>
                                        <button type="button" class="act-btn act-del" title="Delete"
                                            onclick="openDeleteModal('delete_trainer.php','trainer_id','<?php echo $result['trainer_id']; ?>')"><i
                                                class="fas fa-trash-alt"></i></button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>


        <div class="bottom-grid">

            <!-- Card 1: Assign Trainer -->
            <div class="panel">
                <div class="card-header">
                    <div class="card-header-icon" style="background:rgba(245,158,11,.1)">
                        <i class="fas fa-link" style="color:#fbbf24;font-size:.9rem"></i>
                    </div>
                    <div class="card-header-title">Assign Trainer</div>
                    <div class="card-header-sub">Link a trainer to a member</div>
                </div>
                <div class="card-body">
                    <form action="assign_trainer.php" method="POST">
                        <div>
                            <label class="form-label" for="member">Member</label>
                            <select name="member" class="form-select">
                                <?php foreach ($select_members as $member): ?>
                                    <option value="<?php echo $member['member_id']; ?>">
                                        <?php echo $member['first_name'] . " " . $member['last_name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="form-label" for="trainer">Trainer</label>
                            <select name="trainer" class="form-select">
                                <?php foreach ($select_trainers as $trainer): ?>
                                    <option value="<?php echo $trainer['trainer_id']; ?>">
                                        <?php echo $trainer['first_name'] . " " . $trainer['last_name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div style="text-align:center; padding-top:6px">
                            <button type="submit" class="btn-primary"><i class="fas fa-check"></i>&nbsp; Assign
                                Trainer</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Card 2: Membership Overview -->
            <div class="panel">
                <div class="card-header">
                    <div class="card-header-icon" style="background:rgba(16,185,129,.1)">
                        <i class="fas fa-chart-pie" style="color:#34d399;font-size:.9rem"></i>
                    </div>
                    <div class="card-header-title">Membership Overview</div>
                    <div class="card-header-sub">Current membership status</div>
                </div>
                <div class="card-body">
                    <?php
                    $today = date('Y-m-d');
                    $week_later = date('Y-m-d', strtotime('+7 days'));
                    $active_count = 0;
                    $expiring_count = 0;
                    $expired_count = 0;
                    foreach ($select_members as $m) {
                        if (!empty($m['valid_until'])) {
                            if ($m['valid_until'] < $today) {
                                $expired_count++;
                            } elseif ($m['valid_until'] <= $week_later) {
                                $expiring_count++;
                            } else {
                                $active_count++;
                            }
                        }
                    }
                    ?>
                    <ul class="membership-list">
                        <li class="membership-item">
                            <span class="mi-label">
                                <span class="mi-dot" style="background:#34d399"></span> Active
                            </span>
                            <span class="mi-count"><?php echo $active_count; ?></span>
                        </li>
                        <li class="membership-item">
                            <span class="mi-label">
                                <span class="mi-dot" style="background:#fbbf24"></span> Expiring Soon
                            </span>
                            <span class="mi-count"><?php echo $expiring_count; ?></span>
                        </li>
                        <li class="membership-item">
                            <span class="mi-label">
                                <span class="mi-dot" style="background:#ef4444"></span> Expired
                            </span>
                            <span class="mi-count"><?php echo $expired_count; ?></span>
                        </li>
                        <li class="membership-item">
                            <span class="mi-label">
                                <span class="mi-dot" style="background:#6366f1"></span> Total Members
                            </span>
                            <span class="mi-count"><?php echo $total_members; ?></span>
                        </li>
                    </ul>


                    <?php
                    $active_pct_m = $total_members > 0 ? round(($active_count / $total_members) * 100) : 0;
                    $expiring_pct_m = $total_members > 0 ? round(($expiring_count / $total_members) * 100) : 0;
                    $expired_pct_m = $total_members > 0 ? round(($expired_count / $total_members) * 100) : 0;
                    ?>
                    <div class="mo-summary">
                        <div class="mo-bar-label">Distribution</div>
                        <div class="mo-bar">
                            <div class="mo-bar-seg" style="width:<?php echo $active_pct_m; ?>%;background:#34d399"
                                title="Active <?php echo $active_pct_m; ?>%"></div>
                            <div class="mo-bar-seg" style="width:<?php echo $expiring_pct_m; ?>%;background:#fbbf24"
                                title="Expiring <?php echo $expiring_pct_m; ?>%"></div>
                            <div class="mo-bar-seg" style="width:<?php echo $expired_pct_m; ?>%;background:#ef4444"
                                title="Expired <?php echo $expired_pct_m; ?>%"></div>
                        </div>
                        <div class="mo-insights">
                            <div class="mo-insight">
                                <span class="mo-insight-val" style="color:#34d399">
                                    <?php echo $active_pct_m; ?>%
                                </span>
                                <span class="mo-insight-label">Active</span>
                            </div>
                            <div class="mo-insight">
                                <span class="mo-insight-val" style="color:#fbbf24">
                                    <?php echo $expiring_pct_m; ?>%
                                </span>
                                <span class="mo-insight-label">Expiring</span>
                            </div>
                            <div class="mo-insight">
                                <span class="mo-insight-val" style="color:#ef4444">
                                    <?php echo $expired_pct_m; ?>%
                                </span>
                                <span class="mo-insight-label">Expired</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3: Revenue Tracker -->
            <div class="panel">
                <div class="card-header">
                    <div class="card-header-icon" style="background:rgba(52,211,153,.1)">
                        <i class="fas fa-dollar-sign" style="color:#34d399;font-size:.9rem"></i>
                    </div>
                    <div class="card-header-title">Revenue Tracker</div>
                    <div class="card-header-sub">Earnings from memberships</div>
                </div>
                <div class="card-body">
                    <?php
                    $plan_prices = [
                        1 => ['name' => '1 Month Plan', 'price' => 25],
                        3 => ['name' => '3 Month Plan', 'price' => 60],
                        6 => ['name' => '6 Month Plan', 'price' => 100],
                        12 => ['name' => '12 Month Plan', 'price' => 180],
                    ];
                    $rev_data = []; // months => count
                    foreach ($select_members as $m) {
                        if (!empty($m['valid_until']) && !empty($m['created_at'])) {
                            $start = new DateTime($m['created_at']);
                            $end = new DateTime($m['valid_until']);
                            $diff = $start->diff($end);
                            $months = $diff->y * 12 + $diff->m;

                            $plan_key = 1;
                            foreach ([1, 3, 6, 12] as $pk) {
                                if ($months >= $pk)
                                    $plan_key = $pk;
                            }
                            if (!isset($rev_data[$plan_key]))
                                $rev_data[$plan_key] = 0;
                            $rev_data[$plan_key]++;
                        }
                    }

                    $rev_items = [];
                    $total_revenue = 0;
                    $max_revenue = 1;
                    foreach ($plan_prices as $pk => $info) {
                        $count = isset($rev_data[$pk]) ? $rev_data[$pk] : 0;
                        $revenue = $count * $info['price'];
                        $total_revenue += $revenue;
                        if ($revenue > $max_revenue)
                            $max_revenue = $revenue;
                        $rev_items[] = ['name' => $info['name'], 'price' => $info['price'], 'count' => $count, 'revenue' => $revenue];
                    }
                    $plan_colors = ['#34d399', '#f59e0b', '#6366f1', '#ec4899'];
                    ?>
                    <div class="revenue-list">
                        <?php foreach ($rev_items as $i => $rp): ?>
                            <?php $color = $plan_colors[$i % count($plan_colors)]; ?>
                            <?php $bar_pct = $max_revenue > 0 ? round(($rp['revenue'] / $max_revenue) * 100) : 0; ?>
                            <div class="rev-plan">
                                <div class="rev-plan-header">
                                    <span class="rev-plan-name"><?php echo $rp['name']; ?></span>
                                    <span class="rev-plan-amount">€<?php echo number_format($rp['revenue'], 0); ?></span>
                                </div>
                                <div class="rev-plan-detail"><?php echo $rp['count']; ?>
                                    member<?php echo $rp['count'] != 1 ? 's' : ''; ?> × €<?php echo $rp['price']; ?></div>
                                <div class="rev-bar-track">
                                    <div class="rev-bar-fill"
                                        style="width:<?php echo $bar_pct; ?>%;background:<?php echo $color; ?>"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <div class="rev-total">
                            <div class="rev-total-amount">€<?php echo number_format($total_revenue, 0); ?></div>
                            <div class="rev-total-label">Total Revenue</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>

    <script>
        // search filter for tables
        function filterTable(inputId, tbodyId) {
            var q = document.getElementById(inputId).value.toLowerCase();
            var rows = document.getElementById(tbodyId).getElementsByTagName('tr');
            for (var i = 0; i < rows.length; i++) {
                var txt = rows[i].textContent.toLowerCase();
                rows[i].style.display = txt.indexOf(q) > -1 ? '' : 'none';
            }
        }

        // delete confirmation modal
        var pendingDeleteAction = null;
        var pendingDeleteName = null;
        var pendingDeleteValue = null;

        function openDeleteModal(action, name, value) {
            pendingDeleteAction = action;
            pendingDeleteName = name;
            pendingDeleteValue = value;
            document.getElementById('deleteModal').classList.add('active');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('active');
            pendingDeleteAction = null;
        }

        document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
            if (pendingDeleteAction) {
                var form = document.getElementById('deleteForm');
                form.action = pendingDeleteAction;
                var input = document.getElementById('deleteInput');
                input.name = pendingDeleteName;
                input.value = pendingDeleteValue;
                form.submit();
            }
        });

        // close modal on backdrop click
        document.getElementById('deleteModal').addEventListener('click', function (e) {
            if (e.target === this) closeDeleteModal();
        });
    </script>

</body>

</html>

<?php $conn->close(); ?>