<!-- MODAL FOR DELETE CONFIRMATION -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirm Deactivate</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-dark">
                <p>Are you sure you want to deactivate user <strong id="deleteUser"></strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Yes, Deactivate</a>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt-4 bg-transparent">
    
    <!-- 1. CENTERED TITLE -->
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h1 class="fw-bold text-light"><?= esc($title) ?></h1>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success text-center"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <!-- FILTER FORM -->
    <form action="<?= base_url('users') ?>" method="get">
        
        <!-- 2. ADD BUTTON (LEFT) AND SEARCH (RIGHT) -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="<?= base_url('users/add') ?>" class="btn btn-primary shadow-sm">
                <span class="material-symbols-outlined align-middle" style="font-size: 18px;">person_add</span> Add New User
            </a>

            <div class="form-inline">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search users..." value="<?= esc($current_search) ?>">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- FILTER CARD (Transparent) -->
        <div class="card mb-3 bg-light border-0">
            <div class="card-body p-3">
                
                <!-- Rows Shown -->
                <div class="row mb-2">
                    <div class="col-md-2">
                        <label class="small font-weight-bold mb-1">Show Rows</label>
                        <select name="per_page" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="3" <?= $current_per_page == 3 ? 'selected' : '' ?>>3 Rows</option>
                            <option value="5" <?= $current_per_page == 5 ? 'selected' : '' ?>>5 Rows</option>
                            <option value="10" <?= $current_per_page == 10 ? 'selected' : '' ?>>10 Rows</option>
                        </select>
                    </div>
                </div>

                <!-- FILTERS ROW -->
                <div class="row">
                    
                    <!-- Sort Name -->
                    <div class="col-md-3 mb-2">
                        <label class="small font-weight-bold mb-1">Sort Name</label>
                        <select name="sort" class="form-control" onchange="this.form.submit()">
                            <option value="">Default (Newest)</option>
                            <option value="name_asc" <?= $current_sort == 'name_asc' ? 'selected' : '' ?>>A-Z (Last Name)</option>
                            <option value="name_desc" <?= $current_sort == 'name_desc' ? 'selected' : '' ?>>Z-A (Last Name)</option>
                        </select>
                    </div>

                    <!-- Role Filter -->
                    <div class="col-md-3 mb-2">
                        <label class="small font-weight-bold mb-1">Role</label>
                        <select name="role" class="form-control" onchange="this.form.submit()">
                            <option value="">All Roles</option>
                            <option value="ITSO" <?= $current_role == 'ITSO' ? 'selected' : '' ?>>ITSO (Admin)</option>
                            <option value="ASSOCIATE" <?= $current_role == 'ASSOCIATE' ? 'selected' : '' ?>>Associate</option>
                            <option value="STUDENT" <?= $current_role == 'STUDENT' ? 'selected' : '' ?>>Student</option>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div class="col-md-3 mb-2">
                        <label class="small font-weight-bold mb-1">Status</label>
                        <select name="status" class="form-control" onchange="this.form.submit()">
                            <option value="">All Statuses</option>
                            <option value="Active" class="text-success font-weight-bold" <?= $current_status == 'Active' ? 'selected' : '' ?>>&#9679; Active</option>
                            <option value="Inactive" class="text-secondary font-weight-bold" <?= $current_status == 'Inactive' ? 'selected' : '' ?>>&#9679; Inactive</option>
                        </select>
                    </div>

                    <!-- Reset Button -->
                    <div class="col-md-2 mb-2 d-flex align-items-end">
                        <a href="<?= base_url('users') ?>" class="btn btn-secondary btn-block" title="Reset Filters">
                            <i class="fas fa-undo"></i> Reset
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </form>

    <!-- MAIN TABLE -->
    <div class="card bg-transparent border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 bg-white align-middle text-center">
                    <thead class="table-dark text-white">
                        <tr>
                            <th>School ID</th>
                            <th>Name</th>
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
                                    <td class="font-weight-bold"><?= esc($user['school_id']); ?></td>
                                    <td><?= esc($user['last_name'] . ', ' . $user['first_name']); ?></td>
                                    <td>
                                        <?php 
                                            $roleBadge = 'bg-info text-dark';
                                            if($user['role'] == 'ITSO') $roleBadge = 'bg-danger text-white';
                                            elseif($user['role'] == 'ASSOCIATE') $roleBadge = 'bg-warning text-dark';
                                        ?>
                                        <span class="badge <?= $roleBadge ?>"><?= esc($user['role']); ?></span>
                                    </td>
                                    <td>
                                        <?php 
                                            $statusBg = ($user['status'] == 'Active') ? 'bg-success' : 'bg-secondary';
                                        ?>
                                        <span class="badge <?= $statusBg ?> p-2"><?= esc($user['status']); ?></span>
                                    </td>
                                    <td><?= esc($user['email']); ?></td>
                                    
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?= base_url('users/view/'.$user['school_id']) ?>" class="btn btn-secondary btn-sm" title="View">
                                                <span class="material-symbols-outlined" style="font-size: 18px;">person_search</span>
                                            </a>
                                            
                                            <a href="<?= base_url('users/edit/'.$user['school_id']) ?>" class="btn btn-warning btn-sm" title="Edit">
                                                <span class="material-symbols-outlined" style="font-size: 18px;">person_edit</span>
                                            </a>
                                            
                                            <button type="button" class="btn btn-danger btn-sm"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteUserModal"
                                                    data-id="<?= esc($user['school_id']); ?>" 
                                                    data-name="<?= esc($user['first_name'].' '.$user['last_name']); ?>">
                                                <span class="material-symbols-outlined" style="font-size: 18px;">person_remove</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="text-muted py-4">No users found matching your filters.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Pagination -->
        <div class="card-footer bg-transparent border-0 py-3">
            <div class="d-flex justify-content-center">
                <!-- Pass custom template if defined, otherwise default -->
                <?= $pager->links('default', 'default_full') ?> 
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var deleteModal = document.getElementById('deleteUserModal');
    // Using standard Bootstrap 5 event listener logic
    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var userId = button.getAttribute('data-id');
            var userName = button.getAttribute('data-name');
            var nameText = deleteModal.querySelector('#deleteUser');
            var confirmBtn = deleteModal.querySelector('#confirmDeleteBtn');

            nameText.textContent = userName;
            confirmBtn.href = "<?= base_url('users/delete/'); ?>" + userId;
        });
    }
});
</script>