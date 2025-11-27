<?php if (session()->get('isLoggedIn')): ?>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= base_url('dashboard'); ?>">Lola Nena's Sisigan</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="<?= base_url('users');?>">Manage Users</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="<?= base_url('products');?>">Products</a>
            </li>
            <li class="nav-item">
                <a class="btn btn-warning btn-sm" href="<?= base_url('logout');?>">
                    <strong>Logout</strong>
                </a>
            </li>
        </ul>
        </div>
    </div>
</nav>
<?php endif; ?>