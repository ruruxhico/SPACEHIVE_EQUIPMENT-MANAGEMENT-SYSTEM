<div class="container mt-5">
    
    <!-- 1. PROFILE CARD -->
    <div class="row mb-5">
        <div class="col-md-8 mx-auto">
            <div class="card shadow border-0">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        
                        <!-- Avatar / Icon -->
                        <div class="flex-shrink-0 text-center">
                            <img src="<?= base_url('uploads/default.png') ?>" alt="Profile" class="rounded-circle border mb-2" width="200" height="100" style="object-fit: cover;">
                            <br>
                            <?php 
                                $statusBg = ($user['status'] == 'Active') ? 'bg-success' : 'bg-secondary';
                            ?>
                            <span class="badge <?= $statusBg ?> px-3"><?= esc($user['status']) ?></span>
                        </div>
                        
                        <!-- User Details -->
                        <div class="flex-grow-1 ms-4 pl-4">
                            <h2 class="fw-bold text-primary mb-0">
                                <?= esc($user['last_name']) ?>, <?= esc($user['first_name']) ?> <?= esc($user['middle_name']) ?>
                            </h2>
                            
                            <hr class="my-2">
                            
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <small class="text-uppercase text-muted fw-bold">School ID</small>
                                    <div class="h5"><?= esc($user['school_id']) ?></div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <small class="text-uppercase text-muted fw-bold">Role</small>
                                    <div class="h5">
                                        <?php 
                                            $roleBadge = 'bg-info text-dark';
                                            if($user['role'] == 'ITSO') $roleBadge = 'bg-danger text-white';
                                            elseif($user['role'] == 'ASSOCIATE') $roleBadge = 'bg-warning text-dark';
                                        ?>
                                        <span class="badge <?= $roleBadge ?>"><?= esc($user['role']) ?></span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <small class="text-uppercase text-muted fw-bold">Email Address</small>
                                    <div class="h6"><?= esc($user['email']) ?></div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. BORROWING HISTORY -->
    <div class="row">
        <div class="col-12">
            <h4 class="text-light font-weight-bold mb-3 border-bottom pb-2">
                <i class="fas fa-history"></i> User Transaction History
            </h4>

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0 text-center align-middle">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>Item Name</th>
                                    <th>Property Tag</th>
                                    <th>Date Borrowed</th>
                                    <th>Date Returned</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($history)): ?>
                                    <?php foreach ($history as $record): ?>
                                        <tr>
                                            <td class="font-weight-bold text-primary">
                                                <?= esc($record['item_name']) ?>
                                            </td>
                                            <td><small class="text-muted"><?= esc($record['property_tag']) ?></small></td>
                                            <td>
                                                <?= date('M d, Y h:i A', strtotime($record['borrowed_at'])) ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    if ($record['returned_at']) {
                                                        echo date('M d, Y h:i A', strtotime($record['returned_at']));
                                                    } else {
                                                        echo '<span class="text-muted">-</span>';
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    $badge = 'secondary';
                                                    $text = $record['status'];
                                                    
                                                    if ($text == 'Ongoing') {
                                                        $badge = 'warning text-dark';
                                                        $text = 'Borrowed';
                                                    } elseif ($text == 'Returned') {
                                                        $badge = 'success';
                                                    } elseif ($text == 'Overdue') {
                                                        $badge = 'danger';
                                                    }
                                                ?>
                                                <span class="badge bg-<?= $badge ?> p-2"><?= $text ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="py-4 text-muted">
                                            <i class="fas fa-folder-open fa-2x d-block mb-2"></i>
                                            <br>No history found for this user.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. BUTTONS (Centered) -->
    <div class="text-center mt-5 mb-5">
        <!-- Edit Button -->
        <a href="<?= base_url('users/edit/' . $user['school_id']) ?>" class="btn btn-warning btn-lg px-5 shadow-sm font-weight-bold text-dark mb-3" style="border-radius: 50px;">
            <i class="fas fa-edit"></i> Edit User
        </a>
        
        <!-- Go Back Button -->
        <br>
        <a href="<?= base_url('users') ?>" class="text-secondary font-weight-bold" style="text-decoration: none;">
            <i class="fas fa-arrow-left"></i> Go Back
        </a>
    </div>

</div>