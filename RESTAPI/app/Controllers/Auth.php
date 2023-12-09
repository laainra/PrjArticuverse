<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;

class Auth extends Controller
{
    use ResponseTrait;

    public function login()
    {
        $userModel = new UserModel();

        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        // Use the first() method to get the first row as an array
        $user = $userModel->where('email', $email)->first();

        if (is_null($user)) {
            return $this->respond(['error' => 'Invalid username or password.'], 401);
        }

        $pwd_verify = password_verify($password, $user->password);

        if (!$pwd_verify) {
            return $this->respond(['error' => 'Invalid username or password.'], 401);
        }

        $key = getenv('JWT_SECRET');
        $iat = time(); // current timestamp value
        $exp = $iat + 36000000;

        $payload = [
            "iss" => $user->id,
            "aud" => $user->username,
            "sub" => "Subject of the JWT",
            "iat" => $iat, //Time the JWT issued at
            "exp" => $exp, // Expiration time of token
            "email" => $user->email,
        ];

        $token = JWT::encode($payload, $key, 'HS256');
        $user_id = $user->id;
        $role = $user->role;
 
        $response = [
            'message' => 'Login Succesful',
            'token' => $token,
            'user_id' => $user_id,
            'role' => $role
        ];
         

        return $this->respond($response, 200);
    }


    public function register()
    {
        $rules = [
            'email' => ['rules' => 'required|min_length[1]|max_length[255]|valid_email|is_unique[users.email]'],
            'username' => ['rules' => 'required|min_length[1]|max_length[255]|is_unique[users.username]'],
            'name' => ['rules' => 'required|min_length[1]|max_length[255]'],
            'password' => ['rules' => 'required|min_length[8]|max_length[255]'],

        ];


        if ($this->validate($rules)) {
            $model = new UserModel();
            $data = [
                'email'    => $this->request->getVar('email'),
                'username'    => $this->request->getVar('username'),
                'name'    => $this->request->getVar('name'),
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)
            ];
            $model->save($data);

            return $this->respond(['message' => 'Registered Successfully'], 200);
        } else {
            $response = [
                'errors' => $this->validator->getErrors(),
                'message' => 'Invalid Inputs'
            ];
            return $this->fail($response, 409);
        }
        // $requestData = $this->request->getJSON();
        // $validation = $this->validate([
        //     'name'     => 'required|min_length[3]|max_length[255]',
        //     'username' => 'required|min_length[3]|max_length[255]|is_unique[users.username]',
        //     'email'    => 'required|valid_email|is_unique[users.email]',
        //     'password' => 'required|min_length[8]',
        // ]);

        // if (!$validation) {
        //     return $this->failValidationErrors();
        // }

        // $name     = $requestData->name;
        // $username = $requestData->username;
        // $email    = $requestData->email;
        // $password = password_hash($requestData->password, PASSWORD_BCRYPT);

        // $data = [
        //     'name'     => $name,
        //     'username' => $username,
        //     'email'    => $email,
        //     'password' => $password,
        // ];

        // $userModel = new UserModel();
        // $userModel->insert($data);

        // return $this->respondCreated([
        //     'code'   => 201,
        //     'status' => 'Registration Successful',
        //     'data'   => $data,
        // ]);
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
