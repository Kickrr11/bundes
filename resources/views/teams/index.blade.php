@extends('layouts.app')
 
@section('content')
   <div class="card-body">
        <table id="myTable" class="table table-striped">
            <thead>
            <tr>
                <th class="all">Id</th>
                <th class="all">Won/Lost</th>
                <th>Name</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($teams as $team)
                <tr>
                    <td>{{$team->id}}</td>
                    <td>
                        {{ $team->matches_won_count }} / {{ $team->matches_lost_count }}
                    </td>
                    <td>{{$team->name}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection