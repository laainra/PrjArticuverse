<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use CodeIgniter\API\ResponseTrait;

class CommissionController extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        $commissionModel = new \App\Models\CommissionModel();
        $commissions = $commissionModel
            ->select('commissions.*, artworks.title as artwork_title, artworks.artist as artwork_artist, users.name as user_name, users.username as user_username')
            ->join('artworks', 'artworks.id = commissions.artwork_id', 'left')
            ->join('users', 'users.id = commissions.user_id', 'left')
            ->findAll();

        $formattedData = [];

        if (!empty($commissions)) {
            foreach ($commissions as $commission) {
                $formattedData[] = [
                    'id' => $commission->id,
                    'title' => $commission->artwork_title,
                    'amount' => $commission->amount,
                    'method' => $commission->method,
                    'status' => $commission->status,
                    'proof' => $commission->proof,
                    'artist' => $commission->artwork_artist,
                    'user_id' => $commission->user_id,
                    'artwork_id' => $commission->artwork_id,
                    'user_username' => $commission->user_username,
                    'user_name' => $commission->user_name,
                    'created_at' => $commission->created_at,
                    'updated_at' => $commission->updated_at,
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
    public function userCommission($userId)
    {
        $commissionModel = new \App\Models\CommissionModel();
    
        // Get total commission using Query Builder
        $totalCommissionResult = $commissionModel
            ->select('SUM(commissions.amount) as total_commission')
            ->where('commissions.user_id', $userId)
            ->get()
            ->getRow();
    
        $totalCommission = $totalCommissionResult ? $totalCommissionResult->total_commission : 0;
    
        // Get individual commissions
        $commissions = $commissionModel
            ->select('artworks.title as artwork_title', 'commissions.amount', 'users.name as user_name', 'users.username as user_username')
            ->join('artworks', 'artworks.id = commissions.artwork_id', 'left')
            ->join('users', 'users.id = commissions.user_id', 'left')
            ->where('commissions.user_id', $userId)
            ->get()
            ->getResult();
    

        if (!empty($commissions)) {

    
            $response = [
                'status' => 'success',
                'message' => 'Data retrieved successfully',
                'data' => $totalCommission,
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'No data found',
                'data' => [],
            ];
        }
    
        return $this->respond($response);
    }
    

    public function create()
    {
        $commissionModel = new \App\Models\CommissionModel();
        $artworkModel = new \App\Models\ArtworkModel();

        $amount = $this->request->getVar('amount');
        $method = $this->request->getVar('method');
        $proof = $this->request->getFile('proof');
        $artworkId = $this->request->getVar('artwork_id');
        $userId = $this->request->getVar('user_id');

        if (empty($amount) || empty($method)) {
            $response = [
                'status' => 400,
                'message' => 'Bad Request - Missing required data',
            ];

            return $this->respond($response, 400);
        }

        $newName = $proof->getRandomName();
            $proof->move('./path/to/upload/directory', $newName);

        $artwork = $artworkModel->find($artworkId);

        if ($artwork) {
            $data = [
                'amount' => $amount,
                'method' => $method,
                'proof' => $newName,
                'artwork_id' => $artworkId,
                'user_id' => $userId,
            ];

            $commissionModel->insert($data);

            return $this->respondCreated([
                'status' => 'success',
                'message' => 'Commission created successfully',
                'data' => $data
            ]);
        } else {
            $response = [
                'status' => 400,
                'message' => 'Bad Request - Artwork not found',
            ];

            return $this->respond($response, 400);
        }
    }

    public function validateCommission($id)
    {
        $commissionModel = new \App\Models\CommissionModel();
    
    
        $commission = $commissionModel->find($id);
    
   
        if (!$commission) {
            return $this->fail('Commission not found', 404);
        }
    
    
        if ($commission->status === 'validated') {
            return $this->fail('Commission is already validated', 400);
        }
    
       
        $commissionModel->update($id, ['status' => 'validated']);
    
        return $this->respond(['message' => 'Commission validated successfully'], 200);
    }
    public function unvalidateCommission($id)
    {
        $commissionModel = new \App\Models\CommissionModel();
    
    
        $commission = $commissionModel->find($id);
    
   
        if (!$commission) {
            return $this->fail('Commission not found', 404);
        }    
       
        $commissionModel->update($id, ['status' => 'not validated']);
    
        return $this->respond(['message' => 'Commission validated successfully'], 200);
    }

    public function deleteCommission($id)
    {
        $CommissionModel = new \App\Models\CommissionModel();
        $CommissionModel->delete($id);

        return $this->respondDeleted([
            'status' => 'success',
            'message' => 'Commission deleted successfully',
        ]);
    }
    


}
