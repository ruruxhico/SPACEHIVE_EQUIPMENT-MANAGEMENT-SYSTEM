<main class="container mt-5">
    
    <!-- ERROR MESSAGES -->
    <?php if(isset($errors)): ?>
        <div class="alert alert-danger">
            <p><?= implode('<br>', $errors); ?></p>
        </div>
    <?php endif; ?>

    <div class="d-flex align-items-center justify-content-center" style="min-height: 80vh">
        <div class="col col-md-6 mx-auto">
            <h2 class="mb-3 text-center">Edit User Details</h2>

            <div class="p-4 rounded shadow bg-white text-dark">
                
                <form id="userForm" action="<?= base_url('users/update/' . $user['school_id']) ?>" method="post">
                    
                    <!-- School ID (Read Only) -->
                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold">School ID / Employee ID</label>
                        <input type="text" class="form-control bg-light" value="<?= esc($user['school_id']); ?>" readonly>
                    </div>

                    <!-- Names (Editable by everyone) -->
                    <div class="form-group mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" name="first_name" id="first_name" class="form-control" 
                               style="text-transform: uppercase" 
                               value="<?= esc($user['first_name']); ?>" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="middle_name" class="form-label">Middle Name</label>
                        <input type="text" name="middle_name" id="middle_name" class="form-control" 
                               style="text-transform: uppercase" 
                               value="<?= esc($user['middle_name']); ?>">
                    </div>

                    <div class="form-group mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" name="last_name" id="last_name" class="form-control" 
                               style="text-transform: uppercase" 
                               value="<?= esc($user['last_name']); ?>" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" 
                               value="<?= esc($user['email']); ?>" required>
                    </div>

                    <hr>

                    <!-- SECURITY CHECK: Get Logged In Role -->
                    <?php $myRole = session()->get('role'); ?>

                    <!-- ROLE FIELD -->
                    <div class="form-group mb-3">
                        <label for="role" class="form-label font-weight-bold">Role</label>
                        
                        <?php if ($myRole === 'ITSO'): ?>
                            <!-- ADMIN VIEW: Show Dropdown -->
                            <select name="role" id="role" class="form-control">
                                <option value="ITSO" <?= $user['role'] == 'ITSO' ? 'selected' : '' ?>>ITSO (Admin)</option>
                                <option value="ASSOCIATE" <?= $user['role'] == 'ASSOCIATE' ? 'selected' : '' ?>>ASSOCIATE</option>
                                <option value="STUDENT" <?= $user['role'] == 'STUDENT' ? 'selected' : '' ?>>STUDENT</option>
                            </select>
                        <?php else: ?>
                            <!-- STUDENT VIEW: Read Only -->
                            <input type="text" class="form-control bg-light" value="<?= esc($user['role']) ?>" readonly>
                            <!-- Important: We do not send 'role' in POST so the controller ignores it -->
                        <?php endif; ?>
                    </div>

                    <!-- STATUS FIELD -->
                    <div class="form-group mb-3">
                        <label for="status" class="form-label font-weight-bold">Account Status</label>
                        
                        <?php if ($myRole === 'ITSO'): ?>
                            <!-- ADMIN VIEW: Show Dropdown -->
                            <select name="status" id="status" class="form-control">
                                <option value="Active" <?= $user['status'] == 'Active' ? 'selected' : '' ?>>Active</option>
                                <option value="Inactive" <?= $user['status'] == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        <?php else: ?>
                            <!-- STUDENT VIEW: Read Only -->
                            <input type="text" class="form-control bg-light" value="<?= esc($user['status']) ?>" readonly>
                        <?php endif; ?>
                    </div>

                    <hr>

                    <div class="form-group mb-3">
                        <label for="password" class="form-label">New Password <small class="text-muted">(Leave blank to keep current)</small></label>
                        <input type="password" name="password" id="password" class="form-control">
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="<?= base_url('dashboard') ?>" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-warning text-dark font-weight-bold">Update Changes</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</main>