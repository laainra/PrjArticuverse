<?php

namespace App\Controllers;

use App\Models\LikeModel;
use CodeIgniter\API\ResponseTrait;

class LikeController extends BaseController
{
    use ResponseTrait;

    public function likeArtwork($artworkId)
    {
        // Get the currently logged-in user's ID
        $userId = $this->request->getVar('user_id'); // Assuming you send user_id with the request

        // Check if the user has already liked the artwork
        $likeModel = new LikeModel();
        $existingLike = $likeModel->where(['user_id' => $userId, 'artwork_id' => $artworkId])->first();

        if ($existingLike) {
            // User has already liked, unlike it
            $likeModel->delete($existingLike['id']);
            $response['liked'] = false;
        } else {
            // User has not liked, like it
            $likeModel->insert(['user_id' => $userId, 'artwork_id' => $artworkId]);
            $response['liked'] = true;
        }

        // Get the updated number of likes for the artwork
        $updatedLikes = $likeModel->where(['artwork_id' => $artworkId])->countAllResults();
        $response['likesCount'] = $updatedLikes;

        return $this->respond($response);
    }
}
