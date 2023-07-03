<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    use HasFactory;
    protected $table = 'clubs';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    public function match1()
    {
        return $this->hasMany(Score::class, 'first_club_id');
    }

    public function match2()
    {
        return $this->hasMany(Score::class, 'second_club_id');
    }

    public function score1()
    {
        return $this->hasMany(Score::class, 'score_first_club');
    }

    public function score2()
    {
        return $this->hasMany(Score::class, 'score_second_club');
    }

    public function totalWins()
    {
        $totalWins = 0;

        foreach ($this->match1 as $score) {
            $opponentScore = Score::where('second_club_id', $score->second_club_id)
                ->where('first_club_id', $score->first_club_id)
                ->first();

            if ($opponentScore && $score->score_first_club > $opponentScore->score_second_club) {
                $totalWins++;
            }
        }

        foreach ($this->match2 as $score) {
            $opponentScore = Score::where('second_club_id', $score->second_club_id)
                ->where('first_club_id', $score->first_club_id)
                ->first();

            if ($opponentScore && $score->score_second_club > $opponentScore->score_first_club) {
                $totalWins++;
            }
        }

        return $totalWins;
    }

    public function totalSameScore()
    {
        $total = Score::where(function ($query) {
                    $query->where('first_club_id', $this->id)
                        ->orWhere('second_club_id', $this->id);
                })
                ->whereColumn('score_first_club', 'score_second_club')
                ->count();

        return $total;
    }

    public function totalLosses()
    {
        $totalLosses = 0;

        foreach ($this->match1 as $score) {
            $opponentScore = Score::where('second_club_id', $score->second_club_id)
                ->where('first_club_id', $score->first_club_id)
                ->first();

            if ($opponentScore && $score->score_first_club < $opponentScore->score_second_club) {
                $totalLosses++;
            }
        }

        foreach ($this->match2 as $score) {
            $opponentScore = Score::where('second_club_id', $score->second_club_id)
                ->where('first_club_id', $score->first_club_id)
                ->first();

            if ($opponentScore && $score->score_second_club < $opponentScore->score_first_club) {
                $totalLosses++;
            }
        }

        return $totalLosses;
    }

    public function totalGoals()
    {
        $totalGoals = 0;

        foreach ($this->match1 as $score) {
            $totalGoals += $score->score_first_club;
        }

        foreach ($this->match2 as $score) {
            $totalGoals += $score->score_second_club;
        }

        return $totalGoals;
    }

    public function totalGoalsConceded()
    {
        $totalGoalsConceded = 0;

        foreach ($this->match1 as $score) {
            $totalGoalsConceded += $score->score_second_club;
        }

        foreach ($this->match2 as $score) {
            $totalGoalsConceded += $score->score_first_club;
        }

        return $totalGoalsConceded;
    }

    public function totalPoints()
    {
        $totalPoints = $this->totalWins() * 3 + $this->totalSameScore();

        return $totalPoints;
    }
}
