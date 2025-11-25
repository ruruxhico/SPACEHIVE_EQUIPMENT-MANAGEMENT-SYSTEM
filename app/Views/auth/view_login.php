<main class="container py-5">
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
    <div class="d-flex align-items-center justify-content-center" style="min-height: 80vh">
        <div class="col-12 col-md-5 mx-auto">
            <h2 class="mb-3 text-center">Login</h2>

            <div class="p-4 rounded shadow-sm bg-light text-dark">
                <form id="userForm" action="<?= base_url('auth/login') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="form-group mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required />
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required />
                    </div>
                    <div class="form-group mb-3">
                        <button type="submit" class="btn btn-success">Login</button>
                        <a href="<?= base_url('auth/signup') ?>" class="btn btn-warning">Sign Up</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
