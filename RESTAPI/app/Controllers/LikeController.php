<?php

namespace App\Controllers;

use App\Models\LikeModel;
use CodeIgniter\HTTP\ResponseInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;
use CodeIgniter\API\ResponseTrait;

class LikeController extends BaseController
{
    use ResponseTrait;

    public function checkLike($artworkId, $userId)
    {

        $key = getenv('JWT_SECRET');
        $header = $this->request->getHeader("Authorization");
        $token = null;
    
        if (!empty($header)) {
            if (preg_match('/Bearer\s(\S+)/', $header, $matches)) {
                $token = $matches[1];
            }
        }
        $decoded = JWT::decode($token, new Key($key, 'HS256'));
            $userId = isset($decoded->iss) ? $decoded->iss : null;
        $likeModel = new LikeModel();
        $isLiked = $likeModel->isLikedByUser($artworkId, $userId);
        return $this->respond(['likedByUser' => $isLiked]);
    }

    public function likeArtwork($artworkId)
    {
        $key = getenv('JWT_SECRET');
        $header = $this->request->getHeader("Authorization");
        $token = null;
    
        if (!empty($header)) {
            if (preg_match('/Bearer\s(\S+)/', $header, $matches)) {
                $token = $matches[1];
            }
        }
    
        try {
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            $userIdFromToken = isset($decoded->iss) ? $decoded->iss : null;
            
            $likeModel = new LikeModel();
            $existingLike = $likeModel
                ->where(['artwork_id' => $artworkId, 'user_id' => $userIdFromToken])
                ->first();
    
            if ($existingLike) {
                $likeModel->delete($existingLike['id']);
                $response['liked'] = false;
            } else {
                $likeModel->insert(['user_id' => $userIdFromToken, 'artwork_id' => $artworkId]);
                $response['liked'] = true;
            }
            $updatedLikes = $likeModel->where(['artwork_id' => $artworkId])->countAllResults();
            $response['likesCount'] = $updatedLikes;
    
            return $this->respond($response);
        } catch (Exception $e) {
            return $this->failUnauthorized('Invalid token');
        }
    }
    
    


    public function getLikesByArtwork($artworkId)
{
    
    try {
        // Validate artwork_id (add your own validation rules)
        if (empty($artworkId) || !is_numeric($artworkId)) {
            throw new \Exception('Invalid artwork_id');
        }

        // Check if the artwork exists
        $likeModel = new LikeModel();
        $totalLikes = $likeModel->where(['artwork_id' => $artworkId])->countAllResults();

        $response['totalLikes'] = $totalLikes;

        return $this->respond($response);
    } catch (\Exception $e) {
        // Handle the exception, log it, and return an error response
        return $this->fail($e->getMessage(), 400);
    }
}

}
