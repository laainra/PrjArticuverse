<?php

namespace App\Controllers;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;


use CodeIgniter\RESTful\ResourceController;

class UserController extends ResourceController
{
    protected $format = 'json';
    protected $request;

    public function __construct()
    {
        $this->request = service('request');
    }


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

    public function getUserById()
    {

        $userModel = new \App\Models\UserModel();
        $key = getenv('JWT_SECRET');
        $header = $this->request->getHeader("Authorization");
        $token = null;

        if(!empty($header)) {
            if (preg_match('/Bearer\s(\S+)/', $header, $matches)) {
                $token = $matches[1];
            }
        }

        try {
            // $decoded = JWT::decode($token, $key, array("HS256"));
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            $userId = $decoded->iss;


            $user = $userModel->find($userId);

            $data = [
                "id"=> $user->id,
                "name"=> $user->name,
                "username"=> $user->username,
                "email"=> $user->email,
                "description"=> $user->description,
                "role"=> $user->role,
                "avatar"=> $user->avatar,
                "created_at"=> $user->created_at,
            ];

            return $this->respond(['user' => $data]);



        } catch (Exception $ex) {
            $response = service('response');
            $response->setBody('Access denied');
            $response->setStatusCode(401);
            return $response;
        }

    }
    public function updateProfile()
    {
        $userModel = new \App\Models\UserModel();
        $key = getenv('JWT_SECRET');
        $header = $this->request->getHeader("Authorization");
        $token = null;
    
        // Extract data from the request
        $name = $this->request->getVar('name');
        $description = $this->request->getVar('description');
        $username = $this->request->getVar('username');
        $email = $this->request->getVar('email');
        $password = password_hash($this->request->getVar('password'), PASSWORD_DEFAULT);
        $avatar = $this->request->getFile('avatar');
    
        if (!empty($header)) {
            if (preg_match('/Bearer\s(\S+)/', $header, $matches)) {
                $token = $matches[1];
            }
        }
    
        try {
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            $userId = $decoded->iss;
    
            $user = $userModel->find($userId);
            if ($user) {
                // Handle file upload
                if ($avatar->isValid() && !$avatar->hasMoved()) {
                    $newName = $avatar->getRandomName();
                    $avatar->move('./path/to/upload/directory', $newName);
                    $data['avatar'] = $newName;
                }
    
                // Update user data
                $data['name'] = $name;
                $data['description'] = $description;
                $data['username'] = $username;
                $data['email'] = $email;
                $data['password'] = $password;
    
                $proses = $userModel->update($userId, $data);
    
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
        } catch (Exception $ex) {
            $response = service('response');
            $response->setBody('Access denied');
            $response->setStatusCode(401);
            return $response;
        }
    
        return $this->failNotFound('Data tidak ditemukan');
    }
    
}
