<div class="container mt-5">
    
    <!-- 1. ASSOCIATE PROFILE SECTION -->
    <div class="row mb-5">
        <div class="col-md-8 mx-auto">
            <div class="card shadow border-0">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        
                        <!-- Profile Image -->
                        <div class="flex-shrink-0">
                            <!-- We use a placeholder since image upload for users isn't built yet -->
                            <img src="<?= base_url('uploads/default.png') ?>" alt="Profile" class="rounded-circle border" width="200" height="100" style="object-fit: cover;">
                        </div>
                        
                        <!-- Profile Info -->
                        <div class="flex-grow-1 ms-4 pl-4">
                            <h2 class="fw-bold text-primary mb-0">
                                <?= esc($associate['last_name']) ?>, <?= esc($associate['first_name']) ?> <?= esc($associate['middle_name']) ?>
                            </h2>
                            <p class="text-muted mb-1">
                                <?= esc($associate['school_id']) ?> | 
                                <!-- Changed badge color to Warning (Yellow) for Associates to distinguish them -->
                                <span class="badge bg-warning text-dark"><?= esc($associate['role']) ?></span>
                            </p>
                            <p class="small text-secondary mb-3"><?= esc($associate['email']) ?></p>
                            
                            <!-- Action Buttons -->
                            <div class="d-flex gap-2">
                                <!-- Edit Profile -->
                                <a href="<?= base_url('users/edit/' . $associate['school_id']) ?>" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-user-edit"></i> Edit Profile
                                </a>

                                <!-- NEW: Reservation Button -->
                                <a href="#" class="btn btn-success btn-sm text-white">
                                    <i class="fas fa-calendar-plus"></i> My Reservations
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. BORROWING HISTORY SECTION -->
    <div class="row">
        <div class="col-12">
            <h4 class="text-secondary font-weight-bold mb-3 border-bottom pb-2">
                <i class="fas fa-history"></i> Borrowing History
            </h4>

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0 text-center align-middle">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>Item</th>
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
                                            <br>You have no borrowing history yet.
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

</div>