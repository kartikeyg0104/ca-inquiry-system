<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaxSafar — Financial Compliance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/public/assets/css/style.css">
</head>
<body>

    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand text-decoration-none" href="#">Tax<span>Safar</span></a>
            <button class="navbar-toggler border-0 shadow-none px-0" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
                <i class="fa-solid fa-bars fs-2 text-indigo"></i>
            </button>
            
            <div class="collapse navbar-collapse" id="navContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-3">
                    <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#inquiry">Contact</a></li>
                </ul>
                <div class="d-flex align-items-center gap-3 mt-4 mt-lg-0">
                    <a href="index.php?page=login" class="nav-link fw-bold px-0 me-2 text-decoration-none">Log in</a>
                    <a href="#inquiry" class="btn btn-mint px-4 py-2">Consult Now</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero text-center text-lg-start">
        <div class="hero-bg-shapes">
            <div class="shape-1"></div>
            <div class="shape-2"></div>
            <div class="shape-block"></div>
            <div class="shape-block-small"></div>
        </div>
        
        <div class="container px-4 px-lg-5 position-relative z-1 mb-5">
            <div class="row align-items-center justify-content-center text-center">
                <div class="col-lg-11 col-xl-10">
                    <h1 class="hero-title">Your complete<br>financial compliance.</h1>
                    <p class="hero-subtitle mx-auto">Scale your business faster without worrying about taxes, audits, or legal registrations. We handle the numbers so you can focus on growth.</p>
                    
                    <div class="d-flex justify-content-center gap-4 flex-wrap mt-5">
                        <a href="#inquiry" class="btn btn-mint d-inline-flex align-items-center py-3 px-5 fs-5">
                            Consult Now <i class="fa-solid fa-arrow-right ms-2 fs-6"></i>
                        </a>
                        <a href="#services" class="btn btn-outline-indigo py-3 px-5 fs-5">
                            Our Services
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Trust Badge Strip -->
    <section class="trust-strip">
        <div class="container px-4 px-lg-5">
            <div class="row g-4 align-items-center justify-content-center">
                <div class="col-sm-6 col-lg-3">
                    <div class="trust-badge">
                        <i class="fa-solid fa-shield-halved"></i>
                        Certified Experts
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="trust-badge">
                        <i class="fa-solid fa-bolt"></i>
                        Fast & Reliable
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="trust-badge">
                        <i class="fa-solid fa-lock"></i>
                        Highly Secure
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="trust-badge">
                        <i class="fa-solid fa-chart-line"></i>
                        Data-Driven
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-5 bg-white">
        <div class="container px-4 px-lg-5 py-5 my-5">
            <div class="text-center mb-5 pb-4">
                <h2 class="display-4 fw-black mt-2 mb-4" style="color: var(--indigo); font-weight: 900; letter-spacing: -2px;">Premium Corporate Solutions</h2>
                <p class="text-muted fs-5 mx-auto" style="max-width: 650px; color: var(--gray-400);">Everything you need to launch, scale, and secure your company's fiscal future with absolute confidence.</p>
            </div>

            <div class="row g-5">
                <?php
                $services = [
                    ['icon' => 'fa-building', 'title' => 'Company Registration', 'desc' => 'Seamless incorporation processes for Private Limited, Section 8, and LLPs.'],
                    ['icon' => 'fa-file-invoice-dollar', 'title' => 'Income Tax (ITR)', 'desc' => 'Professional planning, advisory, and filing of Income Tax correctly & on time.'],
                    ['icon' => 'fa-receipt', 'title' => 'GST Services', 'desc' => 'Fast GST registration, compliance management, and strategic input tax credit advisory.'],
                    ['icon' => 'fa-calculator', 'title' => 'Outsourced Bookkeeping', 'desc' => 'Clean, modern, and cloud-based accounting services so your books are always ready.'],
                    ['icon' => 'fa-scale-balanced', 'title' => 'Legal Compliance', 'desc' => 'ROC form filings, DIN director setup, and corporate secretarial services.'],
                    ['icon' => 'fa-wallet', 'title' => 'Payroll & HR', 'desc' => 'Hassle-free salary processing, PF, ESIC registrations, and TDS deductions.']
                ];
                
                foreach ($services as $svc):
                ?>
                <div class="col-md-6 col-lg-4">
                    <div class="service-box">
                        <div class="service-icon"><i class="fa-solid <?php echo $svc['icon']; ?>"></i></div>
                        <h4 class="service-title"><?php echo $svc['title']; ?></h4>
                        <p class="service-desc"><?php echo $svc['desc']; ?></p>
                        <a href="#inquiry" class="learn-more">
                            Learn more <i class="fa-solid fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
                <?php endflush; endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Inquiry Section -->
    <section id="inquiry" class="py-5" style="background-color: var(--gray-50);">
        <div class="container px-4 px-lg-5 py-5 my-5">
            <div class="row justify-content-center mb-5 pb-3">
                <div class="col-lg-7 text-center">
                    <h2 class="display-4 fw-black mb-4" style="color: var(--indigo); font-weight: 900; letter-spacing: -2px;">Speak with a CA.</h2>
                    <p class="fs-5" style="color: var(--gray-400);">Fill out your details securely, and our senior financial advisor will connect with you within 2-4 business hours.</p>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="form-container">
                        
                        <?php if (isset($_SESSION['flash_success'])): ?>
                            <div class="alert alert-success d-flex align-items-center mb-5 p-4 border-0 rounded-4" style="background: var(--mint-green-light); color: var(--indigo);">
                                <i class="fa-solid fa-circle-check fs-3 me-3" style="color: var(--mint-green-dark);"></i>
                                <span class="fw-bold fs-5"><?php echo $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['flash_error'])): ?>
                            <div class="alert alert-danger d-flex align-items-center mb-5 p-4 border-0 rounded-4">
                                <i class="fa-solid fa-triangle-exclamation fs-3 me-3"></i>
                                <span class="fw-bold fs-5"><?php echo $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?></span>
                            </div>
                        <?php endif; ?>

                        <form action="index.php?page=process_submit" method="POST">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                            
                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" class="form-control" name="full_name" placeholder="E.g., Jane Smith" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Mobile Number</label>
                                    <input type="tel" class="form-control" name="mobile" placeholder="Phone number" pattern="[0-9]{10}" required>
                                </div>
                            </div>
                            
                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" class="form-control" name="email" placeholder="name@company.com" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">City</label>
                                    <input type="text" class="form-control" name="city" placeholder="Business Location" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Requested Service</label>
                                <select class="form-select" name="service" required>
                                    <option value="" selected disabled>Select the area you need help with...</option>
                                    <option value="Income Tax Return">Income Tax Filling</option>
                                    <option value="GST Registration">GST Registration & Compliance</option>
                                    <option value="Company Incorporation">Register a New Company</option>
                                    <option value="Accounting Services">Outsourced Accounting</option>
                                    <option value="Payroll Management">Payroll setup</option>
                                    <option value="General Consultation">General Advisory</option>
                                </select>
                            </div>

                            <div class="mb-5 pb-2">
                                <label class="form-label">Additional Context (Optional)</label>
                                <textarea class="form-control" name="message" rows="4" placeholder="Tell us exactly what you're looking to solve..."></textarea>
                            </div>

                            <button type="submit" class="btn btn-indigo w-100 py-4 rounded-4 fs-5 d-flex align-items-center justify-content-center gap-3" style="font-weight: 700;">
                                Submit Request <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer-wrap">
        <div class="container px-4 px-lg-5 text-center">
            <h3 class="footer-brand mb-3 text-decoration-none">Tax<span>Safar</span></h3>
            <p class="fs-5 mb-5 pb-2" style="color: #9CA3AF;">Smarter accounting for serious businesses.</p>
            
            <div class="d-flex align-items-center justify-content-center gap-4 mb-5 pb-4">
                <a href="#" class="social-link"><i class="fa-brands fa-linkedin-in fs-5"></i></a>
                <a href="#" class="social-link"><i class="fa-brands fa-twitter fs-5"></i></a>
                <a href="#" class="social-link"><i class="fa-brands fa-instagram fs-5"></i></a>
            </div>
            
            <div class="border-top pt-5" style="border-color: rgba(255,255,255,0.1) !important;">
                <p class="small m-0 fw-semibold" style="color: #6B7280; letter-spacing: 0.05em;">&copy; <?php echo date('Y'); ?> TaxSafar Financial Services. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
