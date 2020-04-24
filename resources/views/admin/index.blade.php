@extends('layouts.app')

@section('content')
    <div class="entry-content">
        <h1>This is admin page for editing shop articles</h1>
        <div><a href="admin/editCategories"><h2>Edit categories</h2></a></div>
        <div><a href="admin/editProducts"><h2>Edit products</h2></a></div>
    </div>
@endsection

@section('page-style-files')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
@stop

@section('page-js-files')
    <script src="{{ asset('js/jquery-3.5.0.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/bootbox.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
@stop