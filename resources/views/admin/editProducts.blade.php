@extends('layouts.app')

@section('content')
    <div class="entry-content">
        <div>
            <a href="/admin"><h3>Back</h3></a>
        </div>
        <div class="main-container">
            <div class="main-title">Products</div>
            <div class="products-container">
                <div class="products-wrapper"></div>
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
        let assetBaseUrl = "{{ asset('') }}";
        let ajaxGetProductRoute = "{{ route('ajaxGetProduct.post') }}";
        let csrfToken = '{{ csrf_token() }}';
        let editedProducts = {};
        var categoriesData = '<?php echo json_encode($categories); ?>';
        var categories = JSON.parse(categoriesData);
        var productsData = '<?php echo json_encode($products); ?>';
        var products = JSON.parse(productsData);
        let $productsContainer = $('.products-container');
        let $productsWrapper = $('.products-wrapper');
        let promise;
        let $body = $('body');
        let ajaxDataObj = {
            'action': 'get_all_products',
            'offset': 0,
            'length': 8
        };

        let ajaxCompleted = true;
        $(window).on('load', function () {
            renderProducts(products);
            $productsContainer.scroll(function () {
                $.when(promise).then(function () {
                    let topPoint = $productsContainer.scrollTop();
                    let bottomVisiblePoint = $productsContainer.height();
                    let overallHeight = $productsWrapper.height();
                    if ((topPoint + bottomVisiblePoint >= overallHeight) && ajaxCompleted) {
                        ajaxCompleted = false;
                        ajaxDataObj.offset += 4;
                        ajaxDataObj.length = ajaxDataObj.offset + 4;
                        getNextProducts();
                    }
                });
            });

            $productsContainer.on('mouseenter', '.product-container', function () {
                $(this).find('.img-wrapper').show();
            }).on('mouseleave', '.product-container', function () {
                $(this).find('.img-wrapper').hide();
            });

            $productsContainer.on('mouseenter', '.img-wrapper', function () {
                $(this).find('.actions-wrapper').show();
            }).on(
                'mouseleave', '.img-wrapper', function () {
                    $(this).find('.actions-wrapper').hide();
                });

            $productsContainer.on('click', '.delete-product', function () {
                let productId = $(this).data("product-id");
                deleteProduct(productId);
            });

            function getNextProducts() {
                let offset = ajaxDataObj.offset;
                let length = ajaxDataObj.length;
                promise = $.ajax({
                    url: "{{ route('ajaxProducts.post') }}",
                    method: 'POST',
                    data: {
                        'offset': offset,
                        'length': length,
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

            $productsContainer.on("click", ".edit-product", function () {
                let productId = $(this).data("product-id");
                if (editedProducts.hasOwnProperty(productId)) {
                    formEditProduct(editedProducts[productId]);
                } else {
                    getProduct(productId);
                }
            });

            $("#downBtn").on("click", function () {
                scrollBottom();
            });
        });

        function renderProducts(products) {
            for (let key in products) {
                if (editedProducts.hasOwnProperty(products[key].id)) {
                    $productsWrapper
                        .append(productBadge(editedProducts[products[key].id]));
                } else {
                    $productsWrapper
                        .append(productBadge(Product.bindProductObject(products[key])));
                }
            }
        }

        /**
         *
         * @param {Product} product
         */
        function formEditProduct(product) {
            let editForm = `<div class="form-container">
                   <form id="edit-form" enctype="multipart/form-data">
                    <div class="form-group img-container">
                      <div class="img-file-wrapper">
                         <img id="current-img" src="${product.url}" alt="Product image" height="120" width="120">
                         <label for="product-img" class="">Change Image</label>
                         <input id="product-img" type="file" class="form-control" name="product_image" value="">
                      </div>
                      <img id="prev-img" src="#" alt="product image" />
                    </div>
                    <div class = "form-group>
                    <label for = "pName"> Product: </label>
                              <input type = "text" name = "name" id = "pName"  class = "form-control" value = "${product.name}" > <br>
                              <input type = "hidden" id = "product_id" name = "id" value = "${product.id}">
                                                  </div>
                                                  <div class = "form-group>
                                                  <label for = "price"> Price: </label>
                              <input type = "text" name = "price" id = "price" class = "form-control" value = "${product.price}" > <br>
                                                  <div>
                                                  <div class = "form-group">
                                                  <label for = "category_name" > Category: </label>
                              <select name = "category_id" id = "category_name" class = "form-control">`;
            for (let category of categories) {
                if (product.category.name === category.name) {
                    editForm += "<option value=" + category.id + " selected >" + category.name + "</option>";
                } else {
                    editForm += "<option value=" + category.id + ">" + category.name + "</option>";
                }
            }

            editForm += ` </select></div></form></div>`;

            bootbox.dialog({
                title: 'Edit product',
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
                        label: "Edit",
                        className: 'btn-info',
                        callback: function () {
                            let category_name = $("#category_name :selected").text();
                            let form = $('#edit-form')[0];
                            let form_data = new FormData(form);
                            let file_data = jQuery('#product-img').prop('files')[0];
                            form_data.append('category_name', category_name);
                            form_data.append('product_image', file_data);
                            form_data.append('_token', '{{csrf_token()}}');
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
                            renderProducts();
                        }
                    }
                }
            }).init(function () {

            })
        }

        function getProduct(productId) {
            let getProduct = Product.getProduct(productId);
            getProduct.then((response) => {
                    let responseObj = response[0];
                    let product = Product.bindProductObject(responseObj);
                    formEditProduct(product);
                },
                (err) => {
                    console.log("Something goes wrong: ", err);
                }
            );
        }

        function scrollBottom() {
            let $bottomElm = $("#bottom-position")[0];
            $bottomElm.scrollIntoView({block: 'start', behavior: 'smooth'});
        }

        /**
         *
         * @param {Product[]} products
         */
        function editProducts(products) {
            let ajaxPromisesEditedProducts = [];
            const keys = Object.keys(products);
            for (const key of keys) {
                ajaxPromisesEditedProducts.push(products[key].edit());
            }

            Promise.all(ajaxPromisesEditedProducts)
                .then((response) => {
                        Object.keys(products).forEach(k => delete products[k]);
                        location.reload(true);
                        console.log(response);
                    },
                    (err) => {
                        console.log(err);
                    }
                )
        }

        function deleteProduct(productId) {
            $.ajax({
                url: "{{ route('ajaxDeleteProduct.post') }}",
                method: 'POST',
                data: {
                    'id': productId,
                    '_token': '{{ csrf_token() }}'
                },
                success: function (response) {
                    let currentBadge = $(".product-container[data-product-id='" + productId + "']");
                    if (currentBadge.length > 0) {
                        currentBadge.hide();
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert("Status: " + textStatus);
                    alert("Error: " + errorThrown);
                }
            });
        }

        function readURL(input) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();

                reader.onload = function (e) {
                    $('#prev-img').attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]); // convert to base64 string
                $('#current-img').hide();
                $('#prev-img').show();
            }
        }

        $body.on('change', "#product-img", function () {
            readURL(this);
        });

    </script>
@stop