@extends('layouts.base')
@section('content')
    <div class="card">
        <h5 class="card-header text-center">Klasemen</h5>
        <div class="card-body">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                    <th scope="col">No</th>
                    <th scope="col">Club</th>
                    <th scope="col">Ma</th>
                    <th scope="col">Me</th>
                    <th scope="col">S</th>
                    <th scope="col">K</th>
                    <th scope="col">GM</th>
                    <th scope="col">GK</th>
                    <th scope="col">Point</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1;
                    @endphp
                    @foreach ($clubs as $club)
                            <tr>
                                <th scope="row">{{ $no }}</th>
                                <td>{{ $club->name }}</td>
                                <td>{{ $club->match1_count + $club->match2_count }}</td>
                                <td>{{ $club->totalWins() }}</td>
                                <td>{{ $club->totalSameScore() }}</td>
                                <td>{{ $club->totalLosses() }}</td>
                                <td>{{ $club->totalGoals() }}</td>
                                <td>{{ $club->totalGoalsConceded() }}</td>
                                <td>{{ $club->totalPoints() }}</td>
                            </tr>
                    @php
                        $no++;
                    @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection