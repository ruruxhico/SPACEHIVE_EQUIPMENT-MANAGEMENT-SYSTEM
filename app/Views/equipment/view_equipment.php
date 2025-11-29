<div class="container-fluid mt-4">
    
    <!-- title -->
     <br><br>
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h1 class="fw-bold text-light"><?= esc($title) ?></h1>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <!-- filters -->
    <form action="<?= base_url('equipment') ?>" method="get">
        
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="<?= base_url('equipment/add') ?>" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus"></i> Add New Equipment
            </a>
            <div class="form-inline">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search ID or Name..." value="<?= esc($current_search) ?>">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3 bg-light border-0">
            <div class="card-body p-3">
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

                <div class="row">
                    <div class="col-md-3 mb-2">
                        <label class="small font-weight-bold mb-1">Equipment Name</label>
                        <select name="type" class="form-control" onchange="this.form.submit()">
                            <option value="">All Types</option>
                            <?php if(isset($types)): foreach ($types as $t): ?>
                                <option value="<?= $t['type_id'] ?>" <?= $current_type == $t['type_id'] ? 'selected' : '' ?>>
                                    <?= esc($t['name']) ?>
                                </option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>

                    <div class="col-md-2 mb-2">
                        <label class="small font-weight-bold mb-1">Sort Name</label>
                        <select name="sort" class="form-control" onchange="this.form.submit()">
                            <option value="">Default</option>
                            <option value="name_asc" <?= $current_sort == 'name_asc' ? 'selected' : '' ?>>A-Z</option>
                            <option value="name_desc" <?= $current_sort == 'name_desc' ? 'selected' : '' ?>>Z-A</option>
                        </select>
                    </div>

                    <div class="col-md-2 mb-2">
                        <label class="small font-weight-bold mb-1">Quantity</label>
                        <div class="input-group">
                            <input type="number" name="quantity" class="form-control" placeholder="0" value="<?= esc($current_qty) ?>">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="submit">Go</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-2">
                        <label class="small font-weight-bold mb-1">Status</label>
                        <select name="status" class="form-control" onchange="this.form.submit()">
                            <option value="">All Statuses</option>
                            <option value="Available" class="text-success font-weight-bold" <?= $current_status == 'Available' ? 'selected' : '' ?>>&#9679; Available</option>
                            <option value="Borrowed" class="text-danger font-weight-bold" <?= $current_status == 'Borrowed' ? 'selected' : '' ?>>&#9679; Borrowed</option>
                            <option value="Unusable" class="text-secondary font-weight-bold" <?= $current_status == 'Unusable' ? 'selected' : '' ?>>&#9679; Unusable</option>
                        </select>
                    </div>

                    <div class="col-md-2 mb-2 d-flex align-items-end">
                        <a href="<?= base_url('equipment') ?>" class="btn btn-secondary btn-block" title="Reset Filters"><i class="fas fa-undo"></i> Reset</a>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- table main -->
    <div class="card bg-transparent border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 bg-white">
                    <thead class="table-dark text-white">
                        <tr>
                            <th width="10%">Image</th>
                            <th>ID</th>
                            <th>Name</th>
                            <th width="20%">Accessory</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($equipment)): ?>
                            <?php foreach ($equipment as $item): ?>
                                <tr>
                                    <td class="text-center align-middle">
                                        <?php 
                                            $imgName = $item['image'] ? $item['image'] : 'default.png';
                                            $imgUrl  = base_url('uploads/equipment/' . $imgName);
                                        ?>
                                        <img src="<?= $imgUrl ?>" class="img-thumbnail rounded-circle" style="width: 50px; height: 50px; object-fit: cover;" onerror="this.src='<?= base_url('uploads/default.png') ?>'">
                                    </td>
                                    
                                    <td class="align-middle font-weight-bold text-primary"><?= esc($item['property_tag']) ?></td>
                                    <td class="align-middle"><?= esc($item['type_name']) ?></td>
                                    <td class="align-middle small">
                                        <?= !empty($item['accessories']) ? esc($item['accessories']) : '<span class="text-muted font-italic">None</span>' ?>
                                    </td>

                                    <td class="align-middle">
                                        <?php 
                                            $avail = $item['available_quantity'];
                                            $total = $item['total_quantity'];
                                            $percent = ($total > 0) ? ($avail / $total) * 100 : 0;
                                            $barColor = ($percent < 20) ? 'danger' : 'success';
                                        ?>
                                        <div class="d-flex align-items-center">
                                            <span class="mr-2 font-weight-bold"><?= $avail ?> / <?= $total ?></span>
                                            <div class="progress flex-grow-1" style="height: 6px; width: 60px;">
                                                <div class="progress-bar bg-<?= $barColor ?>" style="width: <?= $percent ?>%"></div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="align-middle">
                                        <?php 
                                            $bgClass = 'bg-secondary';
                                            if($item['status'] == 'Available') $bgClass = 'bg-success';
                                            elseif($item['status'] == 'Borrowed') $bgClass = 'bg-danger';
                                        ?>
                                        <span class="badge <?= $bgClass ?> p-2"><?= esc($item['status']) ?></span>
                                    </td>

                                    <td class="align-middle">
                                        <div class="btn-group">
                                            <a href="<?= base_url('equipment/view/' . $item['property_tag']) ?>" class="btn btn-sm btn-info" title="View"><i class="fas fa-eye"></i></a>
                                            <a href="<?= base_url('equipment/edit/' . $item['property_tag']) ?>" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-pen"></i></a>
                                            
                                            <?php if($item['status'] !== 'Unusable'): ?>
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger" 
                                                        onclick="openDeactivateModal('<?= esc($item['property_tag']) ?>')" 
                                                        title="Archive">
                                                    <i class="fas fa-archive"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="7" class="text-center py-4 text-muted">No records found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer py-3 bg-transparent border-0">
            <div class="d-flex justify-content-center">
                <?= $pager->links('default', 'default_full') ?> 
            </div>
        </div>
    </div>
