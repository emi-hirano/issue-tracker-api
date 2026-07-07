<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'description'];

    // 1つのプロジェクトは複数のissueを持つ（1対多の「1」側）
    public function issues()
    {
        return $this->hasMany(Issue::class);
    }
}