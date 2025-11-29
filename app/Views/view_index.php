<body class="bg-light">

    <!-- removed navbar -->

    <main class="d-flex justify-content-center align-items-center text-center text-dark" style="min-height: 100vh;">

        <div class="col-md-6">

            <h1 class="display-4 fw-bold mb-2">WELCOME, iTAM!</h1>
            <p class="lead text-muted mb-5">IT Services Office Equipment Management System</p>
            
            <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                
                <!-- Login Button -->
                <a href="<?= base_url('auth/login') ?>" class="btn btn-primary btn-lg px-5 shadow-sm">
                    Login
                </a>
                
                <!-- Sign Up Button (Safe way) -->
                <a href="<?= base_url('auth/signup') ?>" class="btn btn-outline-dark btn-lg px-5 shadow-sm">
                    Create Account
                </a>

            </div>
            
        </div>
    </main>

</body>