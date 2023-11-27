<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;

class Auth extends Controller
{
    use ResponseTrait;

    public function register()
    {
        $requestData = $this->request->getJSON();
        $validation = $this->validate([
            'name'     => 'required|min_length[3]|max_length[255]',
            'username' => 'required|min_length[3]|max_length[255]|is_unique[users.username]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
        ]);

        if (!$validation) {
            return $this->failValidationErrors();
        }

        $name     = $requestData->name;
        $username = $requestData->username;
        $email    = $requestData->email;
        $password = password_hash($requestData->password, PASSWORD_BCRYPT);

        $data = [
            'name'     => $name,
            'username' => $username,
            'email'    => $email,
            'password' => $password,
        ];

        $userModel = new UserModel();
        $userModel->insert($data);

        return $this->respondCreated([
            'code'   => 201,
            'status' => 'Registration Successful',
            'data'   => $data,
        ]);
    }

    protected function failValidationErrors()
    {
        return $this->fail([
            'code'   => 400,
            'status' => 'BAD REQUEST',
            'data'   => null,
        ]);
    }
}
