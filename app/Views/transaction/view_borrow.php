<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 mx-auto">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white">
                    <h2 class="card-title mb-0">Borrowing Form</h2>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('transaction/submitBorrow') ?>" method="post" id="borrowingForm">

                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="borrower_id" class="form-label fw-bold">Select Borrower (Username/ID):</label>
                            <select class="form-select" id="borrower_id" name="borrower_id" required>
                                <option value="" disabled selected>Choose a user</option>
                                <?php if (isset($users) && is_array($users)): ?>
                                    <?php foreach ($users as $user): ?>
                                        <option value="<?= esc($user['school_id']) ?>">
                                            <?= esc($user['first_name'] . ' ' . $user['last_name'] . ' (' . $user['school_id'] . ')') ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="" disabled>No users available</option>
                                <?php endif; ?>
                            </select>
                            <?php if (session()->getFlashdata('errors.borrower_id')): ?>
                                <div class="text-danger mt-1">
                                    <?= session()->getFlashdata('errors.borrower_id') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="type_id" class="form-label fw-bold">Select Equipment Type:</label>
                            <select class="form-select" id="type_id" name="type_id" required>
                                <option value="" disabled selected>Choose an equipment type</option>
                                <?php if (isset($equipment_types) && is_array($equipment_types)): ?>
                                    <?php foreach ($equipment_types as $type): ?>
                                        <option value="<?= esc($type['type_id']) ?>">
                                            <?= esc($type['name'] . ' (Available: ' . $type['available_quantity'] . ')') ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="" disabled>No equipment types available</option>
                                <?php endif; ?>
                            </select>
                            <?php if (session()->getFlashdata('errors.type_id')): ?>
                                <div class="text-danger mt-1">
                                    <?= session()->getFlashdata('errors.type_id') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="accessories" class="form-label fw-bold">Required Accessories (Automatic):</label>
                            <div id="accessories_list" class="alert alert-info">
                                Select an equipment type above to view accessories.
                            </div>
                            <input type="hidden" name="accessories_data" id="accessories_data">
                        </div>

                        <div class="mb-3">
                            <label for="quantity" class="form-label fw-bold">Quantity:</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" min="1" value="1" required>
                            <div class="form-text text-muted">Currently, borrowing is simplified to one unit per form submission.</div>
                            <?php if (session()->getFlashdata('errors.quantity')): ?>
                                <div class="text-danger mt-1">
                                    <?= session()->getFlashdata('errors.quantity') ?>
                                </div>
                            <?php endif; ?>
                        </div>


                        <div class="d-flex justify-content-between pt-3">
                            <a href="<?= base_url('transaction') ?>" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-send-fill"></i> Submit Borrowing
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>