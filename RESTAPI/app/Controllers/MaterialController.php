<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class MaterialController extends ResourceController
{
    protected $format = 'json';

    // public function index()
    // {
    //     $MaterialModel = new \App\Models\MaterialModel();
    //     $data = $MaterialModel->findAll();

    //     if (!empty($data)) {
    //         $response = [
    //             'status' => 'success',
    //             'message' => 'Data retrieved successfully',
    //             'data' => $data
    //         ];
    //     } else {
    //         $response = [
    //             'status' => 'error',
    //             'message' => 'No data found',
    //             'data' => []
    //         ];
    //     }

    //     return $this->respond($response);
    // }

    public function index()
    {
        $materialModel = new \App\Models\MaterialModel();
        $materials = $materialModel
            ->select('materials.*, categories.name as category_name')
            ->join('categories', 'categories.id = materials.category', 'left') // Use left join
            ->findAll();


        $formattedData = [];

        if (!empty($materials)) {
            foreach ($materials as $material) {
                $formattedData[] = [
                    'id' => $material->id,
                    'title' => $material->title,
                    'description' => $material->description,
                    'path' => $material->path,
                    'category' => $material->category_name,
                    'created_at' => $material->created_at,
                    'updated_at' => $material->updated_at,
                ];
            }

            $response = [
                'status' => 'success',
                'message' => 'Data retrieved successfully',
                'data' => $formattedData
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
        $CategoryModel = new \App\Models\CategoryModel();

        $title = $this->request->getVar('title');
        $description = $this->request->getVar('description');
        $categoryName = $this->request->getVar('category');  // Assuming the category name is sent in the request
        $path = $this->request->getVar('path');

        if (empty($title) || empty($description) || empty($categoryName) || empty($path)) {
            $response = [
                'status' => 400,
                'message' => 'Bad Request - Missing required data',
            ];
        } else {
            // Check if the category exists, and get its ID
            $category = $CategoryModel->where('name', $categoryName)->first();
            $categoryId = $category ? $category['id'] : null;

            $data = [
                'title' => $title,
                'description' => $description,
                'category' => $categoryId,
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


    // public function updateMaterial($id)
    // {
    //     $MaterialModel = new \App\Models\MaterialModel();
    //     $material = $MaterialModel->find($id);

    //     if ($material) {
    //         // Retrieve data from the request using getVar
    //         $title = $this->request->getVar('title');
    //         $description = $this->request->getVar('description');
    //         $categoryName = $this->request->getVar('category');
    //         $path = $this->request->getVar('path');

    //         // Assuming the category name is sent in the request
    //         $CategoryModel = new \App\Models\CategoryModel();

    //         // Retrieve the category by name
    //         $category = $CategoryModel->where('name', $categoryName)->first();

    //         // If category found, get its id, otherwise set to null
    //         $categoryId = $category ? $category['id'] : null;

    //         // Prepare the data array
    //         $data = [
    //             'title' => $title,
    //             'description' => $description,
    //             'category' => $categoryId,
    //             'path' => $path,
    //         ];

    //         // Update the material
    //         $proses = $MaterialModel->update($id, $data);

    //         if ($proses) {
    //             $response = [
    //                 'status' => 200,
    //                 'messages' => 'Data berhasil diubah',
    //                 'data' => $data,
    //             ];
    //         } else {
    //             $response = [
    //                 'status' => 402,
    //                 'messages' => 'Gagal diubah',
    //             ];
    //         }

    //         return $this->respond($response);
    //     }

    //     return $this->failNotFound('Data tidak ditemukan');
    // }

    public function updateMaterial($id)
    {
        $MaterialModel = new \App\Models\MaterialModel();
        $material = $MaterialModel->find($id);

        if ($material) {
            // Retrieve JSON data from the request
            $jsonData = $this->request->getJSON();

            // Access individual values from JSON data
            $title = $jsonData->title ?? null;
            $description = $jsonData->description ?? null;
            $categoryName = $jsonData->category ?? null;
            $path = $jsonData->path ?? null;

            // Assuming the category name is sent in the request
            $CategoryModel = new \App\Models\CategoryModel();

            // Retrieve the category by name
            $category = $CategoryModel->where('name', $categoryName)->first();

            // If category found, get its id, otherwise set to null
            $categoryId = $category ? $category['id'] : null;

            // Prepare the data array
            $data = [
                'title' => $title,
                'description' => $description,
                'category' => $categoryId,
                'path' => $path,
            ];

            // Update the material
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


    public function show($id = null)
    {
        $MaterialModel = new \App\Models\MaterialModel();

        $material = $MaterialModel
            ->select('materials.*, categories.name as category_name')
            ->join('categories', 'categories.id = materials.category')
            ->find($id);

        $data =      [
            'id' => $material->id,
            'title' => $material->title,
            'description' => $material->description,
            'path' => $material->path,
            'category' => $material->category_name,
            'created_at' => $material->created_at,
            'updated_at' => $material->updated_at,
        ];


        if (!empty($material)) {
            $response = [
                'status' => 'success',
                'message' => 'Data retrieved successfully',
                'data' =>  $data
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
