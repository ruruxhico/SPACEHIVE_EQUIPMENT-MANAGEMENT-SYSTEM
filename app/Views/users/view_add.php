<div class="container mt-5">
    <div class="card shadow col-md-8 mx-auto border-0">
        <div class="card-header bg-warning text-white">
            <h4 class="mb-0"><i class="fas fa-user-plus"></i> Add New User</h4>
        </div>
        
        <div class="card-body p-4">
            
            <!-- Error Messages -->
            <?php if (session()->has('errors')): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach (session('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach ?>
                    </ul>
                </div>
            <?php endif ?>

            <?php if (session()->has('error')): ?>
                <div class="alert alert-danger text-center"><?= session('error') ?></div>
            <?php endif ?>

            <form action="<?= base_url('users/insert') ?>" method="post">
                <?= csrf_field() ?>

                <!-- 1. PERSONAL INFORMATION -->
                <h6 class="text-muted font-weight-bold text-uppercase mb-3">Personal Information</h6>
                
                <div class="row">
                    <div class="col-md-4 form-group mb-3">
                        <label for="first_name" class="form-label font-weight-bold">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" id="first_name" class="form-control" style="text-transform: uppercase" value="<?= old('first_name') ?>" required>
                    </div>
                    <div class="col-md-4 form-group mb-3">
                        <label for="middle_name" class="form-label font-weight-bold">Middle Name</label>
                        <input type="text" name="middle_name" id="middle_name" class="form-control" style="text-transform: uppercase" value="<?= old('middle_name') ?>">
                    </div>
                    <div class="col-md-4 form-group mb-3">
                        <label for="last_name" class="form-label font-weight-bold">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" id="last_name" class="form-control" style="text-transform: uppercase" value="<?= old('last_name') ?>" required>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="email" class="form-label font-weight-bold">Email Address <span class="text-danger">*</span></label>
                    <input type="email" name="email" id="email" class="form-control" value="<?= old('email') ?>" required>
                </div>

                <hr>

                <!-- 2. ACCOUNT ROLE & ID -->
                <h6 class="text-muted font-weight-bold text-uppercase mb-3">Account Details</h6>

                <div class="form-group mb-3">
                    <label for="role" class="form-label font-weight-bold">Role <span class="text-danger">*</span></label>
                    <select name="role" id="role" class="form-control" required>
                        <option value="" disabled selected>Select Role...</option>
                        <option value="ITSO" <?= old('role') == 'ITSO' ? 'selected' : '' ?>>ITSO (Admin)</option>
                        <option value="ASSOCIATE" <?= old('role') == 'ASSOCIATE' ? 'selected' : '' ?>>ASSOCIATE (Faculty/Staff)</option>
                        <option value="STUDENT" <?= old('role') == 'STUDENT' ? 'selected' : '' ?>>STUDENT</option>
                    </select>
                </div>

                <!-- DYNAMIC ID FIELD -->
                <!-- Note: We use a single input name 'school_id' for simplicity in the controller, 
                     but we change the label dynamically via JS -->
                <div class="form-group mb-4" id="id-container" style="display:none;">
                    <label for="school_id" class="form-label font-weight-bold" id="id-label">School ID / Employee ID <span class="text-danger">*</span></label>
                    <input type="text" name="school_id" id="school_id" class="form-control" value="<?= old('school_id') ?>" placeholder="Enter ID Number">
                </div>

                <hr>

                <!-- 3. PASSWORD -->
                <h6 class="text-muted font-weight-bold text-uppercase mb-3">Security</h6>

                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label for="password" class="form-label font-weight-bold">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label for="confirmpassword" class="form-label font-weight-bold">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" name="confirmpassword" id="confirmpassword" class="form-control" required>
                    </div>
                </div>

                <!-- BUTTONS -->
                <div class="d-flex justify-content-between mt-4">
                    <a href="<?= base_url('users') ?>" class="btn btn-secondary shadow-sm">Cancel</a>
                    <button type="submit" class="btn btn-success px-5 shadow-sm font-weight-bold">Create User</button>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- DYNAMIC SCRIPT -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        const idContainer = document.getElementById('id-container');
        const idLabel = document.getElementById('id-label');
        const idInput = document.getElementById('school_id');

        function updateIdField() {
            const role = roleSelect.value;
            
            if (role === 'ITSO' || role === 'ASSOCIATE' || role === 'STUDENT') {
                idContainer.style.display = 'block';
                idInput.required = true;

                if (role === 'STUDENT') {
                    idLabel.innerHTML = 'Student Number <span class="text-danger">*</span>';
                    idInput.placeholder = 'e.g. 2023-10059';
                } else if (role === 'ASSOCIATE') {
                    idLabel.innerHTML = 'Associate Key / Employee ID <span class="text-danger">*</span>';
                    idInput.placeholder = 'e.g. EMP-2025';
                } else {
                    idLabel.innerHTML = 'Employee ID <span class="text-danger">*</span>';
                    idInput.placeholder = 'e.g. ADM-001';
                }
            } else {
                idContainer.style.display = 'none';
                idInput.required = false;
            }
        }

        // Run on change
        roleSelect.addEventListener('change', updateIdField);

        // Run on load (in case of validation error redirect)
        if(roleSelect.value) {
            updateIdField();
        }
    });
</script>