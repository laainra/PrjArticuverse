<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class MahasiswaEntity extends Entity
{
    protected $attributes = [
        'nama' => null,
        'nim' => null,
        'alamat' => null
    ];

    public function setNama(string $nama): self
    {
        $this->attributes['nama'] = strtoupper($nama);
        return $this;
    }
    public function setNim(string $nim): self
    {
        $this->attributes['nim'] = strtoupper($nim);
        return $this;
    }
    public function setAlamat(string $alamat): self
    {
        $this->attributes['alamat'] = $alamat;
        return $this;
    }
}
