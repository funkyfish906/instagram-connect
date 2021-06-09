@extends('layouts.client')

@section('content')
    @if ($connectedSuccess = Session::get('connection_success'))
        <div class="text-success">
            {{ $connectedSuccess }}
        </div>
    @endif

    @if ($connectedFailed = Session::get('connected_failed'))
        <div class="text-danger">
            {{ Session::get('connected_failed') }}
        </div>
    @endif

    <div class="d-grid col-4 mx-auto mb-3">
        <a href="{{ $link }}" target="_blank" class="btn btn-outline-light">
            Connect
        </a>
    </div>
@stop
