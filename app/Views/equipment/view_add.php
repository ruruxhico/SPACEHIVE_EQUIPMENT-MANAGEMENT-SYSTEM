<div class="container mt-5">
    <div class="card shadow col-md-6 mx-auto border-0">
        <div class="card-body p-4">
            
            <h4 class="text-center text-primary font-weight-bold mb-4">Add Equipment</h4>

            <?php if (session()->has('error')): ?>
                <div class="alert alert-danger text-center"><?= session('error') ?></div>
            <?php endif ?>

            <form action="<?= base_url('equipment/insert') ?>" method="post" enctype="multipart/form-data">
                
                <!-- 1. UPLOAD IMAGE -->
                <div class="form-group text-center mb-4">
                    <label class="font-weight-bold d-block">Upload Image</label>
                    
                    <!-- Image Preview Placeholder -->
                    <div class="mb-2">
                        <img id="imgPreview" src="<?= base_url('uploads/default.png') ?>" style="width: 100px; height: 100px; object-fit: cover; border-radius: 10px; border: 1px solid #ddd;">
                    </div>

                    <div class="custom-file w-75">
                        <input type="file" name="image" class="custom-file-input" id="customFile" accept="image/*" onchange="previewImage(event)">
                        <label class="custom-file-label text-left" for="customFile">Choose file</label>
                    </div>
                </div>

                <!-- 2. NAME (Type Selection) -->
                <div class="form-group">
                    <label class="font-weight-bold">Name</label>
                    <select name="type_id" id="typeSelect" class="form-control form-control-lg" required>
                        <option value="" disabled selected>Select Equipment Name...</option>
                        <?php foreach ($types as $type): ?>
                            <!-- We store the accessories in a data-attribute to use in JS -->
                            <option value="<?= $type['type_id'] ?>" data-accessories="<?= esc($type['accessory_list']) ?>">
                                <?= esc($type['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- 3. ACCESSORY (Read Only Display) -->
                <div class="form-group">
                    <label class="font-weight-bold">Accessories</label>
                    <input type="text" id="accessoryDisplay" class="form-control bg-light" placeholder="Select a name to see accessories..." readonly>
                    <small class="text-muted">These accessories are automatically included with this item type.</small>
                </div>

                <!-- 4. QUANTITY -->
                <div class="form-group">
                    <label class="font-weight-bold">Quantity</label>
                    <input type="number" name="quantity" class="form-control form-control-lg" placeholder="1" min="1" value="1" required>
                </div>

                <!-- BUTTONS (Centered & Stacked) -->
                <div class="text-center mt-5">
                    
                    <!-- Add Button -->
                    <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm mb-3 font-weight-bold" style="border-radius: 50px;">
                        ADD EQUIPMENT
                    </button>
                    
                    <!-- Go Back Button (block level under add) -->
                    <br>
                    <a href="<?= base_url('equipment') ?>" class="text-secondary font-weight-bold" style="text-decoration: none;">
                        <i class="fas fa-arrow-left"></i> Go Back
                    </a>

                </div>

            </form>
        </div>
    </div>
</div>

<!-- JAVASCRIPT LOGIC -->
<script>
    // 1. Update File Name and Preview Image
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('imgPreview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);

        // Update Label Text
        var fileName = event.target.files[0].name;
        var label = event.target.nextElementSibling;
        label.innerText = fileName;
    }

    // 2. Update Accessory Field based on Dropdown Selection
    document.getElementById('typeSelect').addEventListener('change', function() {
        // Get selected option
        var selectedOption = this.options[this.selectedIndex];
        
        // Get the data-accessories attribute
        var accessories = selectedOption.getAttribute('data-accessories');
        
        // Update input
        document.getElementById('accessoryDisplay').value = accessories;
    });
</script>