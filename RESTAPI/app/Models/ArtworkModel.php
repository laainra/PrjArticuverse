<?php

namespace App\Models;


use CodeIgniter\Model;

class ArtworkModel extends Model
{
    protected $table = 'artworks';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'title', 'description', 'media', 'artist', 'creation_year', 'genre', 'artist_id', 'user_id'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];


    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }

    public function getArtworksByUserId($userId)
    {
        // Use leftJoin to fetch artworks for a specific user
        $builder = $this->db->table('artworks');
        $builder->select('artworks.*, users.name as user_name, users.username as user_username'); // Add other fields as needed
        $builder->join('users', 'users.id = artworks.user_id', 'left');
        $builder->where('artworks.user_id', $userId);

        $query = $builder->get();

        return $query->getResult();
    }
}
