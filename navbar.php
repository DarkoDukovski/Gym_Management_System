<?php


$current_page = basename($_SERVER['PHP_SELF']);
?>
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
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        background: #0c0f16;
        color: #c9cdd5;
        min-height: 100vh;
    }

    a {
        text-decoration: none;
        color: inherit
    }

    ::-webkit-scrollbar {
        width: 5px;
        height: 5px
    }

    ::-webkit-scrollbar-track {
        background: #161a24
    }

    ::-webkit-scrollbar-thumb {
        background: #2d3343;
        border-radius: 4px
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #3b4150
    }

    .card {
        background: #151921;
        border: 1px solid #1e2430;
        border-radius: 16px;
    }

    /* Floating Navbar */
    .float-nav-wrap {
        position: sticky;
        top: 0;
        z-index: 200;
        max-width: 1280px;
        margin: 0 auto;
        padding: 14px 1rem 0;
    }

    .float-nav {
        width: 100%;
        background: rgba(16, 19, 25, .75);
        backdrop-filter: blur(24px) saturate(1.6);
        -webkit-backdrop-filter: blur(24px) saturate(1.6);
        border: 1px solid rgba(30, 36, 48, .7);
        border-radius: 18px;
        padding: 10px 10px 10px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 8px 32px rgba(0, 0, 0, .4), 0 0 0 1px rgba(255, 255, 255, .02) inset;
    }

    /* brand */
    .fn-brand {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-shrink: 0;
    }

    .fn-brand-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 14px rgba(245, 158, 11, .25);
        transition: transform .25s ease;
    }

    .fn-brand:hover .fn-brand-icon {
        transform: scale(1.06) rotate(-3deg);
    }

    .fn-brand-icon i {
        color: #fff;
        font-size: .88rem;
    }

    .fn-brand-name {
        font-size: 1.05rem;
        font-weight: 800;
        color: #f9fafb;
        letter-spacing: -.02em;
    }

    /* nav links center */
    .fn-links {
        display: flex;
        align-items: center;
        gap: 4px;
        background: rgba(255, 255, 255, .025);
        border: 1px solid rgba(30, 36, 48, .5);
        border-radius: 14px;
        padding: 4px;
    }

    .fn-link {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        font-size: .8rem;
        font-weight: 500;
        color: #6b7280;
        border-radius: 11px;
        transition: all .22s ease;
        white-space: nowrap;
    }

    .fn-link i {
        font-size: .72rem;
    }

    .fn-link:hover {
        color: #d1d5db;
        background: rgba(255, 255, 255, .05);
    }

    .fn-link.active {
        color: #0c0f16;
        background: linear-gradient(135deg, #f59e0b, #eab308);
        font-weight: 600;
        box-shadow: 0 4px 16px rgba(245, 158, 11, .25);
    }

    .fn-link.active i {
        color: #0c0f16;
    }

    /* right side */
    .fn-right {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }

    .fn-logout {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 12px;
        border: 1px solid rgba(30, 36, 48, .6);
        background: rgba(255, 255, 255, .03);
        color: #6b7280;
        font-size: .8rem;
        font-weight: 500;
        cursor: pointer;
        transition: all .2s;
        white-space: nowrap;
    }

    .fn-logout:hover {
        border-color: rgba(239, 68, 68, .3);
        color: #ef4444;
        background: rgba(239, 68, 68, .06);
    }

    /* mobile hamburger */
    .fn-mobile-btn {
        display: none;
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, .03);
        border: 1px solid rgba(30, 36, 48, .6);
        border-radius: 11px;
        color: #6b7280;
        cursor: pointer;
        align-items: center;
        justify-content: center;
        font-size: .9rem;
        transition: all .2s;
    }

    .fn-mobile-btn:hover {
        color: #d1d5db;
        border-color: #2d3343;
    }

    /* mobile dropdown */
    .fn-mobile-menu {
        display: none;
        max-width: 1200px;
        margin: 6px auto 0;
        background: rgba(16, 19, 25, .9);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(30, 36, 48, .7);
        border-radius: 16px;
        padding: 8px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, .4);
        animation: fnDropIn .25s ease;
    }

    .fn-mobile-menu.open {
        display: block;
    }

    @keyframes fnDropIn {
        from {
            opacity: 0;
            transform: translateY(-8px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fn-mobile-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 14px;
        border-radius: 12px;
        font-size: .84rem;
        font-weight: 500;
        color: #6b7280;
        transition: all .2s;
        margin-bottom: 2px;
    }

    .fn-mobile-link i {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        background: rgba(255, 255, 255, .03);
        font-size: .72rem;
        flex-shrink: 0;
    }

    .fn-mobile-link:hover {
        color: #d1d5db;
        background: rgba(255, 255, 255, .04);
    }

    .fn-mobile-link.active {
        color: #f59e0b;
        background: rgba(245, 158, 11, .06);
    }

    .fn-mobile-link.active i {
        background: rgba(245, 158, 11, .12);
        color: #f59e0b;
    }

    .fn-mobile-sep {
        height: 1px;
        background: rgba(30, 36, 48, .6);
        margin: 6px 12px;
    }

    .fn-mobile-link.fn-mob-logout:hover {
        color: #ef4444;
        background: rgba(239, 68, 68, .05);
    }

    .fn-mobile-link.fn-mob-logout:hover i {
        background: rgba(239, 68, 68, .1);
        color: #ef4444;
    }

    @media (max-width: 900px) {
        .fn-links {
            display: none;
        }

        .fn-logout {
            display: none;
        }

        .fn-mobile-btn {
            display: flex;
        }
    }
</style>

<div class="float-nav-wrap">
    <nav class="float-nav">
        <!-- Brand -->
        <a href="admin_dashboard.php" class="fn-brand">
            <div class="fn-brand-icon"><i class="fas fa-dumbbell"></i></div>
            <span class="fn-brand-name">GymAdmin</span>
        </a>

        <!-- Center: Nav Links Pill -->
        <div class="fn-links">
            <a href="admin_dashboard.php"
                class="fn-link <?php echo $current_page === 'admin_dashboard.php' ? 'active' : ''; ?>">
                <i class="fas fa-chart-pie"></i> Dashboard
            </a>
            <a href="registermember.php"
                class="fn-link <?php echo $current_page === 'registermember.php' ? 'active' : ''; ?>">
                <i class="fas fa-user-plus"></i> New Member
            </a>
            <a href="register_trainer.php"
                class="fn-link <?php echo $current_page === 'register_trainer.php' ? 'active' : ''; ?>">
                <i class="fas fa-chalkboard-teacher"></i> New Trainer
            </a>
            <a href="register_admin.php"
                class="fn-link <?php echo $current_page === 'register_admin.php' ? 'active' : ''; ?>">
                <i class="fas fa-user-shield"></i> New Admin
            </a>
        </div>

        <!-- Right -->
        <div class="fn-right">
            <a href="logout.php" class="fn-logout">
                <i class="fas fa-right-from-bracket"></i> Logout
            </a>

            <button class="fn-mobile-btn" onclick="document.getElementById('fnMobile').classList.toggle('open')">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>

    <!-- Mobile dropdown -->
    <div id="fnMobile" class="fn-mobile-menu">
        <a href="admin_dashboard.php"
            class="fn-mobile-link <?php echo $current_page === 'admin_dashboard.php' ? 'active' : ''; ?>">
            <i class="fas fa-chart-pie"></i> Dashboard
        </a>
        <a href="registermember.php"
            class="fn-mobile-link <?php echo $current_page === 'registermember.php' ? 'active' : ''; ?>">
            <i class="fas fa-user-plus"></i> New Member
        </a>
        <a href="register_trainer.php"
            class="fn-mobile-link <?php echo $current_page === 'register_trainer.php' ? 'active' : ''; ?>">
            <i class="fas fa-chalkboard-teacher"></i> New Trainer
        </a>
        <a href="register_admin.php"
            class="fn-mobile-link <?php echo $current_page === 'register_admin.php' ? 'active' : ''; ?>">
            <i class="fas fa-user-shield"></i> New Admin
        </a>
        <div class="fn-mobile-sep"></div>
        <a href="logout.php" class="fn-mobile-link fn-mob-logout">
            <i class="fas fa-right-from-bracket"></i> Logout
        </a>
    </div>
</div>

<div style="height:28px"></div>

<section id="trainers-list-section"></section>

<script>
    function scrollToTrainersList() {
        var trainersListSection = document.getElementById('trainers-list-section');
        trainersListSection.scrollIntoView({ behavior: 'smooth' });
    }
</script>