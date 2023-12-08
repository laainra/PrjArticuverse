<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class ExhibitionController extends ResourceController
{
    protected $format = 'json';

    public function index()
    {
        $ExhibitionModel = new \App\Models\ExhibitionModel();
        $data = $ExhibitionModel->findAll();

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
    public function deleteExhibition($id)
    {
        $ExhibitionModel = new \App\Models\ExhibitionModel();
        $ExhibitionModel->delete($id);
    
        return $this->respondDeleted([
            'status' => 'success',
            'message' => 'Exhibition deleted successfully',
        ]);
    }
    


    public function create()
    {
        $ExhibitionModel = new \App\Models\ExhibitionModel();
        $nama = $this->request->getVar('name');
        $description = $this->request->getVar('description');
        $location = $this->request->getVar('location');
        $poster = $this->request->getVar('poster');
        $start_date = $this->request->getVar('start_date');
        $end_date = $this->request->getVar('end_date');
    
        if (empty($nama) || empty($description) || empty($location) || empty($poster) || empty($start_date) || empty($end_date)) {
            $response = [
                'status' => 400,
                'message' => 'Bad Request - Missing required data',
            ];
        } else {
            $data = [
                'name' => $nama,
                'description' => $description,
                'location' => $location,
                'poster' => $poster,
                'start_date' => $start_date,
                'end_date' => $end_date,
            ];
    
            $ExhibitionModel->insert($data);
        }
    
        return $this->respondCreated([
            'status' => 'success',
            'message' => 'Exhibition created successfully',
            'data' => $data
        ]);
    }

    public function updateExhibition($id)
    {
        echo "ID: $id";
        $ExhibitionModel = new \App\Models\ExhibitionModel();
        $exhibition = $ExhibitionModel->find($id);

        if ($exhibition) {
            $data = [
                'name' => $this->request->getVar('name'),
                'description' => $this->request->getVar('description'),
                'location' => $this->request->getVar('location'),
                'poster' => $this->request->getVar('poster'),
                'start_date' => $this->request->getVar('start_date'),
                'end_date' => $this->request->getVar('end_date'),
    
            ];
    
            $proses = $ExhibitionModel->update($id, $data);
    
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
