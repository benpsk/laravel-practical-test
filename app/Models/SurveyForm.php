<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $phone_no
 * @property string $gender
 * @property mixed $created_at
 * @property mixed $user
 */
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
