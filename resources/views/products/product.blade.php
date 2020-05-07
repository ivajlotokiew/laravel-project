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
                        addToCartPopUp(quantity);
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

        function addToCartPopUp(quantity) {
            if (quantity <= 0) {
                quantity = 1;
            }

            let editForm = `
                    <div class="cart-container row">
                        <div class="img-container col-sm-2">
                            <img src="${product.img_url}" class="product-image-miniature" alt="product image">
                        </div>
                        <div class="product-name col-sm-2">${product.name}</div>
                        <div class="col-sm-4">Quantity: ${quantity}</div>
                        <div class="product-price col-sm-3">Total price: ${quantity * product.price} Eur</div>
                    </div>
            `;

            bootbox.dialog({
                title: 'The product was added to the cart.',
                message: editForm,
                size: 'large',
                buttons: {
                    Cancel: {
                        label: "Cancel",
                        className: 'btn-danger',
                        callback: function () {
                            console.log('Custom cancel clicked');
                        }
                    },
                    Edit: {
                        label: "Look at the cart",
                        className: 'btn-info',
                        callback: function () {
                            $.ajax({
                                url: "{{ route('ajaxUpdateProduct.post') }}",
                                method: 'POST',
                                data: form_data,
                                cache: false,
                                contentType: false,
                                processData: false,
                                success: function (response) {
                                    let ajaxDataProduct = response['product'];
                                    let $currentProduct =
                                        $('div.product-container[data-product-id="' + form_data.get('id') + '"]');

                                    $currentProduct.find('div.product-title').text(ajaxDataProduct.name);
                                    $currentProduct.find('span.product-price').text(ajaxDataProduct.price + 'Eur | ');
                                    let category = categories.find(x => x.id === ajaxDataProduct.category_id);
                                    $currentProduct.find('span.product-category').text(category.name);

                                },
                                error: function (xhr, status, data) {
                                    if (xhr.status === 422) {
                                        let errors = xhr.responseJSON.errors;
                                        $.each(errors, function (key, val) {
                                            console.log(key + ' => ' + val);
                                            $('.modal-body').find('span.' + key).show();
                                        });
                                    }
                                }
                            });
                        }
                    }
                }
            })
        }

    </script>
@stop