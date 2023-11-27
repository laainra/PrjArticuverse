<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class ArtworkController extends ResourceController
{
    protected $format = 'json';

    public function index()
    {
        $ArtworkModel = new \App\Models\ArtworkModel();
        $data = $ArtworkModel->findAll();

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
