<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class UserController extends ResourceController
{
    protected $format = 'json';

    public function index()
    {
        $UserModel = new \App\Models\UserModel();
        $data = $UserModel->findAll();

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

    public function delete($id=null)
    {
        $UserModel = new \App\Models\UserModel();
        $UserModel->delete($id);
    
        return $this->respondDeleted([
            'status' => 'success',
            'message' => 'Exhibition deleted successfully',
        ]);
    }

    public function getUserById($userId)
    {
        // Assuming you have a model named UserModel
        $userModel = new \App\Models\UserModel();

        // Fetch user data by ID from the model
        $user = $userModel->find($userId);

        if ($user) {
            // Respond with JSON data
            return $this->respond($user, 200);
        } else {
            // Respond with a not found status
            return $this->failNotFound('User not found');
        }
    }
}
