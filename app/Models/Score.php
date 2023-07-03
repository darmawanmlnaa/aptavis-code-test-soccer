<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;
    protected $table = 'scores';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    public function firstClub()
    {
        return $this->belongsTo(Club::class, 'first_club_id');
    }

    public function secondClub()
    {
        return $this->belongsTo(Club::class, 'second_club_id');
    }
}
