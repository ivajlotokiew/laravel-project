@extends('layouts.app')

@section('content')
    <div class="entry-content">
        <div class="main-title">Products in cart</div>
        <div class="row">
            <div class="main-container col-sm-10">

            </div>
            <div class="right-side-container col-sm-2">
                <div class="cart-total-price">Total price:
                    <span class="tPrice">{{$totalPrice}}</span>
                    <span>Eur</span>
                </div>
                <div class="confirm-to-order">
                    <button class="btn btn-primary">Confirm</button>
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
        let cartData = '<?= json_encode($cart); ?>';
        let cart = JSON.parse(cartData);
        let cartId = cart[0].cart.id;
        let ajaxRemoveCartProduct = '{{route('ajaxRemoveCartProduct.post')}}';
        let ajaxQuantityProductsCart = '{{route('ajaxGetProductsQuantityToCart.post')}}';
        let $container = $('.main-container');
        let $body = $('body');

        $(window).on('load', function () {
            renderCartProducts(cart);

            $body.on('change', '.product-quantity', function () {
                let productId = $(this).closest('.cart-container').data('product-id');
                let quantity = $(this).val();
                let loading = $(this).parent().siblings('div.spinner-border');
                let parent = $(this).closest('div.cart-container.row');
                let params = {
                    'productId': productId,
                    'quantity': quantity,
                    'loading': loading,
                    'parent': parent
                };

                changeCartProductQuantity(params);
            });

            $body.on('click', 'div.remove-from-cart', function () {
                let $productId = $(this).closest('.cart-container.row').data('product-id');
                removeCartProduct($productId);
            });

            $body.on('click', '.confirm-to-order > button.btn', function () {
                confirmCartProducts();
            });
        });

        function renderCartProducts(cart) {
            for (let i = 0; i < cart.length; i++) {
                $container
                    .append(cartProductsViewBadge(CartProduct.bindCartProductObject(cart[i])));
            }
        }

        function changeCartProductQuantity(params) {
            params.loading.show();
            params.parent.fadeOut();
            let productId = params.productId;
            let quantity = params.quantity;

            $.ajax({
                url: "{{ route('ajaxChangeProductCartQuantity.post') }}",
                method: 'POST',
                data: {
                    'product_id': productId,
                    'cart_id': cartId,
                    'quantity': quantity,
                    '_token': '{{ csrf_token() }}'
                },
                success: function (response) {
                    params.loading.hide();
                    params.parent.fadeIn();
                    $body.find('div.cart-total-price > span.tPrice').text(response.total_price);
                    params.parent.find('div.product-price > span.pPrice').text(response.single_product_price);
                    cartProductsQuantity();
                },
                error: function (err) {
                    params.loading.hide();
                    params.parent.fadeIn();
                    console.log(err.responseText);
                }
            });
        }

        function removeCartProduct(productId) {
            $.ajax({
                url: ajaxRemoveCartProduct,
                method: 'POST',
                data: {
                    'cart_id': cartId,
                    'product_id': productId,
                    '_token': csrfToken,
                },
                success: function (response) {
                    $(`*[data-product-id='${productId}']`).hide();
                    $body.find('div.cart-total-price > span.tPrice').text(response.total_price);
                    cartProductsQuantityAjax().promise()
                        .done(function (response) {
                            $('#my_cart').find('span').first().text(response['quantity']);
                            checkIfCartEmpty(cartId);
                        })
                        .fail(function (xhr) {
                            ajaxCompleted = true;
                            console.log(xhr.responseText);
                        });
                }
            })
        }

        function confirmCartProducts() {
            $.ajax({
                url: '{{route('ajaxAddCartProductsToOrder.post')}}',
                method: 'POST',
                data: {
                    'cart_id': cartId,
                    '_token': csrfToken,
                },
                success: function (response) {
                    window.location.href = "{{ route('orderConfirmed')}}";
                },
                error: function (err) {
                    ajaxCompleted = true;
                    console.log(err.responseText);
                }
            });
        }

        function checkIfCartEmpty(cartId) {
            $.ajax({
                url: '{{route('ajaxCheckIfCartEmpty.post')}}',
                method: 'POST',
                data: {
                    'cart_id': cartId,
                    '_token': csrfToken,
                },
                success: function (response) {
                    console.log(response);
                    if (response.is_cart_empty) {
                        window.location.href = "{{ route('emptyCart')}}";
                    }
                },
                error: function (err) {
                    ajaxCompleted = true;
                    console.log(err.responseText);
                }
            });
        }

        function cartProductsQuantityAjax() {
            return $.ajax({
                url: ajaxQuantityProductsCart,
                method: 'POST',
                data: {
                    '_token': csrfToken
                }
            })
        }

    </script>
@stop