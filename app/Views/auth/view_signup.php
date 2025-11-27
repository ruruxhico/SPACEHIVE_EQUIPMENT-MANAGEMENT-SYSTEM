<main class="container mt-5">
    <!-- <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?> -->
    
    <?php
        if(isset($errors)):
    ?>
            <div class="alert alert-danger">
                <p><?= implode('<br>', $errors); ?></p>
            </div>
    <?php
        endif;
    ?>

    <div class="d-flex align-items-center justify-content-center" style="min-height: 80vh">
        <div class="col col-md-5 mx-auto">
            <h2 class="mb-3 text-center">Sign Up</h2>

            <div class="p-4 rounded shadow-sm bg-light text-dark">
                <form id="userForm" action="<?= base_url('auth/signup') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="form-group mb-3">
                        <label for="firstname" class="form-label">First Name</label>
                        <input type="text" name="firstname" id="firstname" class="form-control" style="text-transform: uppercase" value="<?= set_value('firstname'); ?>">
                    </div>
                    <div class="form-group mb-3">
                        <label for="middlename" class="form-label">Middle Name</label>
                        <input type="text" name="middlename" id="middlename" class="form-control" style="text-transform: uppercase" value="<?= set_value('middlename'); ?>">
                    </div>
                    <div class="form-group mb-3">
                        <label for="lastname" class="form-label">Last Name</label>
                        <input type="text" name="lastname" id="lastname" class="form-control" style="text-transform: uppercase" value="<?= set_value('lastname'); ?>">
                    </div>
                    <div class="form-group mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="<?= set_value('email'); ?>">
                    </div>
                    <div class="form-group mb-3">
                        <label for="position" class="form-label">Role</label>
                        <select name="position" id="position" class="form-control">
                            <option value="" disabled selected>Select role</option>
                            <option value="ASSOCIATE" <?= set_select('position', 'ASSOCIATE'); ?>>ASSOCIATE</option>
                            <option value="STUDENT" <?= set_select('position', 'STUDENT'); ?>>STUDENT</option>
                        </select>
                    </div>

                    <div id="associate-fields" style="display: none;">
                        <h5 class="mt-4">Associate Confirmation</h5>
                        <div class="form-group mb-3">
                            <label for="associate_key" class="form-label">Associate Key</label>
                            <input type="text" name="associate_key" id="associate_key" class="form-control" value="<?= old('associate_key'); ?>">
                        </div>
                    </div>

                    <div id="student-fields" style="display: none;">
                        <h5 class="mt-4">Student Confirmation</h5>
                        <div class="form-group mb-3">
                            <label for="student_number" class="form-label">Student Number</label>
                            <input type="text" name="student_number" id="student_number" class="form-control" value="<?= old('student_number'); ?>">
                        </div>
                    </div>

                    <div class="form-group mb-2">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label for="confirmpassword" class="form-label">Confirm Password</label>
                        <input type="password" name="confirmpassword" id="confirmpassword" class="form-control">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success">Sign Up</button>
                        <a href="<?= base_url('auth/login'); ?>" class="btn btn-warning">Back to Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const positionDropdown = document.getElementById('position');
            const associateFields = document.getElementById('associate-fields');
            const studentFields = document.getElementById('student-fields');

            positionDropdown.addEventListener('change', function() {
                // Hide all confirmation fields first
                associateFields.style.display = 'none';
                studentFields.style.display = 'none';

                // Check the selected value and show the relevant fields
                const selectedPosition = this.value;

                if (selectedPosition === 'ASSOCIATE') {
                    associateFields.style.display = 'block';
                } else if (selectedPosition === 'STUDENT') {
                    studentFields.style.display = 'block';
                }
            });

            // Trigger on page load in case of validation error (using old data)
            positionDropdown.dispatchEvent(new Event('change'));
        });
    </script>

</main>
