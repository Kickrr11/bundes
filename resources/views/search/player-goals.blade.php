@extends('layouts.app')
 
@section('content')
   <div class="card-body">
        @if (session('status'))
            <div class="alert alert-success">
                <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>
                {{ session('status') }}
            </div>
        @endif
        {{ Form::open(array('route' => array('search.player.goals', 'id'=>'test-form' ),'files'=>'true')) }}
        @csrf
        <div class="form-group">
            {!! Form::label('last_name', 'Last Name:', ['class' => 'vc-label']) !!}
            {!! Form::text('last_name', $value=old('last_name'), array('class'=>'form-control','tabindex'=>'2')) !!}
            @if($errors->first('last_name'))
                <p class="error-label">{{ $errors->first('last_name') }}</p>
            @endif
        </div>
        <div class="form-group text-right">
            {!! Form::submit('Submit', ['class' => 'vc-btn vc-raised-btn'] ) !!}
        </div>
        {{ Form::close() }}

    </div>
@endsection