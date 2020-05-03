@extends('layouts.app')

@section('content')
    <div class="entry-content">
        <h1>This is admin page for editing shop articles</h1>
        <div><a href="admin/editCategories"><h2>Edit categories</h2></a></div>
        <div><a href="admin/editProducts"><h2>Edit products</h2></a></div>
        <div><a href="#" class="cProduct"><h2>Add product</h2></a></div>
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
    <script>
        let $body = $('body');
        $(window).on('load', function () {

            $body.on('click', '.cProduct', function () {
                $.ajax({
                    url: "{{ route('ajaxCategories.post') }}",
                    method: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}'
                    }
                }).done(categories => {
                    bootboxCreateCategory(categories);
                }).fail(function (error) {
                    console.log(error.responseText);
                });
            });

            function bootboxCreateCategory(categories) {
                let editForm = `<div class="row">
                <div class="col-12">
                <div class="product-created">Product was successfully created!</div>
                <form id="create-form" enctype="multipart/form-data">
                    <div class="form-group img-container">
                      <div class="img-file-wrapper">
                         <label for="product-img" class="col-md-4 col-form-label text-md-right">Product Image</label>
                         <input id="product-img" type="file" class="form-control" name="product_image" value="">
                      </div>
                      <img id="prev-img" src="#" alt="product image" />
                    </div>
                    <div class="form-group">
                      <label for="productName">Product name</label>
                      <input type="text" name="name" value="" class="form-control" id="productName" placeholder="Enter product name">
                      <span class="error-field name">Missing or wrong name</span>
                    </div>
                    <div class="form-group">
                      <label for="productDescription">Description</label>
                      <input type="text" name="description" value="" class="form-control" id="productDescription" placeholder="Description">
                      <span class="error-field description">Description must be at least 5 symbols.</span>
                    </div>
                    <div class="form-group">
                      <label for="productPrice">Price</label>
                      <input type="text" name="price" value="" class="form-control" id="productPrice" placeholder="Price">
                      <span class="error-field price">Missing or wrong format price</span>
                    </div>
                   <select class="selectpicker" name="category_id" title="Select category...">`;

                for (let i = 0; i < categories.length; i++) {
                    for (let key in categories[i]) {
                        if (categories[i].hasOwnProperty(key) && key === 'id') {
                            var id = categories[i][key];
                        }

                        if (categories[i].hasOwnProperty(key) && key === 'name') {
                            var name = categories[i][key];
                        }
                    }

                    editForm += '<option value="' + id + '">' + name + '</option>\n';
                }

                editForm += '</select>\n' +
                    '</div>\n' +
                    '</form>\n' +
                    '</div>';

                let dialog = bootbox.dialog({
                    title: 'Create new product',
                    message: editForm,
                    size: 'large',
                    buttons: {
                        cancel: {
                            label: "Cancel",
                            className: 'btn-danger',
                            callback: function () {
                                console.log('Custom cancel clicked');
                            }
                        },
                        success: {
                            label: "Create",
                            className: 'btn-info',
                            callback: function () {
                                let form = $('#create-form')[0];
                                let form_data = new FormData(form);
                                let file_data = jQuery('#product-img').prop('files')[0];
                                form_data.append('product_image', file_data);
                                form_data.append('_token', '{{csrf_token()}}');
                                $.ajax({
                                    url: "{{ route('ajaxCreateProduct.post') }}",
                                    method: 'POST',
                                    data: form_data,
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    success: function (response) {
                                        console.log('HEERERE');
                                        $('#create-form')[0].reset();
                                        $('div.product-created').show();
                                        $('img#prev-img').hide();

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

                                return false;
                            }
                        }
                    }
                }).init(function () {
                    $('.modal-body').on('focus', '.form-control', function () {
                        $(this).next().hide();
                    });
                });
            }

            function readURL(input) {
                console.log(input);
                if (input.files && input.files[0]) {
                    let reader = new FileReader();

                    reader.onload = function (e) {
                        $('#prev-img').attr('src', e.target.result);
                    };

                    reader.readAsDataURL(input.files[0]); // convert to base64 string
                    $('#prev-img').show();
                }
            }

            $body.on('change', "#product-img", function () {
                readURL(this);
            });
        })

    </script>
@stop