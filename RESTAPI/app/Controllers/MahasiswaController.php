<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class MahasiswaController extends ResourceController
{
    protected $format = 'json';

    public function index()
    {
        $mahasiswaModel = new \App\Models\MahasiswaModel();
        $data = $mahasiswaModel->findAll();

        if (!empty($data)) {
            $response = [
                'status' => 'success',
                'message' => 'Data retrieved successfully',
                'data' => $data
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'No data found',
                'data' => []
            ];
        }

        return $this->respond($response);
    }
}
