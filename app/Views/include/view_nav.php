<?php 
// Get the role from the session
$userRole = session()->get('role');
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-sm" style="background-color: #343a40 !important;">
    <div class="container-fluid">
        
        <a class="navbar-brand font-weight-bold" href="<?= base_url('dashboard'); ?>">
            <i class="fas fa-server"></i> ITSO Inventory
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                
                <!-- ============================ -->
                <!-- ADMIN / ITSO ONLY LINKS      -->
                <!-- ============================ -->
                <?php if ($userRole === 'ITSO'): ?>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?= base_url('users');?>">
                            <i class="fas fa-users-cog"></i> Manage Users
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?= base_url('equipment');?>">
                            <i class="fas fa-laptop"></i> Equipment Assets
                        </a>
                    </li>
                <?php endif; ?>

                <!-- ============================ -->
                <!-- ASSOCIATE & ITSO LINKS       -->
                <!-- ============================ -->
                <!-- This is the Reservation Link logic -->
                <?php if ($userRole === 'ITSO' || $userRole === 'ASSOCIATE'): ?>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?= base_url('transaction/reservation');?>">
                            <i class="fas fa-calendar-check"></i> Reservations
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?= base_url('transaction/borrow');?>">
                            <i class="fas fa-calendar-check"></i> Borrow
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?= base_url('transaction/returnList');?>">
                            <i class="fas fa-calendar-check"></i> Return
                        </a>
                    </li>
                <?php endif; ?>
                

            </ul>

            <!-- Right Side: User Info & Logout -->
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item me-3">
                    <!-- DEBUG BADGE: Shows your current role -->
                    <span class="badge bg-light text-dark">
                        Role: <?= esc($userRole) ?>
                    </span>
                </li>
                <li class="nav-item">
                    <a class="btn btn-warning btn-sm font-weight-bold text-dark" href="<?= base_url('logout');?>">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>