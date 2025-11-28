<div class="container mt-5">
    <div class="card shadow col-md-6 mx-auto border-0">
        <div class="card-body p-4">
            
            <h4 class="text-center text-warning font-weight-bold mb-4">Edit Equipment</h4>

            <form action="<?= base_url('equipment/update/' . $item['property_tag']) ?>" method="post" enctype="multipart/form-data">
                
                <div class="form-group text-center mb-4">
                    <label class="font-weight-bold d-block">Equipment Image</label>
                    
                    <?php 
                        $currentTypeImg = 'default.png';
                        foreach($types as $t) {
                            if($t['type_id'] == $item['type_id']) {
                                $currentTypeImg = $t['image'] ?: 'default.png';
                            }
                        }
                        $imgUrl = base_url('uploads/equipment/' . $currentTypeImg);
                    ?>
                    
                    <div class="mb-2">
                        <img id="imgPreview" src="<?= $imgUrl ?>" style="width: 100px; height: 100px; object-fit: cover; border-radius: 10px; border: 1px solid #ddd;">
                    </div>

                    <div class="custom-file w-75">
                        <input type="file" name="image" class="custom-file-input" id="customFile" accept="image/*" onchange="previewImage(event)">
                        <label class="custom-file-label text-left" for="customFile">Change Image...</label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Name</label>
                    <select name="type_id" id="typeSelect" class="form-control form-control-lg">
                        <?php foreach ($types as $type): ?>
                            <option value="<?= $type['type_id'] ?>" 
                                    data-accessories="<?= esc($type['accessory_list']) ?>"
                                    <?= $type['type_id'] == $item['type_id'] ? 'selected' : '' ?>>
                                <?= esc($type['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Current ID</label>
                    <input type="text" class="form-control bg-light" value="<?= esc($item['property_tag']) ?>" readonly>
                    <small class="text-danger">If you change the Name, a NEW ID will be generated automatically.</small>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Accessories</label>
                    <?php 
                        $currentAccessory = 'None';
                        foreach($types as $t) {
                            if($t['type_id'] == $item['type_id']) {
                                $currentAccessory = $t['accessory_list'];
                            }
                        }
                    ?>
                    <input type="text" id="accessoryDisplay" class="form-control bg-light" value="<?= esc($currentAccessory) ?>" readonly>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Current Status</label>
                    <select name="status" class="form-control form-control-lg">
                        <option value="Available" <?= $item['status'] == 'Available' ? 'selected' : '' ?>>Available</option>
                        <option value="Borrowed" <?= $item['status'] == 'Borrowed' ? 'selected' : '' ?>>Borrowed</option>
                        <option value="Unusable" <?= $item['status'] == 'Unusable' ? 'selected' : '' ?>>Unusable</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Remarks</label>
                    <textarea name="remarks" class="form-control" rows="3"><?= esc($item['remarks']) ?></textarea>
                </div>

                <div class="text-center mt-5">
                    <button type="submit" class="btn btn-warning btn-lg px-5 shadow-sm mb-3 font-weight-bold text-dark" style="border-radius: 50px;">
                        UPDATE EQUIPMENT
                    </button>
                    <br>
                    <a href="<?= base_url('equipment') ?>" class="text-secondary font-weight-bold" style="text-decoration: none;">
                        <i class="fas fa-arrow-left"></i> Go Back
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){ document.getElementById('imgPreview').src = reader.result; };
        reader.readAsDataURL(event.target.files[0]);
        event.target.nextElementSibling.innerText = event.target.files[0].name;
    }

    document.getElementById('typeSelect').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        var accessories = selectedOption.getAttribute('data-accessories');
        document.getElementById('accessoryDisplay').value = accessories;
    });
</script>