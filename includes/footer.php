    <footer class="rentx-footer bg-dark text-white pt-5 pb-4 mt-5">
        <div class="container">
            <div class="row g-4 justify-content-between">
                <div class="col-lg-4 col-md-6">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="brand-icon d-flex align-items-center justify-content-center text-white rounded-3 bg-primary" style="width:36px; height:36px; font-size: 18px;">
                            <i class="fa-solid fa-car"></i>
                        </div>
                        <span class="fs-4 fw-bold">Rent<span class="text-primary">X</span></span>
                    </div>
                    <p class="text-secondary mb-4 fs-6 leading-relaxed">
                        RentX offers quick, reliable, and affordable car and bike rentals across your city. Experience hassle-free mobility with top-rated vehicles within a 30 KM service radius.
                    </p>
                    <div class="d-flex gap-3 social-links">
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle p-2 d-flex align-items-center justify-content-center" style="width:36px; height:36px;"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle p-2 d-flex align-items-center justify-content-center" style="width:36px; height:36px;"><i class="fa-brands fa-twitter"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle p-2 d-flex align-items-center justify-content-center" style="width:36px; height:36px;"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle p-2 d-flex align-items-center justify-content-center" style="width:36px; height:36px;"><i class="fa-brands fa-linkedin-in"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6">
                    <h5 class="fw-bold mb-3 text-white fs-6 text-uppercase tracking-wider">Quick Navigation</h5>
                    <ul class="list-unstyled d-flex flex-column gap-2 footer-links">
                        <li><a href="index.php" class="text-secondary text-decoration-none hover-primary"><i class="fa-solid fa-angle-right me-2 fs-7"></i>Home</a></li>
                        <li><a href="index.php#cars" class="text-secondary text-decoration-none hover-primary"><i class="fa-solid fa-angle-right me-2 fs-7"></i>Cars</a></li>
                        <li><a href="index.php#bikes" class="text-secondary text-decoration-none hover-primary"><i class="fa-solid fa-angle-right me-2 fs-7"></i>Bikes</a></li>
                        <li><a href="about.php" class="text-secondary text-decoration-none hover-primary"><i class="fa-solid fa-angle-right me-2 fs-7"></i>About Us</a></li>
                        <li><a href="contact.php" class="text-secondary text-decoration-none hover-primary"><i class="fa-solid fa-angle-right me-2 fs-7"></i>Contact Support</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h5 class="fw-bold mb-3 text-white fs-6 text-uppercase tracking-wider">Customer Support</h5>
                    <ul class="list-unstyled d-flex flex-column gap-2 text-secondary fs-6">
                        <li class="d-flex align-items-start gap-2">
                            <i class="fa-solid fa-location-dot mt-1 text-primary"></i>
                            <span>123 Mobility Hub, Downtown Tech Street, Metro City</span>
                        </li>
                        <li class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-envelope text-primary"></i>
                            <a href="mailto:support@rentx.com" class="text-secondary text-decoration-none hover-primary">support@rentx.com</a>
                        </li>
                        <li class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-phone text-primary"></i>
                            <a href="tel:+919876543210" class="text-secondary text-decoration-none hover-primary">+91 9876543210</a>
                        </li>
                        <li class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-clock text-primary"></i>
                            <span>24/7 Roadside Assistance</span>
                        </li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h5 class="fw-bold mb-3 text-white fs-6 text-uppercase tracking-wider">Stay Updated</h5>
                    <p class="text-secondary fs-6 mb-3">Subscribe to get special discounts and seasonal vehicle offers.</p>
                    <form onsubmit="event.preventDefault(); Swal.fire({icon: 'success', title: 'Subscribed!', text: 'Thank you for subscribing to RentX deals & offers.', showConfirmButton: false, timer: 1500});">
                        <div class="newsletter-pill-container d-flex align-items-center w-100">
                            <input type="email" class="newsletter-input flex-grow-1" placeholder="Your email address" required autocomplete="email">
                            <button class="newsletter-btn flex-shrink-0" type="submit" title="Subscribe to RentX Newsletter">
                                <i class="fa-solid fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <hr class="border-secondary opacity-25 my-4">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 text-secondary fs-7">
                <p class="mb-0">© <?php echo date('Y'); ?> RentX. All Rights Reserved. Built with precision.</p>
                <div class="d-flex gap-3">
                    <a href="#" class="text-secondary text-decoration-none hover-primary">Privacy Policy</a>
                    <span>•</span>
                    <a href="#" class="text-secondary text-decoration-none hover-primary">Terms of Service</a>
                    <span>•</span>
                    <a href="#" class="text-secondary text-decoration-none hover-primary">Refund Policy</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button type="button" class="btn btn-primary rounded-circle shadow-lg position-fixed bottom-0 end-0 m-4 d-none" id="btnBackToTop" style="width:48px; height:48px; z-index:1000;" aria-label="Back to top">
        <i class="fa-solid fa-arrow-up fs-5"></i>
    </button>

    <!-- Quick Specs Preview Modal -->
    <div class="modal fade" id="quickViewModal" tabindex="-1" aria-labelledby="quickViewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header border-0 bg-primary text-white p-4">
                    <h5 class="modal-title fw-bold" id="quickViewModalLabel"><i class="fa-solid fa-car me-2"></i><span id="qvTitle">Vehicle Quick View</span></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-4 align-items-center">
                        <div class="col-md-6 text-center">
                            <img id="qvImage" src="" alt="Vehicle" class="img-fluid rounded-4 shadow-sm border p-2" style="max-height: 260px; object-fit: contain;">
                        </div>
                        <div class="col-md-6">
                            <span class="badge bg-primary-subtle text-primary rounded-pill mb-2 px-3 py-1 fw-bold fs-7" id="qvType">Car</span>
                            <h3 class="fw-bold mb-3 text-dark" id="qvName">Vehicle Name</h3>
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="fs-4 fw-extrabold text-primary" id="qvPrice">₹0 / KM</span>
                                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1" id="qvStatus">Available</span>
                            </div>
                            <div class="row g-2 mb-3 text-secondary fs-6">
                                <div class="col-6"><strong><i class="fa-solid fa-tag me-1 text-primary"></i> Brand:</strong> <span id="qvBrand">-</span></div>
                                <div class="col-6"><strong><i class="fa-solid fa-gas-pump me-1 text-primary"></i> Fuel:</strong> <span id="qvFuel">-</span></div>
                                <div class="col-6"><strong><i class="fa-solid fa-gears me-1 text-primary"></i> Gear:</strong> <span id="qvTrans">-</span></div>
                                <div class="col-6"><strong><i class="fa-solid fa-location-dot me-1 text-primary"></i> City:</strong> <span id="qvCity">-</span></div>
                            </div>
                            <p class="text-secondary small mb-4 bg-light p-3 rounded-3" id="qvDesc">No description available.</p>
                            <a id="qvBookBtn" href="#" class="btn btn-primary btn-lg rounded-pill w-100 fw-bold shadow-sm">
                                Book Now <i class="fa-solid fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- RentX Custom JS -->
    <script src="<?php echo $scriptPath; ?>"></script>
</body>
</html>