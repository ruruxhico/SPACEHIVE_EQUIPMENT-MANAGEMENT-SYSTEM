<div class="container mt-5">
    
    <!-- 1. ADMIN PROFILE SECTION (Consistent with others) -->
    <div class="row mb-5">
        <div class="col-md-8 mx-auto">
            <div class="card shadow border-0">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        
                        <!-- Profile Image -->
                        <div class="flex-shrink-0">
                            <img src="<?= base_url('uploads/default.png') ?>" alt="Profile" class="rounded-circle border" width="200" height="100" style="object-fit: cover;">
                        </div>
                        
                        <!-- Profile Info -->
                        <div class="flex-grow-1 ms-4 pl-4">
                            <h2 class="fw-bold text-primary mb-0">
                                <?= esc($admin['last_name']) ?>, <?= esc($admin['first_name']) ?>
                            </h2>
                            <p class="text-muted mb-1">
                                <?= esc($admin['school_id']) ?> | 
                                <span class="badge bg-danger text-white">SYSTEM ADMINISTRATOR</span>
                            </p>
                            <p class="small text-secondary mb-3"><?= esc($admin['email']) ?></p>
                            
                            <!-- Action Buttons -->
                            <div class="d-flex gap-2">
                                <a href="<?= base_url('users/edit/' . $admin['school_id']) ?>" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-user-edit"></i> Edit Profile
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. ADMINISTRATION WIDGETS (Centered) -->
    <div class="row mb-4">
        <div class="col-12 text-center mb-4">
            <h4 class="text-light font-weight-bold border-bottom pb-2 d-inline-block px-5">
                <i class="fas fa-cogs"></i> System Management
            </h4>
        </div>
    </div>

    <div class="row justify-content-center">
        
        <!-- User Management Widget -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 h-100 text-center hover-shadow transition">
                <div class="card-body p-5">
                    <div class="icon-box mb-3 text-primary">
                        <i class="fas fa-users-cog fa-4x"></i>
                    </div>
                    <h4 class="font-weight-bold">User Management</h4>
                    <p class="text-muted">Add, edit, or deactivate student and associate accounts.</p>
                    <a href="<?= base_url('users') ?>" class="btn btn-primary btn-block mt-3 stretched-link rounded-pill px-4">
                        Manage Users
                    </a>
                </div>
            </div>
        </div>

        <!-- Equipment Inventory Widget -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 h-100 text-center hover-shadow transition">
                <div class="card-body p-5">
                    <div class="icon-box mb-3 text-success">
                        <i class="fas fa-boxes fa-4x"></i>
                    </div>
                    <h4 class="font-weight-bold">Equipment Assets</h4>
                    <p class="text-muted">Track inventory, update items, and manage asset status.</p>
                    <a href="<?= base_url('equipment') ?>" class="btn btn-success btn-block mt-3 stretched-link rounded-pill px-4">
                        Manage Inventory
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    /* Slight hover effect for cards */
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
        transition: all 0.3s ease;
    }
    .transition {
        transition: all 0.3s ease;
    }
</style>