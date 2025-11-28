<div class="container mt-4">
    <div class="card shadow col-md-8 mx-auto">
        <div class="card-header bg-warning text-dark">
            <h4 class="mb-0">Edit Equipment: <?= esc($item['property_tag']) ?></h4>
        </div>
        <div class="card-body">
            
            <form action="<?= base_url('equipment/update/' . $item['property_tag']) ?>" method="post">
                
                <!-- ID Display (Read Only) -->
                <div class="form-group">
                    <label>Property Tag</label>
                    <input type="text" class="form-control" value="<?= esc($item['property_tag']) ?>" readonly disabled>
                </div>

                <div class="form-group">
                    <label for="status">Current Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="Available" <?= $item['status'] == 'Available' ? 'selected' : '' ?>>Available</option>
                        <option value="Borrowed" <?= $item['status'] == 'Borrowed' ? 'selected' : '' ?>>Borrowed</option>
                        <option value="Maintenance" <?= $item['status'] == 'Maintenance' ? 'selected' : '' ?>>Maintenance</option>
                        <option value="Unusable" <?= $item['status'] == 'Unusable' ? 'selected' : '' ?>>Unusable (Archived)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <textarea name="remarks" id="remarks" class="form-control" rows="3"><?= esc($item['remarks']) ?></textarea>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="<?= base_url('equipment') ?>" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success">Update Changes</button>
                </div>

            </form>
        </div>
    </div>
</div>