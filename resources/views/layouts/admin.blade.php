<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title','Admin Panel')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    {{-- CK5Editor Links --}}
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.0.0/ckeditor5.css" />
	<script src="https://cdn.ckeditor.com/ckeditor5/43.0.0/ckeditor5.umd.js"></script>
    {{-- CK5Editor Premium Links --}}
    <style>
  
    /* Global */
    body {
        overflow-x: hidden;
        background: #f8f9fa;
    }
    /* Sidebar */

    .sidebar {
        width: 260px;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        background: #212529;
        color: white;
        z-index: 1050;
        transition: all .3s ease;
        overflow-y: auto;
    }

    /* Sidebar collapsed */

    .sidebar.collapsed {
        width: 80px;
    }

    /* Sidebar Header */

    .sidebar-header {
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 20px;
        border-bottom: 1px solid rgba(255, 255, 255, .1);
    }

    .brand {
        color: white;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 22px;
        font-weight: 600;
    }

    .brand:hover {
        color: white;
    }

    .brand i {
        font-size: 28px;
    }

    /* Hide brand text when collapsed */

    .sidebar.collapsed .brand-text {
        display: none;
    }

    .sidebar.collapsed #gameLogo { 
        display:none; 
    }


    /* Menu */


    .sidebar-menu {
        list-style: none;
        padding: 15px 0;
        margin: 0;
    }

    .menu-item {
        width: 100%;
    }

    .menu-link {
        display: flex;
        align-items: center;
        gap: 15px;
        width: 100%;
        padding: 13px 22px;
        color: #ced4da;
        text-decoration: none;
        background: none;
        border: none;
        transition: .2s;
    }

    .menu-link:hover {
        background: #343a40;
        color: white;
    }

    .menu-link.active {
        background: #0d6efd;
        color: white;
    }

    /* Icons */

    .menu-link i {
        font-size: 21px;
        min-width: 25px;
        text-align: center;
    }

    /* Hide text on collapse */

    .sidebar.collapsed .menu-link span {
        display: none;
    }

    /* Center icons */

    .sidebar.collapsed .menu-link {
        justify-content: center;
    }


    /* Arrow */


    .arrow {
        margin-left: auto;
        transition: .3s;
    }

    .menu-link:not(.collapsed) .arrow {
        transform: rotate(180deg);
    }

    .sidebar.collapsed .arrow {
        display: none;
    }

    /* ===========================
    Sub Menu
    =========================== */

    .submenu {
        background: #1c1f22;
        list-style: none;
        padding: 5px 0;
    }

    .submenu-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 20px 10px 30px;
        color: #adb5bd;
        text-decoration: none;
        transition: .2s;
    }

    .sidebar.collapsed .submenu span {
    display: none;
    }

    .submenu-link:hover {
        color: white;
        background: #343a40;
    }

    .submenu-link i {
        min-width: 20px;
        text-align: center;
    }

    /* Main Content */
    .main {
        margin-left: 260px;
        min-height: 100vh;
        transition: .3s ease;
    }

    .main.expanded {
        margin-left: 80px;
    }

    /* Navbar */
    .navbar {
        height: 70px;
    }

    /* Overlay Mobile */
    #overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .5);
        z-index: 1040;
    }


    /* Mobile Responsive */


    @media (max-width: 991px) {
        .sidebar {
            left: -260px;
        }

        .sidebar.mobile-open {
            left: 0;
        }

        .main {
            margin-left: 0;
        }

        .sidebar.collapsed {
            width: 260px;
        }
    }

    .menu-link.active {
    background: #0d6efd;
    color: #fff;
    }

    .submenu-link.active {
        background: #0d6efd;
        color: #fff;
        border-radius: 6px;
    }

    .submenu-link.active i {
        color: #fff;
    }

    /* Container positioned fixed over the content */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999; /* Keeps it above page content */
        display: flex;
        flex-direction: column;
        gap: 10px;
        max-width: 360px; /* Reduced width */
        width: 90%;
    }

    /* Floating Toast Card */
    .custom-toast {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        border-radius: 10px;
        background: #ffffff;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        border-left: 5px solid;
        animation: slideIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        transition: all 0.4s ease;
    }

    /* Success & Error Themes */
    .toast-success {
        border-left-color: #10b981;
        color: #065f46;
    }
    .toast-success .toast-icon {
        color: #10b981;
    }

    .toast-danger {
        border-left-color: #ef4444;
        color: #991b1b;
    }
    .toast-danger .toast-icon {
        color: #ef4444;
    }

    /* Layout Details */
    .toast-icon {
        display: flex;
        align-items: center;
        flex-shrink: 0;
    }

    .toast-message {
        font-size: 0.9rem;
        font-weight: 500;
        line-height: 1.4;
        flex-grow: 1;
    }

    /* Close Button */
    .toast-close {
        background: transparent;
        border: none;
        font-size: 18px;
        line-height: 1;
        color: #9ca3af;
        cursor: pointer;
        padding: 2px 4px;
        border-radius: 4px;
        transition: color 0.2s ease;
    }
    .toast-close:hover {
        color: #374151;
    }

    /* Smooth Slide-In Animation */
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    /* Hide State for Fade-Out */
    .custom-toast.hide {
        transform: translateX(120%);
        opacity: 0;
    }
    
    </style>
