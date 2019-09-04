@extends('layouts.app')
 
@section('content')
   <div class="card-body">
        <table id="myTable" class="table table-striped">
            <thead>
            <tr>
                <th class="all">Id</th>
                <th class="all">Team1</th>
                <th class="all">Team2</th>
                <th>Result</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($matches as $macth)
                <tr>
                    <td>{{$macth['MatchID']}}</td>
                    <td>
                        {{ $macth['Team1']['TeamName'] }}
                    </td>
                    <td>
                        {{ $macth['Team2']['TeamName'] }}
                    </td>
                    <td> 
                        @if (array_key_exists(0, $macth['MatchResults']))
                                {{ $macth['MatchResults'][0]['PointsTeam1'] }} : {{ $macth['MatchResults'][0]['PointsTeam2'] }}
                        @else 
                            {{ 'No result yet' }}
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $matches->links() }}
    </div>
@endsection