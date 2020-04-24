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
        let assetBaseUrl = "{{ asset('') }}";
        let ajaxGetProductRoute = "{{ route('ajaxGetProduct.post') }}";
        let csrfToken = '{{ csrf_token() }}';
        let editedProducts = {};
        let categories = <?= json_encode($categories); ?>;
        let products = <?= json_encode($products); ?>;
        let $productsContainer = $('.products-container');
        let $productsWrapper = $('.products-wrapper');
        let promise;
        let $body = $("<body>");
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

            $("#eProducts").on("click", function () {
                if (!(Object.keys(editedProducts).length === 0 && editedProducts.constructor === Object)) {
                    editProducts(editedProducts);
                } else {
                    alert("Check console for more info.");
                    console.error("There are no products to edit.")
                }
            })
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
            let editForm = `<form id = "edit-form">
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

            editForm += ` < /select></div > < /form>`;

            let dialog = bootbox.dialog({
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
                            let data = $("#edit-form").serializeArray();
                            data.push({name: "category_name", value: category_name});
                            let product = objectifyForm(data);
                            let editedProduct = Product.bindProductObject(product);
                            editedProduct.status = true;
                            storeEditedProducts(editedProduct);
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
                    let product = Product.bindProductObject(response);
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

        function objectifyForm(formArray) {//serialize data function
            let returnArray = {};
            for (let i = 0; i < formArray.length; i++) {
                returnArray[formArray[i]['name']] = formArray[i]['value'];
            }

            return returnArray;
        }

        function storeEditedProducts(editedProduct) {
            if (!editedProducts.hasOwnProperty(editedProducts.id)) {
                editedProducts[editedProduct.id] = editedProduct;
            }
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

    </script>
@stop