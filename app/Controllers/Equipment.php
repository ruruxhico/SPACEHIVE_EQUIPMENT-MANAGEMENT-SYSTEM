<?php

namespace App\Controllers;

use App\Models\EquipmentAssetModel;
use App\Models\EquipmentTypeModel;

class Equipment extends BaseController
{
    public function index()
    {
        // SECURITY CHECK: Only ITSO can access this
        if (session()->get('role') !== 'ITSO') {
            return redirect()->to('dashboard')->with('error', 'Access Denied: You are not authorized to view Equipment.');
        }

        $assetModel = new EquipmentAssetModel();
        $typeModel  = new EquipmentTypeModel(); 
        
        // filter requests
        $search    = $this->request->getGet('search');
        $typeFilter= $this->request->getGet('type');
        $sort      = $this->request->getGet('sort');       
        $status    = $this->request->getGet('status');     
        $perPage   = $this->request->getGet('per_page') ?? 5; 
        $qtyFilter = $this->request->getGet('quantity');   

        $assetModel->select('
            equipment_assets.*, 
            equipment_types.name as type_name, 
            equipment_types.image, 
            equipment_types.available_quantity,
            equipment_types.total_quantity,
            (SELECT GROUP_CONCAT(accessory_name SEPARATOR ", ") 
             FROM type_accessories 
             WHERE type_accessories.type_id = equipment_assets.type_id) as accessories
        ', false);
        
        $assetModel->join('equipment_types', 'equipment_types.type_id = equipment_assets.type_id');

        
        if (!empty($typeFilter)) {
            $assetModel->where('equipment_assets.type_id', $typeFilter);
        }

        // search
        if (!empty($search)) {
            $assetModel->groupStart()
                ->like('equipment_assets.property_tag', $search)
                ->orLike('equipment_types.name', $search)
            ->groupEnd();
        }

        // status
        if (!empty($status)) {
            $assetModel->where('equipment_assets.status', $status);
        }

        // quantity
        if (!empty($qtyFilter)) {
            $assetModel->where('equipment_types.available_quantity <=', $qtyFilter);
        }

        // sorting
        if ($sort == 'name_asc') {
            $assetModel->orderBy('equipment_types.name', 'ASC');
        } elseif ($sort == 'name_desc') {
            $assetModel->orderBy('equipment_types.name', 'DESC');
        } elseif ($sort == 'qty_asc') {
            $assetModel->orderBy('equipment_types.available_quantity', 'ASC');
        } elseif ($sort == 'qty_desc') {
            $assetModel->orderBy('equipment_types.available_quantity', 'DESC');
        } else {
            $assetModel->orderBy('equipment_assets.property_tag', 'ASC');
        }

        $data = [
            'title'     => 'Equipment Inventory',
            'equipment' => $assetModel->paginate($perPage),
            'pager'     => $assetModel->pager,
            'types'     => $typeModel->orderBy('name', 'ASC')->findAll(), 
            
            'current_search'   => $search,
            'current_type'     => $typeFilter, 
            'current_sort'     => $sort,
            'current_status'   => $status,
            'current_per_page' => $perPage,
            'current_qty'      => $qtyFilter
        ];

        return view('include/view_head', $data)
            . view('include/view_nav')
            . view('equipment/view_equipment', $data);
    }

    public function add()
    {
        $typeModel = new EquipmentTypeModel();
        
        $types = $typeModel->findAll();
        $db = \Config\Database::connect();

        foreach ($types as &$type) {
            $query = $db->table('type_accessories')
                        ->select('GROUP_CONCAT(accessory_name SEPARATOR ", ") as accessory_name', false)
                        ->where('type_id', $type['type_id'])
                        ->get();
            
            $result = $query->getRow();
            $type['accessory_list'] = $result->accessory_name ?? 'None';
        }

        $data = [
            'title' => 'Add Equipment',
            'types' => $types
        ];

        return view('include/view_head', $data)
            . view('include/view_nav')
            . view('equipment/view_add', $data);
    }

    public function insert()
    {
        $assetModel = new EquipmentAssetModel();
        $typeModel  = new EquipmentTypeModel();
        
        $db = \Config\Database::connect();
        
        $typeId   = $this->request->getPost('type_id');
        $quantity = (int)$this->request->getPost('quantity'); 
        $remarks  = $this->request->getPost('remarks'); 
        
        if (!$this->validate(['type_id' => 'required', 'quantity' => 'required|greater_than[0]'])) {
             return redirect()->back()->withInput()->with('error', 'Please select a Name and valid Quantity.');
        }

        $typeInfo = $typeModel->find($typeId);

        $file = $this->request->getFile('image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            if (!is_dir(FCPATH . 'uploads/equipment')) {
                mkdir(FCPATH . 'uploads/equipment', 0777, true);
            }
            $file->move(FCPATH . 'uploads/equipment', $newName);
            
            // image update
            $db->table('equipment_types')->where('type_id', $typeId)->update(['image' => $newName]);
        }

        $createdTags = [];
        for ($i = 0; $i < $quantity; $i++) {
            $newTag = $assetModel->generateAssetId($typeInfo['type_code']);
            
            $data = [
                'property_tag' => $newTag,
                'type_id'      => $typeId,
                'status'       => 'Available',
                'remarks'      => $remarks
            ];

            $assetModel->insert($data);
            $createdTags[] = $newTag;
        }

        $newTotal     = $typeInfo['total_quantity'] + $quantity;
        $newAvailable = $typeInfo['available_quantity'] + $quantity;

        $db->table('equipment_types')->where('type_id', $typeId)->update([
            'total_quantity'     => $newTotal,
            'available_quantity' => $newAvailable
        ]);

        $msg = "Successfully added $quantity item(s).";
        return redirect()->to('equipment')->with('success', $msg);
    }

