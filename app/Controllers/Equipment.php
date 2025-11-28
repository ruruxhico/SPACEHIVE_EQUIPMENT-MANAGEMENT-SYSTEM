<?php

namespace App\Controllers;

use App\Models\EquipmentAssetModel;
use App\Models\EquipmentTypeModel;

class Equipment extends BaseController
{
    public function index()
    {
        $assetModel = new EquipmentAssetModel();
        
        // --- FILTERS ---
        $search    = $this->request->getGet('search');
        $sort      = $this->request->getGet('sort');       
        $status    = $this->request->getGet('status');     
        $perPage   = $this->request->getGet('per_page') ?? 5; 
        $qtyFilter = $this->request->getGet('quantity');   

        // --- BUILD QUERY ---
        $assetModel->select('
            equipment_assets.*, 
            equipment_types.name as type_name, 
            equipment_types.image, 
            equipment_types.available_quantity,
            equipment_types.total_quantity,
            (SELECT GROUP_CONCAT(accessory_name SEPARATOR ", ") 
             FROM type_accessories 
             WHERE type_accessories.type_id = equipment_assets.type_id) as accessories
        ');
        
        $assetModel->join('equipment_types', 'equipment_types.type_id = equipment_assets.type_id');

        // --- APPLY FILTERS ---
        if (!empty($search)) {
            $assetModel->groupStart()
                ->like('equipment_assets.property_tag', $search)
                ->orLike('equipment_types.name', $search)
            ->groupEnd();
        }

        if (!empty($status)) {
            $assetModel->where('equipment_assets.status', $status);
        }

        if (!empty($qtyFilter)) {
            $assetModel->where('equipment_types.available_quantity <=', $qtyFilter);
        }

        // --- SORTING ---
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
            'current_search'   => $search,
            'current_sort'     => $sort,
            'current_status'   => $status,
            'current_per_page' => $perPage,
            'current_qty'      => $qtyFilter
        ];

        // ADDED NAVBAR HERE
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
            // Fetch accessories manually for the JS logic
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

        // ADDED NAVBAR HERE
        return view('include/view_head', $data)
            . view('include/view_nav')
            . view('equipment/view_add', $data);
    }

    public function insert()
    {
        $assetModel = new EquipmentAssetModel();
        $typeModel  = new EquipmentTypeModel();
        $validation = service('validation');

        $typeId   = $this->request->getPost('type_id');
        $quantity = (int)$this->request->getPost('quantity'); 
        
        if (!$this->validate(['type_id' => 'required', 'quantity' => 'required|greater_than[0]'])) {
             return redirect()->back()->withInput()->with('error', 'Please select a Name and valid Quantity.');
        }

        $typeInfo = $typeModel->find($typeId);

        // Image Handling
        $file = $this->request->getFile('image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            if (!is_dir(FCPATH . 'uploads/equipment')) {
                mkdir(FCPATH . 'uploads/equipment', 0777, true);
            }
            $file->move(FCPATH . 'uploads/equipment', $newName);
            $typeModel->update($typeId, ['image' => $newName]);
        }

        $createdTags = [];
        
        // Bulk Insert Loop
        for ($i = 0; $i < $quantity; $i++) {
            $newTag = $assetModel->generateAssetId($typeInfo['type_code']);
            $data = [
                'property_tag' => $newTag,
                'type_id'      => $typeId,
                'status'       => 'Available',
                'remarks'      => '' 
            ];
            $assetModel->insert($data);
            $createdTags[] = $newTag;
        }

        // Update Counts
        $typeModel->where('type_id', $typeId)->set('total_quantity', "total_quantity + $quantity", false)->update();
        $typeModel->where('type_id', $typeId)->set('available_quantity', "available_quantity + $quantity", false)->update();

        $msg = "Successfully added $quantity item(s).";
        return redirect()->to('equipment')->with('success', $msg);
    }

    public function view($id)
    {
        $assetModel = new EquipmentAssetModel();
        $item = $assetModel->select('equipment_assets.*, equipment_types.name as type_name')
                           ->join('equipment_types', 'equipment_types.type_id = equipment_assets.type_id')
                           ->where('property_tag', $id)
                           ->first();

        $data = ['title' => 'View Item', 'item' => $item];

        // ADDED NAVBAR HERE
        return view('include/view_head', $data)
            . view('include/view_nav')
            . view('equipment/view_details', $data);
    }

    public function edit($id)
    {
        $assetModel = new EquipmentAssetModel();
        $typeModel  = new EquipmentTypeModel();

        $data = [
            'title' => 'Edit Equipment',
            'item'  => $assetModel->find($id),
            'types' => $typeModel->findAll()
        ];

        // ADDED NAVBAR HERE
        return view('include/view_head', $data)
            . view('include/view_nav')
            . view('equipment/view_edit', $data);
    }

    public function update($id)
    {
        $assetModel = new EquipmentAssetModel();
        $data = [
            'status'  => $this->request->getPost('status'),
            'remarks' => $this->request->getPost('remarks')
        ];
        $assetModel->update($id, $data);
        return redirect()->to('equipment')->with('success', 'Updated ' . $id);
    }

    public function deactivate($id)
    {
        $assetModel = new EquipmentAssetModel();
        $assetModel->update($id, ['status' => 'Unusable']);
        return redirect()->to('equipment')->with('success', 'Archived ' . $id);
    }
}