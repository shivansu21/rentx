<?php include "includes/header.php"; ?>

<div class="container py-5">
    <!-- Page Header -->
    <div class="text-center mb-5">
        <span class="badge rounded-pill px-3 py-1 fw-bold fs-7 mb-3" style="background:linear-gradient(135deg,#d1fae5,#a7f3d0);color:#059669;">
            <i class="fa-solid fa-car me-1"></i> ABOUT US
        </span>
        <h1 class="display-5 fw-extrabold mb-3">About <span style="background:linear-gradient(135deg,#10b981,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">RentX</span> Platform</h1>
        <p class="text-secondary fs-5 mx-auto" style="max-width:560px;">Empowering seamless urban mobility with verified cars and bikes on demand across your city.</p>
    </div>

    <!-- Feature Cards -->
    <div class="row g-4 mb-5">
        <?php
        $features = [
            ['icon'=>'fa-car',         'color'=>'#10b981', 'bg'=>'#d1fae5', 'title'=>'Wide Vehicle Range',     'desc'=>'From economic scooters to luxury sedans and family SUVs, find the ride that fits your travel style.'],
            ['icon'=>'fa-shield-halved','color'=>'#059669','bg'=>'#a7f3d0', 'title'=>'Safe & Verified',         'desc'=>'Every vehicle undergoes thorough multi-point inspection and licence verification before every trip.'],
            ['icon'=>'fa-bolt',        'color'=>'#06b6d4', 'bg'=>'#cffafe', 'title'=>'Instant Online Booking', 'desc'=>'Instant online booking with transparent per-kilometer estimates and digital confirmation in 60 seconds.'],
            ['icon'=>'fa-headset',     'color'=>'#34d399', 'bg'=>'#d1fae5', 'title'=>'24/7 Roadside Support', 'desc'=>'Our dedicated team is ready around the clock to support you with emergency roadside assistance.'],
        ];
        foreach ($features as $f):
        ?>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100" style="transition:transform 0.2s,box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-5px)';this.style.boxShadow='0 20px 40px rgba(0,0,0,0.12)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3 mx-auto" style="width:68px;height:68px;background:<?php echo $f['bg']; ?>;font-size:28px;color:<?php echo $f['color']; ?>;">
                    <i class="fa-solid <?php echo $f['icon']; ?>"></i>
                </div>
                <h5 class="fw-bold mb-2"><?php echo $f['title']; ?></h5>
                <p class="text-secondary small mb-0"><?php echo $f['desc']; ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Stats Banner -->
    <div class="rounded-4 p-5 mb-5 text-white text-center" style="background:linear-gradient(135deg,#059669 0%,#10b981 50%,#06b6d4 100%);">
        <div class="row g-4">
            <div class="col-md-3 col-6">
                <div class="fs-1 fw-extrabold">500+</div>
                <div class="opacity-85">Verified Vehicles</div>
            </div>
            <div class="col-md-3 col-6">
                <div class="fs-1 fw-extrabold">10K+</div>
                <div class="opacity-85">Happy Customers</div>
            </div>
            <div class="col-md-3 col-6">
                <div class="fs-1 fw-extrabold">30KM</div>
                <div class="opacity-85">Service Radius</div>
            </div>
            <div class="col-md-3 col-6">
                <div class="fs-1 fw-extrabold">24/7</div>
                <div class="opacity-85">Live Support</div>
            </div>
        </div>
    </div>

    <!-- Mission Section -->
    <div class="row g-4 align-items-center mb-5">
        <div class="col-lg-6">
            <span class="badge rounded-pill px-3 py-1 fw-bold fs-7 mb-3" style="background:#d1fae5;color:#059669;">OUR MISSION</span>
            <h2 class="fw-extrabold mb-3">Mobility That Moves <span style="background:linear-gradient(135deg,#10b981,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">With You</span></h2>
            <p class="text-secondary fs-6 mb-4">RentX was founded with a simple belief — renting a vehicle should be as easy as ordering food online. We remove the friction, hidden costs, and paperwork from vehicle rental so you can focus on the journey.</p>
            <div class="d-flex flex-column gap-3">
                <?php
                $points = [
                    ['icon'=>'fa-check-circle','text'=>'Transparent per-KM pricing with zero hidden charges'],
                    ['icon'=>'fa-check-circle','text'=>'Admin-verified licence before every approved booking'],
                    ['icon'=>'fa-check-circle','text'=>'Fully insured fleet with 30KM service radius coverage'],
                ];
                foreach ($points as $pt):
                ?>
                <div class="d-flex align-items-center gap-3">
                    <i class="fa-solid <?php echo $pt['icon']; ?> fs-5" style="color:#10b981;"></i>
                    <span class="fw-semibold"><?php echo $pt['text']; ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="col-lg-6 text-center">
            <div class="rounded-4 p-5 d-inline-block" style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);">
                <i class="fa-solid fa-car-side" style="font-size:100px;color:#10b981;opacity:0.9;"></i>
            </div>
        </div>
    </div>

    <!-- CTA -->
    <div class="text-center py-5 rounded-4 border" style="border-color:rgba(16,185,129,0.3) !important; background:linear-gradient(135deg,rgba(16,185,129,0.05),rgba(6,182,212,0.05));">
        <h3 class="fw-extrabold mb-3">Ready to Hit the Road?</h3>
        <p class="text-secondary mb-4">Browse our verified fleet and book your vehicle in under 60 seconds.</p>
        <a href="index.php" class="btn-emerald-submit" style="padding:14px 36px;text-decoration:none;width:auto;display:inline-flex;font-size:16px;">
            <i class="fa-solid fa-car me-2"></i> Browse Vehicles
        </a>
    </div>
</div>

<?php include "includes/footer.php"; ?>