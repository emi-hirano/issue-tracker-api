<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Issue extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id', 'reporter_id', 'assignee_id',
        'title', 'description', 'status', 'priority',
    ];

    // このissueが属するプロジェクト（1対多の「多」側）
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // 起票者（issues.reporter_id → users.id）
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    // 担当者（issues.assignee_id → users.id、未アサインなら null）
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }
    
    // このissueに付いてるラベル（多対多）
    public function labels()
    {
        return $this->belongsToMany(Label::class);
    }

    // このissueへのコメント（1対多の「1」側）
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
    //