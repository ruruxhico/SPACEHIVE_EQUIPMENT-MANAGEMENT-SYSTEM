<main class="landing-page d-flex justify-content-center align-items-center text-center text-dark">

    <div class="main">
        <h1 class="display-4 fw-bold">WELCOME, iTAM!</h1>
        <p class="lead fst-italic">"Already registered?" <a href="<?= base_url('auth/login') ?>" class="">Login</a></p>
        
        <?= view('auth/view_signup') ?>
        
    </div>
</main>
