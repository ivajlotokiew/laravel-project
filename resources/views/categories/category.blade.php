@extends('layouts.app')

@section('content')
    {{ Breadcrumbs::render('category', $products[0]) }}
    <div class="entry-content">
        <div class="main-container">
            <div class="main-title">Products</div>
            <div class="products-container">
                <div class="products-wrapper">

                </div>
            </div>
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
        let ajaxQuantityProductsCart = '{{route("ajaxGetProductsQuantityToCart.post")}}';
        let $productsContainer = $('.products-container');
        let $productsWrapper = $('.products-wrapper');
        let $body = $("body");
        let ajaxCompleted = true;
        let ajaxDataObj = {
            'offset': 4,
            'limit': 8,
            'category_id': products[0].category_id
        };

        // console.log(typeof products);
        console.log(products);
        $(window).on('load', function () {
            renderProducts(products);

            $body.on('click', '#buy-btn', function () {
                let params = [];
                params['id'] = $(this).closest('article.product-miniature').data('product-id');
                params['price'] = products.find(x => x.id === params['id']).price;
                params['quantity'] = 1;
                addProductToCart(params);
            });

            $productsContainer.scroll(function () {
                if (ajaxCompleted) {
                    let topPoint = $productsContainer.scrollTop();
                    let bottomVisiblePoint = $productsContainer.height();
                    let overallHeight = $productsWrapper.height();
                    if ((topPoint + bottomVisiblePoint >= overallHeight) && ajaxCompleted) {
                        ajaxCompleted = false;
                        ajaxDataObj.offset += 4;
                        ajaxDataObj.length = ajaxDataObj.offset + 4;
                        getNextProducts();
                    }
                }
            });

            function getNextProducts() {
                let offset = ajaxDataObj.offset;
                let limit = ajaxDataObj.limit;
                let category_id = ajaxDataObj.category_id;
                $.ajax({
                    url: "{{ route('ajaxProductsCategory.post') }}",
                    method: 'POST',
                    data: {
                        'offset': offset,
                        'limit': limit,
                        'category_id': category_id,
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function (products) {
                        if (products.constructor === Array && products.length > 0) {
                            ajaxCompleted = true;
                            renderProducts(products);
                        }
                    },
                    error: function (err) {
                        ajaxCompleted = true;
                        console.log(err.responseText);
                    }
                });
            }
        });

        function renderProducts(products) {
            for (let key in products) {
                $productsWrapper.append(
                    clientViewProductBadge(Product.bindProductObject(products[key])));
            }
        }

        function addProductToCart(params) {
            $.ajax({
                url: "{{ route('ajaxAddProductToCart.post') }}",
                method: 'POST',
                data: {
                    'product_id': params['id'],
                    'price': params['price'],
                    'quantity': params['quantity'],
                    '_token': '{{ csrf_token() }}'
                },
                success: function () {
                    let product = products.find(x => x.id === params['id']);
                    addToCartPopUp(product, 1);
                },
                error: function (err) {
                    ajaxCompleted = true;
                    console.log(err.responseText);
                }
            });
        }
    </script>
@stop