</div>

<!-- deac modal-->

<div class="modal fade" id="deactivateModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-danger">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title font-weight-bold">
            <i class="fas fa-exclamation-triangle"></i> Deactivate Equipment
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        
        <p class="mb-3 lead">Are you sure you want to deactivate this equipment?</p>
        
        <div class="alert alert-warning">
            If yes, please re-type the equipment ID <br>
            <strong id="targetIdDisplay" class="h5"></strong>
        </div>

        <input type="text" id="confirmIdInput" class="form-control form-control-lg text-center" placeholder="Type ID here..." autocomplete="off">
        <small class="text-muted d-block mt-2">The button will enable when IDs match.</small>
      
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        
        <a href="#" id="confirmDeactivateBtn" class="btn btn-danger disabled" style="pointer-events: none;">
            Yes, Deactivate
        </a>
      </div>
    </div>
  </div>
</div>

<script>
    let currentTargetId = '';
    
    var myDeactivateModal;

    document.addEventListener('DOMContentLoaded', function() {
        var modalEl = document.getElementById('deactivateModal');
        if (modalEl) {
            myDeactivateModal = new bootstrap.Modal(modalEl);
        }
    });

    function openDeactivateModal(id) {
        currentTargetId = id;
        
        document.getElementById('targetIdDisplay').innerText = id;
        document.getElementById('confirmIdInput').value = '';
        
        let btn = document.getElementById('confirmDeactivateBtn');
        btn.classList.add('disabled');
        btn.style.pointerEvents = 'none';
        btn.href = "<?= base_url('equipment/deactivate/') ?>/" + id;

        if (myDeactivateModal) {
            myDeactivateModal.show();
        } else {
            new bootstrap.Modal(document.getElementById('deactivateModal')).show();
        }
    }

    document.getElementById('confirmIdInput').addEventListener('input', function() {
        let inputVal = this.value;
        let btn = document.getElementById('confirmDeactivateBtn');

        if (inputVal === currentTargetId) {
            btn.classList.remove('disabled');
            btn.style.pointerEvents = 'auto';
        } else {
            btn.classList.add('disabled');
            btn.style.pointerEvents = 'none';
        }
    });
</script>