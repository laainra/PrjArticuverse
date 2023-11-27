<?php

namespace App\Models;

use CodeIgniter\Model;

class MahasiswaModel extends Model
{
    protected $table = 'mahasiswa';
    protected $primaryKey = 'id';
    protected $returnType = MahasiswaModel::class;
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'nama',
        'nim',
        'alamat'
    ];
}
