<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClubRequest;
use App\Http\Requests\ScoreRequest;
use App\Models\Club;
use App\Models\Score;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MatchController extends Controller
{
    public function index()
    {
        $clubs = Club::with(['match1', 'match2'])
        ->withCount(['match1', 'match2'])
        ->get()
        ->sortByDesc(function ($club) {
            return $club->totalPoints();
        });
        return view('welcome', compact(['clubs']));
    }

    // Club

    public function club(Request $request)
    {
        if ($request->ajax()) {
            $clubs = Club::all();
            return DataTables::of($clubs)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $btn = '
                        <a class="btn btn-danger delete-club" data-url="'.route('club.destroy', ['id' => $row->id]).'"><i class="bi bi-trash-fill"></i></a>
                        <button type="button" class="btn btn-warning" onclick="openModal('.$row->id.')"
                        ><i class="bi bi-pencil-square text-white"></i>
                        </button>
                        ';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('club');
    }

    public function storeClub(ClubRequest $request)
    {
        $validatedData = $request->validated();
        Club::create($validatedData);
        return redirect()->back()->with(['message'=>'New club has been added!', 'status'=>'success']);
    }

    public function showClub(int $id)
    {
        $club = Club::where('id', $id)->first();
        if(empty($club)) {
            return response()->json([
                'error' => true,
                'error_message' => 'Club not found'
            ]);
        }
        return response()->json([
            'error' => false,
            'path' => url('/club'),
            'data' => $club
        ]);
    }

    public function updateClub(ClubRequest $request, $id)
    {
        $validatedData = $request->validated();
        $club = Club::findOrFail($id);
        $club->update($validatedData);
        return redirect()->back()->with(['message'=>'Club has been updated!', 'status'=>'success']);
    }

    public function destroyClub($id)
    {
        Club::where('id', $id)->delete();
        return redirect()->back()->with(['message'=>'Club has been deleted!', 'status'=>'success']);
    }

    // Score

    public function score(Request $request)
    {
        $clubs = Club::all();

        if ($request->ajax()) {
            $scores = Score::all();
            return DataTables::of($scores)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $btn = '
                        <a class="btn btn-danger delete-score" data-url="'.route('score.destroy', ['id' => $row->id]).'"><i class="bi bi-trash-fill"></i></a>
                        <button type="button" class="btn btn-warning" onclick="openModal('.$row->id.')">
                            <i class="bi bi-pencil-square text-white"></i>
                        </button>
                        ';
                        return $btn;
                    })
                    ->addColumn('match', function ($row) {
                        return $row->firstClub->name .' - '. $row->secondClub->name;
                    })
                    ->addColumn('score', function ($row) {
                        return $row->score_first_club .' - '. $row->score_second_club;
                    })
                    ->rawColumns(['action', 'match', 'score'])
                    ->make(true);
        }

        return view('score', compact('clubs'));
    }

    public function storeScore(ScoreRequest $request)
    {
        $validatedData = $request->validated();

        $firstClubIds = $validatedData['first_club_id'];
        $secondClubIds = $validatedData['second_club_id'];
        $scoreFirstClubs = $validatedData['score_first_club'];
        $scoreSecondClubs = $validatedData['score_second_club'];
        
        // Loop through the arrays and create and save Score models
        foreach ($firstClubIds as $index => $firstClubId) {
            Score::create([
                'first_club_id' => $firstClubId,
                'second_club_id' => $secondClubIds[$index],
                'score_first_club' => $scoreFirstClubs[$index],
                'score_second_club' => $scoreSecondClubs[$index]
            ]);
        }

        return redirect()->back()->with(['message'=>'Match record has been added!', 'status'=>'success']);
    }

    public function showScore(int $id)
    {
        $score = Score::where('id', $id)->first();
        if(empty($score)) {
            return response()->json([
                'error' => true,
                'error_message' => 'Match not found'
            ]);
        }
        return response()->json([
            'error' => false,
            'path' => url('/score'),
            'data' => $score
        ]);
    }

    public function updateScore(ScoreRequest $request, $id)
    {
        $validatedData = $request->validated();
        $score = Score::findOrFail($id);
        $score->update($validatedData);
        return redirect()->back()->with(['message'=>'Match record has been updated!', 'status'=>'success']);
    }

    public function destroyScore($id)
    {
        Score::where('id', $id)->delete();
        return redirect()->back()->with(['message'=>'Match record has been deleted!', 'status'=>'success']);
    }
}
