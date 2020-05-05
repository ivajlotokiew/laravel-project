@extends('layouts.app')

@section('content')
    {{ Breadcrumbs::render('products') }}
    <div class="entry-content">
        <div class="main-container">
            <div class="main-title">Products</div>
            <div class="products-container">
                <div class="products-wrapper">

                </div>
            </div>

        </div>
        <div id="bottom-position" class="form-group">
            <label for="eProducts">
                <input type="button" class="btn button-primary pull-right" name="name" id="eProducts"
                       value="Apply changes"/>
            </label>
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
        let productsData = '<?= json_encode($products); ?>';
        let products = JSON.parse(productsData);
        let $productsContainer = $('.products-container');
        let $productsWrapper = $('.products-wrapper');
        let $body = $("body");
        let ajaxCompleted = true;
        let ajaxDataObj = {
            'offset': 4,
            'limit': 8,
            'category_id': products[0].category_id
        };

        $(window).on('load', function () {
            renderProducts(products);
        });

        function renderProducts(products) {
            for (let key in products) {
                $productsWrapper.append(
                    clientViewProductBadge(Product.bindProductObject(products[key])));
            }
        }

    </script>
@stop