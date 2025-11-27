<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteUserModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-dark">
                <p>
                    Are you sure you want to delete user <strong id="deleteUser"></strong>?
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Yes, Delete</a>
            </div>
        </div>
    </div>
</div>

<main  class="container py-5">
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger text-center"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success text-center"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <section class="container my-4">
        <div class="text-center mb-4">
            <h1 class="fw-bold text-light">Users</h1>
        </div>
        
        <!-- <div class="col col-md-10 mx-auto bg-white p-4 rounded shadow" style="max-width: 100%; width: 100%;">
            <a href="<?= base_url('users/add'); ?>" class="btn btn-primary mb-3">
                <span class="material-symbols-outlined align-middle">person_add</span> Add New User
            </a> -->

            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>First Name</th>
                            <th>Middle Name</th>
                            <th>Last Name</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>E-Mail</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users)): ?>
                            <?php foreach($users as $user): ?>
                                <tr>
                                    <td><?= esc($user['first_name']); ?></td>
                                    <td><?= esc($user['middle_name']); ?></td>
                                    <td><?= esc($user['last_name']); ?></td>
                                    <td><?= esc($user['role']); ?></td>
                                    <td><?= esc($user['status']); ?></td>
                                    <td><?= esc($user['email']); ?></td>
                                    <td>
                                        <a href="<?= base_url('users/view/'.$user['id']) ?>" class="btn btn-secondary btn-sm" title="View User">
                                            <span class="material-symbols-outlined">
                                                person_search
                                            </span>
                                        </a>
                                        <a href="<?= base_url('users/edit/'.$user['id']) ?>" class="btn btn-warning btn-sm" title="Edit User">
                                            <span class="material-symbols-outlined">
                                                person_edit
                                            </span>
                                        </a>
                                        <!-- <a href="<?= base_url('users/delete/'.$user['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete user?')">
                                            <span class="material-symbols-outlined">
                                                person_remove
                                            </span>
                                        </a> -->
                                        <button type="button" class="btn btn-danger btn-sm"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#deleteUserModal"
                                                            data-id="<?= esc($user['id']); ?>" 
                                                            data-name="<?= esc($user['firstname'].' '.$user['lastname']); ?>">
                                            <span class="material-symbols-outlined">
                                                person_remove
                                            </span>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="text-muted">No users found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <?= $pager->links(); ?>
            </div>
        <!-- </div> -->
    </section>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteModal = document.getElementById('deleteUserModal');
        deleteModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const userId = button.getAttribute('data-id');
            const userName = button.getAttribute('data-name');
            const confirmBtn = deleteModal.querySelector('#confirmDeleteBtn');
            const nameText = deleteModal.querySelector('#deleteUser');

            nameText.textContent = userName;
            confirmBtn.href = "<?= base_url('users/delete/'); ?>" + userId;
        });
    });
    </script>


</main>
