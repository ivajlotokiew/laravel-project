@extends('layouts.app')

@section('content')
    <div class="entry-content">
        <div class="row confirmed-order">
            <h2 class="main-title">Order confirmed</h2>
            <h4 class="primary-order-msg">Our representative will contact you very soon</h4>
            <h5 class="order-msg">Thanks for the trust</h5>
            <a href="{{route('categories')}}">
                <div>Back to categories</div>
            </a>
        </div>
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

@section('page-js-script')
    <script type="text/javascript">
        $(window).on('load', function () {

        });
    </script>
@stop