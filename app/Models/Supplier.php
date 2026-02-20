<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    protected $fillable = ['name'];

    public function layups()
    {
        return $this->hasMany(CltLayup::class);
    }

    use HasFactory;
}
