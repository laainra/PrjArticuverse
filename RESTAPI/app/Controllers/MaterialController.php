<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class MaterialController extends ResourceController
{
    protected $format = 'json';

    public function index()
    {
        $MaterialModel = new \App\Models\MaterialModel();
        $data = $MaterialModel->findAll();

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
