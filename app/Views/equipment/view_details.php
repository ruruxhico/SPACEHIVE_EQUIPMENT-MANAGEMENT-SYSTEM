<div class="container mt-5">
    <div class="card shadow col-md-6 mx-auto border-0">
        <div class="card-header bg-white text-center border-0 pt-4">
            <?php 
                $imgName = $item['image'] ? $item['image'] : 'default.png';
                $imgUrl  = base_url('uploads/equipment/' . $imgName);
            ?>
            <img src="<?= $imgUrl ?>" class="img-fluid rounded shadow-sm" style="max-height: 200px; object-fit: cover;" onerror="this.src='<?= base_url('uploads/default.png') ?>'">
            
            <h3 class="mt-3 text-primary font-weight-bold"><?= esc($item['type_name']) ?></h3>
            <h5 class="text-muted"><?= esc($item['property_tag']) ?></h5>
            
            <?php 
                $bgClass = 'bg-secondary';
                if($item['status'] == 'Available') $bgClass = 'bg-success';
                elseif($item['status'] == 'Borrowed') $bgClass = 'bg-danger';
            ?>
            <span class="badge <?= $bgClass ?> px-3 py-2 mt-2"><?= esc($item['status']) ?></span>
        </div>

        <div class="card-body px-4">
            <hr>
            
            <div class="form-group mb-3">
                <label class="small font-weight-bold text-muted text-uppercase">Accessories</label>
                <div class="h6">
                    <?php if (!empty($item['accessories'])): ?>
                        <i class="fas fa-plug text-primary mr-2"></i> <?= esc($item['accessories']) ?>
                    <?php else: ?>
                        <span class="text-muted font-italic">None included</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group mb-3">
                <label class="small font-weight-bold text-muted text-uppercase">Stock Availability</label>
                <div class="h6">
                    <i class="fas fa-layer-group text-primary mr-2"></i>
                    <?= $item['available_quantity'] ?> available out of <?= $item['total_quantity'] ?>
                </div>
            </div>

            <div class="form-group mb-4">
                <label class="small font-weight-bold text-muted text-uppercase">Description / Remarks</label>
                <div class="p-3 bg-light rounded text-dark">
                    <?= !empty($item['remarks']) ? esc($item['remarks']) : '<span class="text-muted font-italic">No specific remarks for this unit.</span>' ?>
                    <hr class="my-2">
                    <small class="text-muted"><strong>Category Description:</strong> <?= esc($item['description']) ?></small>
                </div>
            </div>

            <div class="text-center mt-5 mb-3">
                
                <a href="<?= base_url('equipment/edit/' . $item['property_tag']) ?>" class="btn btn-warning btn-lg px-5 shadow-sm font-weight-bold text-dark mb-3" style="border-radius: 50px;">
                    <i class="fas fa-edit"></i> Edit Equipment
                </a>
                
                <br>
                <a href="<?= base_url('equipment') ?>" class="text-secondary font-weight-bold" style="text-decoration: none;">
                    <i class="fas fa-arrow-left"></i> Go Back
                </a>

            </div>
        </div>
    </div>
</div>