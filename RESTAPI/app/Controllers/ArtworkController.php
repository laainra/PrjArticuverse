<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class ArtworkController extends ResourceController
{
    protected $format = 'json';

    // public function index()
    // {
    //     $ArtworkModel = new \App\Models\ArtworkModel();
    //     $data = $ArtworkModel->findAll();

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
        $ArtworkModel = new \App\Models\ArtworkModel();
        $artworks = $ArtworkModel->select('artworks.*, genres.name as genre_name, users.name as user_name, users.username as user_username, users.avatar as user_avatar')
            ->join('genres', 'genres.id = artworks.genre', 'left') // Adjust the join condition
            ->join('users', 'users.id = artworks.user_id', 'left')  // Adjust the join condition
            ->findAll();

        $formattedData = [];

        if (!empty($artworks)) {
            foreach ($artworks as $artwork) {
                $formattedData[] = [
                    'id' => $artwork->id,
                    'title' => $artwork->title,
                    'description' => $artwork->description,
                    'media' => $artwork->media,
                    'artist' => $artwork->artist,
                    'creation_year' => $artwork->creation_year,
                    'genre' => $artwork->genre_name,
                    'user_id' => $artwork->user_id,
                    'user_username' => $artwork->user_username,
                    'user_name' => $artwork->user_name,
                    'user_avatar' => $artwork->user_avatar,
                    'created_at' => $artwork->created_at,
                    'updated_at' => $artwork->updated_at,
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
    public function showArtworkById($id)
    {
        $ArtworkModel = new \App\Models\ArtworkModel();
        $artwork = $ArtworkModel->select('artworks.*, genres.name as genre_name, users.name as user_name, users.username as user_username, users.avatar as user_avatar')
            ->join('genres', 'genres.id = artworks.genre', 'left')
            ->join('users', 'users.id = artworks.user_id', 'left')
            ->find($id);

        $data =      [
            'id' => $artwork->id,
            'title' => $artwork->title,
            'description' => $artwork->description,
            'media' => $artwork->media,
            'artist' => $artwork->artist,
            'creation_year' => $artwork->creation_year,
            'genre' => $artwork->genre_name,
            'user_id' => $artwork->user_id,
            'user_username' => $artwork->user_username,
            'user_name' => $artwork->user_name,
            'user_avatar' => $artwork->user_avatar,
            'created_at' => $artwork->created_at,
            'updated_at' => $artwork->updated_at,
        ];


        if (!empty($artwork)) {
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

    public function create()
    {
        $ArtworkModel = new \App\Models\ArtworkModel();
        $GenreModel = new \App\Models\GenreModel();

        // Assuming there is an 'artist' field
        $artist = $this->request->getVar('artist');
        // Assuming there is a 'media' field
        $media = $this->request->getFile('media'); // Assuming 'media' is the name attribute of your file input
        $creationYear = $this->request->getVar('creation_year');
        $title = $this->request->getVar('title');
        $description = $this->request->getVar('description');
        $genreName = $this->request->getVar('genre');
        $userId = $this->request->getVar('user_id'); // Assuming there is a 'genre' field

        // Check if the required fields are empty
        if (empty($title) || empty($description) || empty($genreName)) {
            $response = [
                'status' => 400,
                'message' => 'Bad Request - Missing required data',
            ];

            return $this->respond($response, 400); // Return a response for the bad request
        }

        // Check if the media file is valid
        if ($media->isValid() && !$media->hasMoved()) {
            // Move the uploaded file to the desired directory
            $newName = $media->getRandomName();
            $media->move('./path/to/upload/directory', $newName);

            // Get the user ID from the authenticated user (assuming you have an authentication system in place)
            // Implement this function to retrieve the user ID

            // Check if the genre exists, and get its ID
            $genre = $GenreModel->where('name', $genreName)->first();
            $genreId = $genre ? $genre['id'] : null;

            // Create an array with the data to be inserted, including the user ID and file path
            $data = [
                'title' => $title,
                'description' => $description,
                'artist' => $artist,
                'media' => $newName,
                'creation_year' => $creationYear,
                'genre' => $genreId,
                'user_id' => $userId,
                // Add other fields as needed
            ];

            // Insert the data into the database
            $ArtworkModel->insert($data);

            // Return a success response
            return $this->respondCreated([
                'status' => 'success',
                'message' => 'Artwork created successfully',
                'data' => $data
            ]);
        } else {
            // Handle the case where the media file is not valid
            $response = [
                'status' => 400,
                'message' => 'Bad Request - Invalid media file',
            ];

            return $this->respond($response, 400);
        }
    }


    public function updateArtwork($id)
    {
        $ArtworkModel = new \App\Models\ArtworkModel();
        $Artwork = $ArtworkModel->find($id);

        if ($Artwork) {
            // Access individual values from form data
            $title = $this->request->getVar('title') ?? null;
            $description = $this->request->getVar('description') ?? null;
            $genreName = $this->request->getVar('genre') ?? null;
            $artist = $this->request->getVar('artist') ?? null;
            $creation_year = $this->request->getVar('creation_year') ?? null;

            // Assuming the Genre name is sent in the request
            $GenreModel = new \App\Models\GenreModel();

            // Retrieve the Genre by name
            $genre = $GenreModel->where('name', $genreName)->first();

            // If Genre found, get its id, otherwise set to null
            $genreId = $genre ? $genre['id'] : null;

            // Get the user ID from the authenticated user (assuming you have an authentication system in place)
            $userId = $this->request->getVar('user_id');

            // Get the existing media file name
            // $media = $this->request->getVar('media');

            // if ($file = $this->request->getFile('media')) {
            //     // If a new file is provided, upload and update the media field
            //     $newMediaName = $file->getRandomName();
            //     $file->move('./path/to/upload/directory', $newMediaName);
            //     $media = $newMediaName;
            // }

            // Prepare the data array, including the user ID
            $data = [
                'id'=> $Artwork->id,
                'title' => $title,
                'description' => $description,
                'genre' => $genreId,
                // 'media' => $media,
                'artist' => $artist,
                'creation_year' => $creation_year,
                'user_id' => $userId,
            ];

            // Update the Artwork
            $proses = $ArtworkModel->update($id, $data);

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



    protected function getAuthenticatedUserId()
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
        $userId = $decoded->iss;

        return $userId;
    }

    public function deleteArtwork($id)
    {
        $ArtworkModel = new \App\Models\ArtworkModel();
        $ArtworkModel->delete($id);

        return $this->respondDeleted([
            'status' => 'success',
            'message' => 'Exhibition deleted successfully',
        ]);
    }

    public function searchArtwork()
    {
        $ArtworkModel = new \App\Models\ArtworkModel();

        // Assuming the search query is sent in the request body
        $data = $this->request->getJSON();
        $search = $data->search;

        // Perform the search based on the searchQuery
        $result = $ArtworkModel->like('title', $search)->findAll();

        return $this->respond([
            'status' => 'success',
            'message' => 'Artwork search successful',
            'result' => $result,
        ]);
    }

    public function getArtworksByUserId($userId)
    {
        $ArtworkModel = new \App\Models\ArtworkModel();
        $UserModel = new \App\Models\UserModel();
        $user = $UserModel->find($userId);
    
        if ($user) {
            $artworks = $ArtworkModel->select('artworks.*, genres.name as genre_name, users.name as user_name')
                ->join('genres', 'genres.id = artworks.genre', 'left')
                ->join('users', 'users.id = artworks.user_id', 'left')
                ->where('artworks.user_id', $userId)
                ->findAll();
    
            if (!empty($artworks)) {
                $data = [
                    'status' => 'success',
                    'message' => 'Data retrieved successfully',
                    'data' => $artworks
                ];
            } else {
                $data = [
                    'status' => 'error',
                    'message' => 'No artworks found for the user',
                    'data' => []
                ];
            }
        } else {
            $data = [
                'status' => 'error',
                'message' => 'User not found',
                'data' => []
            ];
        }
    
        return $this->respond($data);
    }
    

    // public function create()
    // {
    //     $ArtworkModel = new \App\Models\ArtworkModel();
    //     $GenreModel = new \App\Models\GenreModel();

    //     // Assuming there is an 'artist' field
    //     $artist = $this->request->getVar('artist');
    //     // Assuming there is a 'media' field
    //     $media = $this->request->getVar('media');
    //     // Assuming there is a 'creation_year' field
    //     $creationYear = $this->request->getVar('creation_year');
    //     $title = $this->request->getVar('title');
    //     $description = $this->request->getVar('description');
    //     $genreName = $this->request->getVar('genre'); // Assuming there is a 'genre' field

    //     if (empty($title) || empty($description) || empty($genreName)) {
    //         $response = [
    //             'status' => 400,
    //             'message' => 'Bad Request - Missing required data',
    //         ];

    //         return $this->respond($response, 400); // Return a response for the bad request
    //     }

    //     // Check if the genre exists, and get its ID
    //     $genre = $GenreModel->where('name', $genreName)->first();
    //     $genreId = $genre ? $genre['id'] : null;

    //     // Create an array with the data to be inserted
    //     $data = [
    //         'title' => $title,
    //         'description' => $description,
    //         'artist' => $artist,
    //         'media' => $media,
    //         'creation_year' => $creationYear,
    //         'genre' => $genreId,
    //         // Add other fields as needed
    //     ];

    //     // Insert the data into the database
    //     $ArtworkModel->insert($data);

    //     // Return a success response
    //     return $this->respondCreated([
    //         'status' => 'success',
    //         'message' => 'Artwork created successfully',
    //         'data' => $data
    //     ]);
    // }


    // public function updateArtwork($id)
    // {
    //     $ArtworkModel = new \App\Models\ArtworkModel();
    //     $Artwork = $ArtworkModel->find($id);

    //     if ($Artwork) {
    //         // Retrieve JSON data from the request
    //         $jsonData = $this->request->getJSON();

    //         // Access individual values from JSON data
    //         $title = $jsonData->title ?? null;
    //         $description = $jsonData->description ?? null;
    //         $genreName = $jsonData->genre ?? null;
    //         $media = $jsonData->media ?? null;
    //         $artist = $jsonData->artist ?? null;
    //         $creation_year = $jsonData->creation_year ?? null;

    //         // Assuming the Genre name is sent in the request
    //         $GenreModel = new \App\Models\GenreModel();

    //         // Retrieve the Genre by name
    //         $genre = $GenreModel->where('name', $genreName)->first();

    //         // If Genre found, get its id, otherwise set to null
    //         $genreId = $genre ? $genre['id'] : null;

    //         // Prepare the data array
    //         $data = [
    //             'title' => $title,
    //             'description' => $description,
    //             'genre' => $genreId,
    //             'media' => $media,
    //             'artist' => $artist,
    //             'creation_year' => $creation_year,
    //         ];

    //         // Update the Artwork
    //         $proses = $ArtworkModel->update($id, $data);

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



}
