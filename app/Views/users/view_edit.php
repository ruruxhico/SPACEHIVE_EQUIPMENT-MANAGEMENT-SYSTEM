<main>
    <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    
    <?php
        if(isset($errors)):
    ?>
            <div class="alert alert-danger">
                <p><?= implode('<br>', $errors); ?></p>
            </div>
    <?php
        endif;
    ?>

    <div class="col col-md-5 mx-auto">
        <form id="userForm" action="<?= base_url('users/update/'.$user['id']) ?>" method="post">
            <?= csrf_field() ?>
            <div class="form-group mb-2">
                <label for="firstname" class="form-label">First Name</label>
                <input type="text" name="firstname" id="firstname" class="form-control" style="text-transform: uppercase" value="<?= $user['firstname']; ?>">
            </div>
            <div class="form-group mb-2">
                <label for="middlename" class="form-label">Middle Name</label>
                <input type="text" name="middlename" id="middlename" class="form-control" style="text-transform: uppercase" value="<?= $user['middlename']; ?>"> 
            </div>
            <div class="form-group mb-2">
                <label for="lastname" class="form-label">Last Name</label>
                <input type="text" name="lastname" id="lastname" class="form-control" style="text-transform: uppercase" value="<?= $user['lastname']; ?>">
            </div>
            <div class="form-group mb-2">
                <label for="position" class="form-label">Role</label>
                <input type="text" name="position" id="position" class="form-control" style="text-transform: uppercase" value="<?= $user['position']; ?>">
            </div>
            <div class="form-group mb-2">
                <label for="position" class="form-label">Status</label>
                <input type="text" name="position" id="position" class="form-control" style="text-transform: uppercase" value="<?= $user['position']; ?>">
            </div>
            <div class="form-group mb-2">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="<?= $user['email']; ?>">
            </div>
            <div class="form-group mb-2">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control">
            </div>
            <div class="form-group mb-2">
                <label for="confirmpassword" class="form-label">Confirm Password</label>
                <input type="password" name="confirmpassword" id="confirmpassword" class="form-control">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-success">Save</button>
                <a href="<?= base_url('users'); ?>" class="btn btn-warning">Back</a>
            </div>
        </form>
    </div>
</main>