    public function view($id)
    {
        $assetModel = new EquipmentAssetModel();

        $item = $assetModel->select('
            equipment_assets.*, 
            equipment_types.name as type_name,
            equipment_types.image,
            equipment_types.description,
            equipment_types.available_quantity,
            equipment_types.total_quantity,
            (SELECT GROUP_CONCAT(accessory_name SEPARATOR ", ") 
             FROM type_accessories 
             WHERE type_accessories.type_id = equipment_assets.type_id) as accessories
        ', false)
        ->join('equipment_types', 'equipment_types.type_id = equipment_assets.type_id')
        ->where('property_tag', $id)
        ->first();

        $data = ['title' => 'View Item Details', 'item' => $item];

        return view('include/view_head', $data)
            . view('include/view_nav')
            . view('equipment/view_details', $data);
    }

    public function edit($id)
    {
        $assetModel = new EquipmentAssetModel();
        $typeModel  = new EquipmentTypeModel();
        $db = \Config\Database::connect();
        
        $item = $assetModel->find($id);

        $types = $typeModel->findAll();
        foreach ($types as &$type) {
            $query = $db->table('type_accessories')
                        ->select('GROUP_CONCAT(accessory_name SEPARATOR ", ") as accessory_name', false)
                        ->where('type_id', $type['type_id'])
                        ->get();
            $result = $query->getRow();
            $type['accessory_list'] = $result->accessory_name ?? 'None';
        }

        $data = [
            'title' => 'Edit Equipment',
            'item'  => $item,
            'types' => $types 
        ];

        return view('include/view_head', $data)
            . view('include/view_nav')
            . view('equipment/view_edit', $data);
    }

    public function update($id)
    {
        $assetModel = new EquipmentAssetModel();
        $typeModel  = new EquipmentTypeModel();
        $db = \Config\Database::connect();

        $newTypeId = $this->request->getPost('type_id');
        $status    = $this->request->getPost('status');
        $remarks   = $this->request->getPost('remarks');
        
        $originalItem = $assetModel->find($id);
        $oldTypeId    = $originalItem['type_id'];

        $updateData = [
            'status'  => $status,
            'remarks' => $remarks
        ];

        if ($newTypeId != $oldTypeId) {
            
            $newTypeInfo = $typeModel->find($newTypeId);
            $newTag      = $assetModel->generateAssetId($newTypeInfo['type_code']);
            
            $updateData['type_id']      = $newTypeId;
            $updateData['property_tag'] = $newTag; 

            $db->table('equipment_types')->where('type_id', $oldTypeId)
               ->set('total_quantity', 'total_quantity - 1', false)
               ->set('available_quantity', 'available_quantity - 1', false)
               ->update();

            $db->table('equipment_types')->where('type_id', $newTypeId)
               ->set('total_quantity', 'total_quantity + 1', false)
               ->set('available_quantity', 'available_quantity + 1', false)
               ->update();
               
            $successMsg = "Equipment converted to " . $newTypeInfo['name'] . ". New ID: " . $newTag;
        } else {
            $successMsg = "Updated details for " . $id;
        }

        $file = $this->request->getFile('image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            if (!is_dir(FCPATH . 'uploads/equipment')) {
                mkdir(FCPATH . 'uploads/equipment', 0777, true);
            }
            $file->move(FCPATH . 'uploads/equipment', $newName);
            
            $db->table('equipment_types')->where('type_id', $newTypeId)->update(['image' => $newName]);
        }

        $db->table('equipment_assets')->where('property_tag', $id)->update($updateData);

        return redirect()->to('equipment')->with('success', $successMsg);
    }
}