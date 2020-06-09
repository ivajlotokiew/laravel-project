@extends('layouts.app')

@section('content')
    <div>
        Confirm: {{ $order->confirmed }}
    </div>

@endsection

@section('page-style-files')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
@stop