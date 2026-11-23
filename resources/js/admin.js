document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebar");
    const main = document.getElementById("main");
    const collapseBtn = document.getElementById("sidebarCollapse");
    const mobileBtn = document.getElementById("mobileSidebarToggle");
    const overlay = document.getElementById("overlay");

    /*
    |--------------------------------------------------------------------------
    | Desktop Sidebar Collapse
    |--------------------------------------------------------------------------
    */
    if (collapseBtn) {
        collapseBtn.addEventListener("click", function () {
            sidebar.classList.toggle("collapsed");
            main.classList.toggle("expanded");

            if (sidebar.classList.contains("collapsed")) {
                localStorage.setItem("sidebar", "collapsed");
            } else {
                localStorage.setItem("sidebar", "expanded");
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Load Sidebar State
    |--------------------------------------------------------------------------
    */
    const sidebarState = localStorage.getItem("sidebar");

    if (sidebarState === "collapsed") {
        sidebar.classList.add("collapsed");
        main.classList.add("expanded");
    }

    /*
    |--------------------------------------------------------------------------
    | Mobile Sidebar Open
    |--------------------------------------------------------------------------
    */
    if (mobileBtn) {
        mobileBtn.addEventListener("click", function () {
            sidebar.classList.add("mobile-open");
            overlay.style.display = "block";
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Close Sidebar On Overlay Click
    |--------------------------------------------------------------------------
    */
    if (overlay) {
        overlay.addEventListener("click", function () {
            sidebar.classList.remove("mobile-open");
            overlay.style.display = "none";
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Close Mobile Sidebar When Resize
    |--------------------------------------------------------------------------
    */
    window.addEventListener("resize", function () {
        if (window.innerWidth > 991) {
            sidebar.classList.remove("mobile-open");
            overlay.style.display = "none";
        }
    });

    /*
    |--------------------------------------------------------------------------
    | Close Mobile Sidebar After Clicking Menu
    |--------------------------------------------------------------------------
    */
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