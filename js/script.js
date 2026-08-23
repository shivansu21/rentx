/**
 * RentX Front-End Interactive Script
 * Handles live search filtering, vehicle quick view modal, booking calculator, and UI animations.
 */

document.addEventListener("DOMContentLoaded", function () {

    // 1. Back to Top Button
    const btnBackToTop = document.getElementById("btnBackToTop");
    if (btnBackToTop) {
        window.addEventListener("scroll", function () {
            if (window.scrollY > 300) {
                btnBackToTop.classList.remove("d-none");
            } else {
                btnBackToTop.classList.add("d-none");
            }
        });

        btnBackToTop.addEventListener("click", function () {
            window.scrollTo({ top: 0, behavior: "smooth" });
        });
    }

    // 2. Smooth-scroll for in-page anchor links
    document.querySelectorAll('a[href*="#"]').forEach(function (link) {
        link.addEventListener("click", function (e) {
            const href = link.getAttribute("href");
            const hash = href.includes("#") ? href.split("#")[1] : null;
            if (!hash) return;

            const target = document.getElementById(hash);
            if (!target) return;

            const linkPage = href.split("#")[0];
            const currentPage = window.location.pathname.split("/").pop();

            if (linkPage === "" || linkPage === currentPage || href.startsWith("#")) {
                e.preventDefault();
                target.scrollIntoView({ behavior: "smooth" });
            }
        });
    });

    // 3. Quick View Modal Trigger
    const quickViewModal = document.getElementById('quickViewModal');
    if (quickViewModal) {
        document.querySelectorAll('.btn-quick-view').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const name = this.getAttribute('data-name');
                const brand = this.getAttribute('data-brand');
                const type = this.getAttribute('data-type');
                const price = this.getAttribute('data-price');
                const fuel = this.getAttribute('data-fuel');
                const trans = this.getAttribute('data-trans');
                const city = this.getAttribute('data-city');
                const status = this.getAttribute('data-status');
                const image = this.getAttribute('data-image');
                const desc = this.getAttribute('data-desc');
                const id = this.getAttribute('data-id');

                document.getElementById('qvTitle').textContent = name || 'Vehicle Specs';
                document.getElementById('qvName').textContent = name || '-';
                document.getElementById('qvBrand').textContent = brand || '-';
                document.getElementById('qvType').textContent = type || '-';
                document.getElementById('qvPrice').textContent = '₹' + price + ' / KM';
                document.getElementById('qvFuel').textContent = fuel || '-';
                document.getElementById('qvTrans').textContent = trans || '-';
                document.getElementById('qvCity').textContent = city || '-';
                document.getElementById('qvDesc').textContent = desc || 'No description available.';
                document.getElementById('qvImage').src = image;

                const qvStatus = document.getElementById('qvStatus');
                const qvBookBtn = document.getElementById('qvBookBtn');

                if (status === 'Available') {
                    qvStatus.className = 'badge bg-success-subtle text-success rounded-pill px-3 py-1';
                    qvStatus.textContent = '🟢 Available';
                    qvBookBtn.classList.remove('disabled');
                    qvBookBtn.href = 'booking.php?id=' + id;
                    qvBookBtn.innerHTML = 'Book Now <i class="fa-solid fa-arrow-right ms-2"></i>';
                } else {
                    qvStatus.className = 'badge bg-danger-subtle text-danger rounded-pill px-3 py-1';
                    qvStatus.textContent = '🔴 Currently Booked';
                    qvBookBtn.classList.add('disabled');
                    qvBookBtn.href = '#';
                    qvBookBtn.innerHTML = 'Not Available';
                }

                const modal = new bootstrap.Modal(quickViewModal);
                modal.show();
            });
        });
    }

    // 4. Live Instant Filter (on home/index page)
    const liveSearchInput = document.getElementById('liveSearchInput');
    const liveTypeFilter = document.getElementById('liveTypeFilter');

    function filterVehicles() {
        const query = (liveSearchInput ? liveSearchInput.value : '').toLowerCase().trim();
        const type = (liveTypeFilter ? liveTypeFilter.value : '').toLowerCase();

        const cards = document.querySelectorAll('.vehicle-card-col');
        let visibleCount = 0;

        cards.forEach(function (card) {
            const cardName = (card.getAttribute('data-name') || '').toLowerCase();
            const cardBrand = (card.getAttribute('data-brand') || '').toLowerCase();
            const cardType = (card.getAttribute('data-type') || '').toLowerCase();

            const matchesSearch = query === '' || cardName.includes(query) || cardBrand.includes(query);
            const matchesType = type === '' || cardType === type;

            if (matchesSearch && matchesType) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        const noResultsMsg = document.getElementById('noResultsMessage');
        if (noResultsMsg) {
            if (visibleCount === 0 && cards.length > 0) {
                noResultsMsg.classList.remove('d-none');
            } else {
                noResultsMsg.classList.add('d-none');
            }
        }
    }

    if (liveSearchInput) {
        liveSearchInput.addEventListener('input', filterVehicles);
    }
    if (liveTypeFilter) {
        liveTypeFilter.addEventListener('change', filterVehicles);
    }

    // 5. Booking Live Cost Calculator
    const kmInput = document.getElementById("km");
    const priceInput = document.getElementById("price");
    const totalInput = document.getElementById("total_amount");

    if (kmInput && priceInput && totalInput) {
        function calculateTotal() {
            const kmValue = parseFloat(kmInput.value);
            const priceValue = parseFloat(priceInput.value);

            if (isNaN(kmValue) || kmValue <= 0) {
                totalInput.value = "";
                const calcSummary = document.getElementById("calcSummary");
                if (calcSummary) calcSummary.classList.add("d-none");
                return;
            }

            const amount = kmValue * priceValue;
            totalInput.value = "₹ " + amount.toFixed(2);

            const calcSummary = document.getElementById("calcSummary");
            const calcKm = document.getElementById("calcKm");
            const calcPrice = document.getElementById("calcPrice");
            const calcTotal = document.getElementById("calcTotal");

            if (calcSummary && calcKm && calcPrice && calcTotal) {
                calcSummary.classList.remove("d-none");
                calcKm.textContent = kmValue + " KM";
                calcPrice.textContent = "₹" + priceValue + " / KM";
                calcTotal.textContent = "₹" + amount.toFixed(2);
            }
        }

        kmInput.addEventListener("input", calculateTotal);
    }

    // 7. Dark / Light Mode Toggle System
    const currentTheme = localStorage.getItem("rentx_theme");

    function applyTheme(theme) {
        const themeBtns = document.querySelectorAll("#themeToggleBtn, .admin-theme-toggle, .theme-dash-toggle");

        if (theme === "light") {
            document.body.classList.remove("dark-theme");
            document.body.classList.add("light-theme");
            document.documentElement.setAttribute("data-bs-theme", "light");
            themeBtns.forEach(function (btn) {
                const icon = btn.querySelector("i") || document.getElementById("themeToggleIcon");
                const text = btn.querySelector("span") || document.getElementById("themeToggleText");
                if (icon) {
                    icon.className = "fa-solid fa-moon text-warning";
                }
                if (text) {
                    text.textContent = "Dark Mode";
                }
            });
        } else {
            document.body.classList.remove("light-theme");
            document.body.classList.add("dark-theme");
            document.documentElement.setAttribute("data-bs-theme", "dark");
            themeBtns.forEach(function (btn) {
                const icon = btn.querySelector("i") || document.getElementById("themeToggleIcon");
                const text = btn.querySelector("span") || document.getElementById("themeToggleText");
                if (icon) {
                    icon.className = "fa-solid fa-sun text-warning";
                }
                if (text) {
                    text.textContent = "Light Mode";
                }
            });
        }
    }

    if (currentTheme) {
        applyTheme(currentTheme);
    } else {
        applyTheme("dark");
    }

    function toggleTheme() {
        const isDark = document.body.classList.contains("dark-theme");
        const nextTheme = isDark ? "light" : "dark";
        localStorage.setItem("rentx_theme", nextTheme);
        applyTheme(nextTheme);
    }

    document.querySelectorAll("#themeToggleBtn, .admin-theme-toggle, .theme-dash-toggle").forEach(function (btn) {
        btn.addEventListener("click", toggleTheme);
    });

    // ==========================================================================
    // 8. INTERACTIVE VISUAL ANIMATIONS & CUSTOMER ENGAGEMENT
    // ==========================================================================

    // A. 3D Mouse Parallax Tilt on Hero Stage
    const heroStage = document.querySelector(".hero-vehicle-stage");
    const heroImg = document.querySelector(".floating-hero-img");

    if (heroStage && heroImg) {
        heroStage.addEventListener("mousemove", function (e) {
            const rect = heroStage.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;
            
            const rotateX = (-y / rect.height) * 18;
            const rotateY = (x / rect.width) * 18;

            heroImg.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.05, 1.05, 1.05)`;
        });

        heroStage.addEventListener("mouseleave", function () {
            heroImg.style.transform = "perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)";
        });
    }

    // B. Card Cursor Spotlight Tracker
    document.querySelectorAll(".rentx-card, .kpi-stat-card, .search-card-widget").forEach(function (card) {
        card.addEventListener("mousemove", function (e) {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            card.style.setProperty("--mouse-x", `${x}px`);
            card.style.setProperty("--mouse-y", `${y}px`);
        });
    });

    // C. Intersection Observer Scroll Reveal Animation
    const observerOptions = {
        threshold: 0.1,
        rootMargin: "0px 0px -50px 0px"
    };

    const revealObserver = new IntersectionObserver(function (entries, observer) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add("revealed");
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll(".rentx-card, section h2, .search-card-widget").forEach(function (el) {
        el.classList.add("reveal-on-scroll");
        revealObserver.observe(el);
    });

    // D. Animated Number Count-Up for Hero Stats
    const statElements = document.querySelectorAll(".hero-wrapper h4, .hero-wrapper h3");
    let counted = false;

    const statsObserver = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting && !counted) {
                counted = true;
                statElements.forEach(function (el) {
                    const text = el.textContent.trim();
                    if (text.includes("500")) {
                        animateCount(el, 0, 500, "+");
                    } else if (text.includes("100")) {
                        animateCount(el, 0, 100, "%");
                    }
                });
            }
        });
    }, observerOptions);

    if (statElements.length > 0) {
        const heroSection = document.querySelector(".hero-wrapper");
        if (heroSection) statsObserver.observe(heroSection);
    }

    function animateCount(el, start, end, suffix) {
        let current = start;
        const duration = 1500;
        const stepTime = 20;
        const increment = (end - start) / (duration / stepTime);
        
        const timer = setInterval(function () {
            current += increment;
            if (current >= end) {
                current = end;
                clearInterval(timer);
            }
            el.textContent = Math.floor(current) + suffix;
        }, stepTime);
    }

    // E. Interactive Favorite / Wishlist Heart Button
    document.querySelectorAll(".favorite-btn").forEach(function (btn) {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.toggle("active");
            const isFav = this.classList.contains("active");
            
            if (window.Swal) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: isFav ? 'success' : 'info',
                    title: isFav ? 'Added to Saved Rides ❤️' : 'Removed from Saved Rides',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });
            }
        });
    });

    // F. Password Visibility Toggle Handler
    document.querySelectorAll(".toggle-password-btn, .toggle-password").forEach(function (btn) {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            const targetId = this.getAttribute("data-target");
            const input = targetId ? document.getElementById(targetId) : this.closest(".custom-input-group, .input-group").querySelector("input");
            
            if (input) {
                const isPass = input.type === "password";
                input.type = isPass ? "text" : "password";
                
                const icon = this.querySelector("i") || this;
                if (icon) {
                    if (isPass) {
                        icon.classList.remove("fa-eye", "fa-regular");
                        icon.classList.add("fa-eye-slash", "fa-solid", "text-primary");
                    } else {
                        icon.classList.remove("fa-eye-slash", "fa-solid", "text-primary");
                        icon.classList.add("fa-eye", "fa-regular");
                    }
                }
            }
        });
    });

});


