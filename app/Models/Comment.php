<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['issue_id', 'user_id', 'body'];

    // このコメントが属するissue（Issue側 comments() の反対側）
    public function issue()
    {
        return $this->belongsTo(Issue::class);
    }

    // このコメントを書いた人
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}