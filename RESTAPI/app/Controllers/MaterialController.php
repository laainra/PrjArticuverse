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
    public function deleteMaterial($id)
    {
        $MaterialModel = new \App\Models\MaterialModel();
        $MaterialModel->delete($id);

        return $this->respondDeleted([
            'status' => 'success',
            'message' => 'Exhibition deleted successfully',
        ]);
    }


    public function create()
    {
        $MaterialModel = new \App\Models\MaterialModel();

        $title = $this->request->getVar('title');
        $description = $this->request->getVar('description');
        $category = $this->request->getVar('category');
        $path = $this->request->getVar('path');

    
        if (empty($title) || empty($description) || empty($category) || empty($path) ) {
            $response = [
                'status' => 400,
                'message' => 'Bad Request - Missing required data',
            ];
        } else {
            $data = [
                'title' => $title,
                'description' => $description,
                'category' => $category,
                'path' => $path,
            ];
    
            $MaterialModel->insert($data);
        }
    
        return $this->respondCreated([
            'status' => 'success',
            'message' => 'Exhibition created successfully',
            'data' => $data
        ]);
    
    }
    

    public function updateMaterial($id)
    {
        echo "ID: $id";
        $MaterialModel = new \App\Models\MaterialModel();
        $material = $MaterialModel->find($id);


        if ($material ) {
            $data = [
                'title' => $this->request->getVar('title'),
                'description' => $this->request->getVar('description'),
                'category' => $this->request->getVar('category'),
                'path' => $this->request->getVar('path'),
    
            ];
    
            $proses = $MaterialModel->update($id, $data);
    
            if ($proses) {
                $response = [
                    'status' => 200,
                    'messages' => 'Data berhasil diubah',
                    'data' => $data,
                ];
            } else {
                $response = [
                    'status' => 402,
                    'messages' => 'Gagal diubah',
                ];
            }
    
            return $this->respond($response);
        }
    
        return $this->failNotFound('Data tidak ditemukan');
    }
}
