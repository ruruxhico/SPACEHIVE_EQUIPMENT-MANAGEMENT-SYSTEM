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
                        <input type="text" name="position" id="position" class="form-control" style="text-transform: uppercase" value="<?= set_value('position'); ?>">
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
</main>
