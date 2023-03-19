<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyForm extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = ['name', 'phone_no', 'dob', 'gender', 'user_id'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
