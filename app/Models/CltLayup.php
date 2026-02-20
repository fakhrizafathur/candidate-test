<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CltLayup extends Model
{
    protected $fillable = ['supplier_id', 'name'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function layers()
    {
        return $this->hasMany(CltLayer::class, 'layup_id');
    }

    use HasFactory;
}
