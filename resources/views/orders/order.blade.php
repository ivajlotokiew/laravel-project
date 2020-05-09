@extends('layouts.app')

@section('content')
    {{ Breadcrumbs::render('product', $product) }}
    <div class="entry-content">
        <div class="main-container">

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
        let productData = '<?=json_encode($product);?>';
        let product = JSON.parse(productData);
        let mainContainer = $('.main-container');
        let $body = $("body");
        $(window).on('load', function () {
            renderProduct(product);

            $body.on('click', '#buy_product > .btn', function () {
                let quantity = $(this).siblings('#product_quantity').val();
                addProductToCart(quantity);
            });

            function addProductToCart(quantity) {
                $.ajax({
                    url: "{{ route('ajaxAddProductToCart.post') }}",
                    method: 'POST',
                    data: {
                        'product_id': product.id,
                        'price': product.price,
                        'quantity': quantity,
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function () {
                        addToCartPopUp(product, quantity);
                    },
                    error: function (err) {
                        ajaxCompleted = true;
                        console.log(err.responseText);
                    }
                });
            }

        });

        function renderProduct(product) {
            let $productBadge = singleProductBadge(Product.bindProductObject(product));
            mainContainer.append($productBadge);
        }

    </script>
@stop