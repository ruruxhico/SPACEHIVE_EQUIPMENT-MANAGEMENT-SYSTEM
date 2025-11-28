<?php 
// NOTE: Login check temporarily commented out for testing
// if (session()->get('isLoggedIn')): 
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-sm" style="background-color: #343a40 !important;">
    <div class="container-fluid">
        
        <!-- Brand Name -->
        <a class="navbar-brand font-weight-bold" href="<?= base_url('dashboard'); ?>">
            <i class="fas fa-server"></i> ITSO Inventory
        </a>
        
        <!-- Hamburger Button (Standard Bootstrap 5) -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu Links -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <!-- User Management -->
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?= base_url('users');?>">Manage Users</a>
                </li>
                
                <!-- Equipment Management -->
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?= base_url('equipment');?>">Equipment Assets</a>
                </li>
            </ul>

            <!-- Logout -->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="btn btn-warning btn-sm font-weight-bold text-dark" href="<?= base_url('logout');?>">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<?php // endif; ?>