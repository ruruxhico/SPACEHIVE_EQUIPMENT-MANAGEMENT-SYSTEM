<div class="container-fluid mt-4">
    
    <!-- 1. CENTERED TITLE -->
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="text-light font-weight-bold"><?= esc($title) ?></h2>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <!-- START OF FILTER FORM -->
    <form action="<?= base_url('equipment') ?>" method="get">
        
        <!-- 2. ADD BUTTON (LEFT) AND SEARCH (RIGHT) -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            
            <!-- Left: Add Button -->
            <a href="<?= base_url('equipment/add') ?>" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus"></i> Add New Equipment
            </a>

            <!-- Right: Search Bar -->
            <div class="form-inline">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search ID or Name..." value="<?= esc($current_search) ?>">
                    <div class="input-group-append">
                        <button class="btn btn-warning" type="submit">
                            <i class="fas fa-search">Submit</i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- FILTER CARD -->
        <div class="card shadow mb-3">
            <div class="card-body bg-light p-3">
                
                <!-- 3. ROWS SHOWN (Right above filters) -->
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

                <!-- 4. FILTERS (Right above table) -->
                <div class="row">
                    <!-- Sort Name -->
                    <div class="col-md-3 mb-2">
                        <label class="small font-weight-bold mb-1">Sort Name</label>
                        <select name="sort" class="form-control" onchange="this.form.submit()">
                            <option value="">Default</option>
                            <option value="name_asc" <?= $current_sort == 'name_asc' ? 'selected' : '' ?>>A-Z</option>
                            <option value="name_desc" <?= $current_sort == 'name_desc' ? 'selected' : '' ?>>Z-A</option>
                        </select>
                    </div>

                    <!-- Quantity Filter -->
                    <div class="col-md-3 mb-2">
                        <label class="small font-weight-bold mb-1">Quantity (Max 15)</label>
                        <div class="input-group">
                            <input type="number" name="quantity" class="form-control" placeholder="Enter a Number" max="15" value="<?= esc($current_qty) ?>">
                            <div class="input-group-append">
                                <!-- Small button to apply quantity if user doesn't press enter -->
                                <button class="btn btn-outline-secondary" type="submit">Go</button>
                            </div>
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div class="col-md-3 mb-2">
                        <label class="small font-weight-bold mb-1">Status</label>
                        <select name="status" class="form-control" onchange="this.form.submit()">
                            <option value="">All Statuses</option>
                            <option value="Available" <?= $current_status == 'Available' ? 'selected' : '' ?>>Available</option>
                            <option value="Borrowed" <?= $current_status == 'Borrowed' ? 'selected' : '' ?>>Borrowed</option>
                            <option value="Unusable" <?= $current_status == 'Unusable' ? 'selected' : '' ?>>Unusable</option>
                        </select>
                    </div>

                    <!-- Reset Button -->
                    <div class="col-md-1 mb-2 d-flex align-items-end">
                        <a href="<?= base_url('equipment') ?>" class="btn btn-secondary btn-block" title="Reset Filters"><i class="fas fa-undo">Reset Filters</i></a>
                    </div>
                </div>

            </div>
        </div>

    </form>

    <!-- MAIN TABLE --> 
    <div class="card shadow">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="bg-dark text-white">
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
                                    <!-- IMAGE -->
                                    <td class="text-center align-middle">
                                        <?php 
                                            $imgName = $item['image'] ? $item['image'] : 'default.png';
                                            $imgUrl  = base_url('uploads/equipment/' . $imgName);
                                        ?>
                                        <img src="<?= $imgUrl ?>" class="img-thumbnail rounded-circle" style="width: 50px; height: 50px; object-fit: cover;" onerror="this.src='<?= base_url('uploads/default.png') ?>'">
                                    </td>
                                    
                                    <!-- ID -->
                                    <td class="align-middle font-weight-bold text-primary"><?= esc($item['property_tag']) ?></td>
                                    
                                    <!-- NAME -->
                                    <td class="align-middle"><?= esc($item['type_name']) ?></td>
                                    
                                    <!-- ACCESSORIES -->
                                    <td class="align-middle small">
                                        <?= !empty($item['accessories']) ? esc($item['accessories']) : '<span class="text-muted font-italic">None</span>' ?>
                                    </td>

                                    <!-- QUANTITY -->
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

                                    <!-- STATUS -->
                                    <td class="align-middle">
                                        <?php 
                                            $badge = 'secondary';
                                            if($item['status'] == 'Available') $badge = 'success';
                                            elseif($item['status'] == 'Borrowed') $badge = 'warning';
                                            elseif($item['status'] == 'Unusable') $badge = 'danger';
                                        ?>
                                        <span class="badge badge-<?= $badge ?> p-2"><?= esc($item['status']) ?></span>
                                    </td>

                                    <!-- ACTIONS -->
                                    <td class="align-middle">
                                        <div class="btn-group">
                                            <a href="<?= base_url('equipment/view/' . $item['property_tag']) ?>" class="btn btn-sm btn-info" title="View"><i class="fas fa-eye"></i></a>
                                            <a href="<?= base_url('equipment/edit/' . $item['property_tag']) ?>" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-pen"></i></a>
                                            <?php if($item['status'] !== 'Unusable'): ?>
                                                <a href="<?= base_url('equipment/deactivate/' . $item['property_tag']) ?>" 
                                                   class="btn btn-sm btn-danger" 
                                                   onclick="return confirm('Are you sure you want to Archive this item?');" 
                                                   title="Archive">
                                                    <i class="fas fa-archive"></i>
                                                </a>
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
        <!-- PAGINATION LINKS -->
        <div class="card-footer py-3">
            <div class="d-flex justify-content-center">
                <!-- We pass the current GET parameters to the pager so links don't lose filters -->
                <?= $pager->links('default', 'default_full') ?> 
            </div>
        </div>
    </div>
</div>