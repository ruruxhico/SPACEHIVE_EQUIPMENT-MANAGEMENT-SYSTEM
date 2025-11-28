<div class="container mt-4">
    <div class="card shadow col-md-6 mx-auto">
        <div class="card-header bg-info text-white">
            <h4 class="mb-0">Equipment Details</h4>
        </div>
        <div class="card-body">
            <h1 class="text-center text-primary mb-4"><?= esc($item['property_tag']) ?></h1>
            
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <strong>Type:</strong> <?= esc($item['type_name']) ?>
                </li>
                <li class="list-group-item">
                    <strong>Current Status:</strong> 
                    <span class="badge badge-secondary"><?= esc($item['status']) ?></span>
                </li>
                <li class="list-group-item">
                    <strong>Remarks:</strong><br>
                    <?= esc($item['remarks']) ?: '<span class="text-muted font-italic">No remarks</span>' ?>
                </li>
            </ul>

            <div class="mt-4 text-center">
                <a href="<?= base_url('equipment') ?>" class="btn btn-secondary">Back to List</a>
                <a href="<?= base_url('equipment/edit/' . $item['property_tag']) ?>" class="btn btn-warning">Edit</a>
            </div>
        </div>
    </div>
</div>