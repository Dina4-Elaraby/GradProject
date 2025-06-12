<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserQuestionAnswer extends Model
{
    protected $fillable = [
        'questioner_id',
        'answerer_id',
        'question_id',
        'answer_id',
    ];

    public function questioner()
    {
        return $this->belongsTo(User::class, 'questioner_id');
    }

    public function answerer()
    {
        return $this->belongsTo(User::class, 'answerer_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function answer()
    {
        return $this->belongsTo(Answer::class);
    }
}

