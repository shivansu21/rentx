// RentX front-end helper script

document.addEventListener("DOMContentLoaded", function () {

    // Smooth-scroll for in-page anchor links (e.g. index.php#cars, index.php#bikes)
    document.querySelectorAll('a[href*="#"]').forEach(function (link) {
        const hash = link.getAttribute("href").split("#")[1];
        if (!hash) return;

        const target = document.getElementById(hash);
        if (!target) return;

        link.addEventListener("click", function (e) {
            // Only intercept links that stay on the current page
            const linkPage = link.getAttribute("href").split("#")[0];
            const currentPage = window.location.pathname.split("/").pop();

            if (linkPage === "" || linkPage === currentPage) {
                e.preventDefault();
                target.scrollIntoView({ behavior: "smooth" });
            }
        });
    });

});
