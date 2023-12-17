<?php

namespace App\Models;

use CodeIgniter\Model;
use PhpParser\Comment;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'name', 'username', 'email', 'description', 'password', 'role', 'avatar', 'bank_acc'
    ];
    

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

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

    public function artworks()
    {
        return $this->hasMany(ArtworkModel::class, 'user_id', 'id');
    }
    public function likes()
    {
        return $this->hasMany(LikeModel::class, 'user_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(CommentModel::class, 'user_id', 'id');
    }

    public function saved_artworks()
    {
        return $this->hasMany(SavedModel::class, 'user_id', 'id');
    }
    public function commissions()
    {
        return $this->hasMany(CommissionModel::class, 'user_id', 'id');
    }
}