</head>
<body>
<div class="wrapper">
    @include('admin.partials.sidebar')
    <div class="main" id="main">
         @include('admin.partials.navbar')
        {{--@if(session('success'))
            <div class="alert alert-success alert-dismissible fade show auto-dismiss" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show auto-dismiss" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif --}}
        @include('admin.partials.flash-messages')
        <div class="content p-4">
            @yield('content')
        </div>
    </div>
</div>
<div id="overlay"></div>
</body>
</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Function to handle close button click
    function dismissToast(button) {
        const toast = button.closest('.custom-toast');
        if (toast) {
            toast.classList.add('hide');
            setTimeout(() => toast.remove(), 400);
        }
    }
    document.addEventListener("DOMContentLoaded", function () {
    // Select all alerts marked for auto-dismiss
    const toasts = document.querySelectorAll('.auto-dismiss');

    toasts.forEach(function (toast) {
        // Auto-close after 4.5 seconds
        setTimeout(function () {
            toast.classList.add('hide');
            setTimeout(function () {
                toast.remove();
            }, 400);
        }, 4500);
    });
    const sidebar = document.getElementById("sidebar");
    const main = document.getElementById("main");
    const collapseBtn = document.getElementById("sidebarCollapse");
    const mobileBtn = document.getElementById("mobileSidebarToggle");
    const overlay = document.getElementById("overlay");
    // Desktop Sidebar Collapse
    if (collapseBtn) {
        collapseBtn.addEventListener("click", function () {
            sidebar.classList.toggle("collapsed");
            main.classList.toggle("expanded");

            if (sidebar.classList.contains("collapsed")) {
                localStorage.setItem("sidebar", "collapsed");

                // Close all open submenus
                document.querySelectorAll(".submenu.show").forEach(function (submenu) {
                    bootstrap.Collapse.getOrCreateInstance(submenu).hide();
                });
            } else {
                localStorage.setItem("sidebar", "expanded");
            }
        });
    }
    // Load Sidebar State
    const sidebarState = localStorage.getItem("sidebar");

    if (sidebarState === "collapsed") {
        sidebar.classList.add("collapsed");
        main.classList.add("expanded");

        document.querySelectorAll(".submenu.show").forEach(function (submenu) {
            bootstrap.Collapse.getOrCreateInstance(submenu).hide();
        });
    }
    // Mobile Sidebar Open
    if (mobileBtn) {
        mobileBtn.addEventListener("click", function () {
            sidebar.classList.add("mobile-open");
            overlay.style.display = "block";
        });
    }
    // Close Sidebar On Overlay Click
    if (overlay) {
        overlay.addEventListener("click", function () {
            sidebar.classList.remove("mobile-open");
            overlay.style.display = "none";
        });
    }
    // Close Mobile Sidebar When Resize
    window.addEventListener("resize", function () {
        if (window.innerWidth > 991) {
            sidebar.classList.remove("mobile-open");
            overlay.style.display = "none";
        }
    });
    //  Close Mobile Sidebar After Clicking Menu
    const menuLinks = document.querySelectorAll(".menu-link");

    menuLinks.forEach(function (link) {
        link.addEventListener("click", function () {
            if (window.innerWidth <= 991) {
                sidebar.classList.remove("mobile-open");
                overlay.style.display = "none";
            }
        });
    });
});
</script>
@stack('scripts')