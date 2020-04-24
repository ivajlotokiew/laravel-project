@extends('layouts.app')

@section('content')
    <div class="entry-content">
        <span>
            <a href="/admin"><h3>Back to admin menu</h3></a>
        </span>
        @foreach($categories as $category)
            <article class="category-miniature col-lg-4 col-md-6 col-sm-6 col-xs-12"
                     data-id-category="{{$category->id}}">
                <div class="category-container">
                    <div class="category-image">
                        <a href="/categories/{{$category->id}}"
                           class="thumbnail category-thumbnail">
                            <img class="img_1" src={{asset("images/category_" . $category->id . ".jpg") }} alt="">
                        </a>
                    </div>
                    <div class="category-info">
                        <h5 class="category-title" itemprop="name"><a href="">{{$category->name}}</a></h5>
                    </div>
                </div>
            </article>
        @endforeach
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