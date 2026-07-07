<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'color'];

    // このラベルが付いてるissue（多対多。Issue側 labels() の反対側）
    public function issues()
    {
        return $this->belongsToMany(Issue::class);
    }
}