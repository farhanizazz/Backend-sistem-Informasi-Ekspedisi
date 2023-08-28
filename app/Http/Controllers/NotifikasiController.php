<?php

namespace App\Http\Controllers;

use App\Helpers\NotifikasiHelper;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    private $notifikasiHelper;
    public function __construct()
    {
        $this->notifikasiHelper = new NotifikasiHelper();
    }

    public function getReminderPajak(){
        $data = $this->notifikasiHelper->getReminderPajak();
        if ($data['status'] == 'success') {
            return response()->json([
                'status' => 'success',
                'data' => $data['data'],
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => $data['message'],
        ]);
    }
}
