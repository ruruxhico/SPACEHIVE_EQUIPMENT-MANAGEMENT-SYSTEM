<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Return an Equipment</h2>
            
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger" role="alert">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="row">
        <?php if (empty($transactions)): ?>
            <div class="col-12">
                <div class="alert alert-info" role="alert">
                    🎉 There are currently no equipment items marked as **Ongoing**.
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($transactions as $transaction): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">Item: **<?= esc($transaction['type_name']) ?>** (<?= esc($transaction['item_tag']) ?>)</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                ) ?>]
                                <img src="<?= base_url('uploads/equipment_images/' . esc($transaction['image'])) ?>" 
                                     alt="<?= esc($transaction['type_name']) ?>" 
                                     class="img-fluid rounded" style="max-height: 100px;">
                            </div>
                            
                            <ul class="list-group list-group-flush mb-3">
                                <li class="list-group-item">
                                    <strong>Id of borrower:</strong> <?= esc($transaction['school_id']) ?>
                                </li>
                                <li class="list-group-item">
                                    <strong>Name of borrower:</strong> <?= esc($transaction['first_name'] . ' ' . $transaction['last_name']) ?>
                                </li>
                                <li class="list-group-item">
                                    <strong>Accessory:</strong> <?= esc($transaction['accessories'] ?? 'None Listed') ?>
                                </li>
                                <li class="list-group-item">
                                    <strong>Date and Time Borrowed:</strong> <?= date('Y-m-d H:i A', strtotime($transaction['borrowed_at'])) ?>
                                </li>
                                </ul>
                            
                            <form action="<?= base_url('transaction/returnEquipment/' . $transaction['transaction_id']) ?>" method="post" 
                                  onsubmit="return confirmReturn(<?= esc($transaction['transaction_id']) ?>)">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="bi bi-check-circle-fill"></i> Return
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
    function confirmReturn(transactionId) {
        // Optional: Custom confirmation dialog before submitting
        if (confirm("Are you sure you want to mark this item as returned?")) {
            // Success message is handled by the Controller's redirect with session flash data
            return true;
        }
        return false;
    }

    // This block handles the automatic pop-up message (if needed outside of CI's flashdata)
    // However, using CI's flashdata is the standard and better practice.
    // If you need the exact "equipment is returned successfully" pop-up:
    <?php if (session()->getFlashdata('success') && strpos(session()->getFlashdata('success'), 'returned successfully') !== false): ?>
        alert("equipment is returned successfully");
    <?php endif; ?>
</script>