<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class GenreController extends ResourceController
{
    protected $format = 'json';

    public function index()
    {
        $GenreModel = new \App\Models\GenreModel();
        $data = $GenreModel->findAll();

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
