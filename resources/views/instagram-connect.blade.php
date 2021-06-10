@extends('layouts.client')

@section('content')
    @if ($success = Session::get('connection_success'))
        <div class="text-success">
            {{ $success }}
        </div>
    @endif

    @if ($failed = Session::get('connection_failed'))
        <div class="text-danger">
            {{ $failed }}
        </div>
    @endif

    <div class="d-grid col-4 mx-auto mb-3">
        <a href="{{ $link }}" target="_blank" class="btn btn-outline-light">
            Connect
        </a>
    </div>
@stop
