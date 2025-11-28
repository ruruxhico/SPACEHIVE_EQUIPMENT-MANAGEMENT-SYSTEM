<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-dark">
                <p>Are you sure you want to delete user <strong id="deleteUser"></strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Yes, Delete</a>
            </div>
        </div>
    </div>
</div>

<main class="container py-5">
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger text-center"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success text-center"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <section class="container my-4">
        <div class="text-center mb-4">
            <h1 class="fw-bold text-light">User Management</h1>
        </div>
        
        <div class="d-flex justify-content-center mb-3">
            <form class="d-flex" action="<?= base_url('users') ?>" method="get">
                <input class="form-control me-2" type="search" name="search" placeholder="Search users..." value="<?= esc($search ?? '') ?>" style="width: 300px;">
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        <div class="card shadow border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle text-center mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>School ID</th> 
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
                                        <td><?= esc($user['school_id']); ?></td>
                                        <td><?= esc($user['first_name']); ?></td>
                                        <td><?= esc($user['middle_name']); ?></td>
                                        <td><?= esc($user['last_name']); ?></td>
                                        <td>
                                            <span class="badge bg-info text-dark"><?= esc($user['role']); ?></span>
                                        </td>
                                        <td>
                                            <?php $statusBg = ($user['status'] == 'Active') ? 'bg-success' : 'bg-secondary'; ?>
                                            <span class="badge <?= $statusBg ?>"><?= esc($user['status']); ?></span>
                                        </td>
                                        <td><?= esc($user['email']); ?></td>
                                        
                                        <td>
                                            <a href="<?= base_url('users/view/'.$user['school_id']) ?>" class="btn btn-secondary btn-sm" title="View">
                                                <span class="material-symbols-outlined">person_search</span>
                                            </a>
                                            
                                            <a href="<?= base_url('users/edit/'.$user['school_id']) ?>" class="btn btn-warning btn-sm" title="Edit">
                                                <span class="material-symbols-outlined">person_edit</span>
                                            </a>
                                            
                                            <button type="button" class="btn btn-danger btn-sm"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteUserModal"
                                                    data-id="<?= esc($user['school_id']); ?>" 
                                                    data-name="<?= esc($user['first_name'].' '.$user['last_name']); ?>">
                                                <span class="material-symbols-outlined">person_remove</span>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="8" class="text-muted py-4">No users found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Pagination -->
            <div class="card-footer bg-white border-0 py-3">
                <div class="d-flex justify-content-center">
                    <?= $pager->links() ?>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var deleteModal = document.getElementById('deleteUserModal');
    deleteModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        
        var userId = button.getAttribute('data-id');
        var userName = button.getAttribute('data-name');
        
        var modalTitle = deleteModal.querySelector('.modal-title');
        var nameText = deleteModal.querySelector('#deleteUser');
        var confirmBtn = deleteModal.querySelector('#confirmDeleteBtn');

        nameText.textContent = userName;
        confirmBtn.href = "<?= base_url('users/delete/'); ?>" + userId;
    });
});
</script>