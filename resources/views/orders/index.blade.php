@extends('layouts.app')

@section('content')
    {{ Breadcrumbs::render('orders') }}
    <div class="entry-content">
        <div class="main-container">
            <div class="main-title">Orders</div>
            <div class="orders-container">
                <div class="orders-wrapper">

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
        let ordersData = '<?= json_encode($orders); ?>';
        let orders = JSON.parse(ordersData);
        let $ordersContainer = $('.orders-container');
        let $ordersWrapper = $('.orders-wrapper');
        let $body = $("body");
        let ajaxCompleted = true;
        let ajaxDataObj = {
            'offset': 4,
            'limit': 8,
            'category_id': orders[0].category_id
        };

        $(window).on('load', function () {
            console.log(orders);
            renderOrders(orders);
        });

        function renderOrders(orders) {
            for (let key in orders) {
                $ordersWrapper.append(
                    clientViewProductBadge(Product.bindProductObject(orders[key])));
            }
        }

    </script>
@stop