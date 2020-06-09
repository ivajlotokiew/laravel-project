@extends('layouts.app')

@section('content')
    <div class="entry-content">
        <span>
            <a href="/admin"><h3>Back to admin menu</h3></a>
        </span>
        @foreach($categories as $category)
            <article class="category-miniature col-lg-4 col-md-6 col-sm-6 col-xs-12"
                     data-category-id="{{$category->id}}">
                <div class="category-container">
                    <div class="category-image">
                        <img class="img_1" data-src="{{$category->category_image}}"
                             src={{$category->category_image}} alt="">
                    </div>
                    <div class="category-info">
                        <h5 class="category-title" itemprop="name">{{$category->name}}</h5>
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
        let $body = $('body');
        let ajaxSuccessfullyEditedCategory = false;
        $(window).on('load', function () {
            $body.on('click', '.category-miniature', function () {
                editCategory(this);
            });

            function editCategory(input) {
                let categoryName = jQuery(input).find('.category-title').text();
                let $categoryId = jQuery(input).data('category-id');
                let categoryImage = jQuery(input).find('.category-image > img').data('src');
                let categoryForm = `
                    <div class="category-container">
                        <form id="category-form">
                            <div class="form-group">
                                <div>
                                    <img id="cat-prev-img" src="${categoryImage}" alt="category image">
                                </div>
                                <label for="cFile">Select a file:</label>
                                <input type="file" class="form-control" id="cFile" name="category_image">
                            </div>
                            <div class="form-group">
                                <label for="cName">Category</label>
                                <input type="text" name="name" class="form-control" id="cName" value="${categoryName}"><br>
                            </div>
                        </form>
                    </div>
                `;

                bootbox.dialog({
                    title: 'Custom Dialog Example',
                    message: categoryForm,
                    size: 'large',
                    onEscape: true,
                    backdrop: true,
                    onHide: function () {
                        if (ajaxSuccessfullyEditedCategory) {
                            ajaxGetEditedCategory($categoryId);
                            ajaxSuccessfullyEditedCategory = false;
                        }
                    },
                    buttons: {
                        Cancel: {
                            label: 'Cancel',
                            className: 'btn-danger',
                            callback: function () {
                            }
                        },
                        Update: {
                            label: 'Update',
                            className: 'btn-primary',
                            callback: function () {
                                let $form = $("#category-form")[0];
                                let formData = new FormData($form);
                                formData.append('id', $categoryId);
                                formData.append('image', $('input[type=file]')[0].files[0]);
                                formData.append('_token', '{{csrf_token()}}');
                                $.ajax({
                                    url: '{{route("ajaxUpdateCategory.post")}}',
                                    method: 'POST',
                                    data: formData,
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    success: function (response) {
                                        ajaxSuccessfullyEditedCategory = true;
                                        bootbox.alert('Successfully edited category');
                                    },
                                    error: function (err) {
                                        ajaxCompleted = true;
                                        console.log(err.responseText);
                                    }
                                });

                                return false;
                            }
                        }
                    }
                })
            }

            function readURL(input) {
                if (input.files && input.files[0]) {
                    let reader = new FileReader();

                    reader.onload = function (e) {
                        $('#cat-prev-img').attr('src', e.target.result);
                    };

                    reader.readAsDataURL(input.files[0]); // convert to base64 string
                }
            }

            $body.on('change', "#cFile", function () {
                readURL(this);
            });

            function ajaxGetEditedCategory($categoryId) {
                $.ajax({
                    url: '{{route("ajaxGetEditedCategory.post")}}',
                    method: 'POST',
                    data: {
                        'category_id': $categoryId,
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        let $mainBadgeElement = $body.find("article[data-category-id='" + $categoryId + "']");
                        $mainBadgeElement.find('h5.category-title').text(response.category.name);
                        $mainBadgeElement.find('img.img_1').attr('src', response.category.category_image);
                        $mainBadgeElement.find('.category-image > img').data('src', response.category.category_image);
                    },
                    error: function (err) {
                        ajaxCompleted = true;
                        console.log(err.responseText);
                    }
                });
            }
        });

    </script>
@stop