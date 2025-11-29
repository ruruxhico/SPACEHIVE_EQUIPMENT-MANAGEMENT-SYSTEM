<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\EquipmentTypeModel;
use App\Models\TransactionModel;
use CodeIgniter\Controller;

class Transaction extends Controller
{
    /**
     * Display the borrowing form.
     */
    public function borrow()
    {
        $userModel = new UserModel();
        $equipmentTypeModel = new EquipmentTypeModel();

        $data = [
            'users' => $userModel->select('school_id, first_name, last_name')->findAll(),
            'equipment_types' => $equipmentTypeModel->select('type_id, name, available_quantity')->findAll(),
            'title' => 'New Equipment Borrowing'
        ];

        // Load the header/layout if you have one
        // echo view('templates/header', $data);
        echo view('transaction/view_borrow', $data);
        // echo view('templates/footer');
    }

    /**
     * Handle the form submission for borrowing.
     */
    public function submitBorrow()
    {
        $request = service('request');

        // 1. Validation Rules
        if (!$this->validate([
            'borrower_id' => 'required|max_length[20]',
            'type_id' => 'required|integer',
            'quantity' => 'required|integer|greater_than[0]',
            'expected_return' => 'required|valid_date[Y-m-d H:i:s]'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $transactionModel = new TransactionModel();
        $equipmentTypeModel = new EquipmentTypeModel();

        $borrowerId = $request->getPost('borrower_id');
        $typeId = $request->getPost('type_id');
        $quantity = (int)$request->getPost('quantity');
        $expectedReturn = $request->getPost('expected_return');

        // 2. Check Available Quantity (Simplified logic)
        $equipmentType = $equipmentTypeModel->find($typeId);
        if (!$equipmentType || $equipmentType['available_quantity'] < $quantity) {
            return redirect()->back()->withInput()->with('error', 'The requested quantity is not available.');
        }

        // 3. Find an available equipment asset (based on type)
        // NOTE: This complex logic requires joining or a subquery to find an 'Available' property_tag
        $db = \Config\Database::connect();
        $query = $db->table('equipment_assets')
            ->where('type_id', $typeId)
            ->where('status', 'Available')
            ->limit(1)
            ->get();
        $asset = $query->getRowArray();

        if (!$asset) {
            return redirect()->back()->withInput()->with('error', 'No specific asset of that type is currently available.');
        }
        $itemTag = $asset['property_tag'];


        // 4. Save the Transaction
        $data = [
            'borrower_id' => $borrowerId,
            'item_tag' => $itemTag,
            // 'reservation_id' => null, // Assuming no reservation used for direct borrowing
            'expected_return' => $expectedReturn,
            // 'borrowed_at' is set by the database DEFAULT CURRENT_TIMESTAMP
            'status' => 'Ongoing',
            // 'issued_by' should be the ID of the logged-in ITSO/Associate user
            'issued_by' => session()->get('school_id') ?? 'ITSO_DEFAULT' 
        ];

        if ($transactionModel->insert($data)) {
            // 5. Update Asset Status and Quantity
            // Mark the specific asset as 'Borrowed'
            $db->table('equipment_assets')->where('property_tag', $itemTag)->update(['status' => 'Borrowed']);
            // Decrease the available quantity for the equipment type
            $equipmentTypeModel->update($typeId, ['available_quantity' => $equipmentType['available_quantity'] - $quantity]);

            // 6. Send Email Notification (Placeholder)
            $this->sendBorrowEmail($borrowerId, $itemTag); 

            return redirect()->to('transaction/list')->with('success', 'Equipment successfully borrowed and notification sent.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to record the transaction.');
        }
    }

    /**
     * Mock function to simulate email sending.
     */
    protected function sendBorrowEmail($borrowerId, $itemTag)
    {
        // In a real application, you would use CodeIgniter's Email library here.
        // $email = service('email');
        // $email->to($borrowerEmail);
        // $email->subject('Equipment Borrowed');
        // $email->message("You have successfully borrowed item {$itemTag}.");
        // $email->send();
        log_message('info', "Email sent to {$borrowerId} for borrowing {$itemTag}.");
    }

    public function returnList()
    {
        $transactionModel = new TransactionModel();
        
        // Fetch ongoing transactions with all necessary joins
        $ongoingTransactions = $transactionModel->getOngoingTransactions();

        $data = [
            'transactions' => $ongoingTransactions,
            'title' => 'Return an Equipment'
        ];

        echo view('transaction/view_return', $data);
    }
    
    // --- NEW METHOD: returnEquipment() ---
    /**
     * Process the return of an equipment item.
     */
    public function returnEquipment($transaction_id)
    {
        $transactionModel = new TransactionModel();
        $db = \Config\Database::connect();
        
        $transaction = $transactionModel->find($transaction_id);

        if (!$transaction || $transaction['status'] !== 'Ongoing') {
            return redirect()->back()->with('error', 'Invalid or already returned transaction.');
        }

        $itemTag = $transaction['item_tag'];
        $typeQuery = $db->table('equipment_assets')
                        ->select('type_id')
                        ->where('property_tag', $itemTag)
                        ->get();
        $typeData = $typeQuery->getRowArray();
        $typeId = $typeData['type_id'] ?? null;
        
        $db->transStart();
        
        // 1. Update Transaction Status
        $transactionModel->update($transaction_id, [
            'status' => 'Returned',
            'returned_at' => date('Y-m-d H:i:s'),
            'received_by' => session()->get('school_id') ?? 'ITSO_DEFAULT' 
        ]);

        // 2. Update Asset Status
        $db->table('equipment_assets')
           ->where('property_tag', $itemTag)
           ->update(['status' => 'Available']);

        // 3. Update Equipment Type Available Quantity (Increment by 1)
        if ($typeId) {
            $equipmentTypeModel = new EquipmentTypeModel();
            $equipmentTypeModel->set('available_quantity', 'available_quantity + 1', FALSE)
                               ->where('type_id', $typeId)
                               ->update();
        }

        if ($db->transStatus() === FALSE) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Failed to process return. Database error.');
        } else {
            $db->transComplete();
            return redirect()->to('transaction/returnList')->with('success', 'Equipment is returned successfully');
        }
    }
    
    // ... (sendBorrowEmail() method remains the same) ...
}


