<?php

namespace App\Controllers;

use App\Models\SavedModel;
use CodeIgniter\API\ResponseTrait;

class SavedController extends BaseController
{
    use ResponseTrait;

    public function saveArtwork($artworkId)
    {
        // Get the currently logged-in user's ID
        $userId = $this->request->getVar('user_id'); // Assuming you send user_id with the request

        $savedModel = new SavedModel();
        $existingSaved = $savedModel->where(['user_id' => $userId, 'artwork_id' => $artworkId])->first();

        if ($existingSaved) {
     
            $savedModel->delete($existingSaved['id']);
            $response['saved'] = false;
        } else {
        
            $savedModel->insert(['user_id' => $userId, 'artwork_id' => $artworkId]);
            $response['saved'] = true;
        }

        return $this->respond($response);
    }
    
    public function getArtworksByUserId($userId)
    {
        $savedModel = new SavedModel();
        $artworkIds = $savedModel->getArtworksByUserId($userId);

        // Assuming you have an ArtworkModel to get artwork details
        $artworkModel = new \App\Models\ArtworkModel();
        $artworks = [];

        foreach ($artworkIds as $artworkId) {
            $artwork = $artworkModel->find($artworkId->artwork_id);
            if ($artwork) {
                $artworks[] = $artwork;
            }
        }

        return $this->respond($artworks);
    }
}