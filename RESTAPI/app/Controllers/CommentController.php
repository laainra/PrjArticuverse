<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\CommentModel;

class CommentController extends BaseController
{
    use ResponseTrait;

    public function getComments($artworkId)
    {
        $commentModel = new CommentModel();
        $comments = $commentModel->getCommentsByArtwork($artworkId);

        $data = [];

        if (!empty($comments)) {
            foreach ($comments as $comment) {
                $data[] = [
                    'id' => $comment['id'],
                    'body' => $comment['body'],
                    'user_id' => $comment['user_id'],
                    'user_username' => $comment['username'],
                    'user_avatar' => $comment['avatar'],
                    'artwork_id' => $comment['artwork_id'],
                    'created_at' => $comment['created_at'],
                    'updated_at' => $comment['updated_at'],
                ];
            }

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

    public function createComment()
    {
        $data = [
            'user_id' => $this->request->getVar('user_id'),
            'artwork_id' => $this->request->getVar('artwork_id'),
            'body' => $this->request->getVar('body'),
        ];

        $commentModel = new CommentModel();
        $commentModel->insert($data);

        return $this->respondCreated(['message' => 'Comment created successfully', 'data'=>$data]);
    }

    public function updateComment($id)
    {
        $commentModel = new CommentModel();
        $comment = $commentModel->find($id);

        if (empty($comment)) {
            return $this->failNotFound('Comment not found');
        }

        $data = [
            'user_id' => $this->request->getVar('user_id'),
            'artwork_id' => $this->request->getVar('artwork_id'),
            'body' => $this->request->getVar('body'),
        ];

        $commentModel->update($id, $data);

        return $this->respond(['message' => 'Comment updated successfully', 'data' => $data]);
    }

    public function deleteComment($id)
    {
        $commentModel = new CommentModel();
        $comment = $commentModel->find($id);

        if (empty($comment)) {
            return $this->failNotFound('Comment not found');
        }

        $commentModel->delete($id);

        return $this->respondDeleted(['message' => 'Comment deleted successfully']);
    }
}
