@include('includes/header_start')
@include('includes/header_end')

<!-- Page title -->
<ul class="list-inline menu-left mb-0">
    <li class="list-inline-item">
        <button type="button" class="button-menu-mobile open-left waves-effect">
            <i class="ion-navicon"></i>
        </button>
    </li>
    <li class="hide-phone list-inline-item app-search">
        <h3 class="page-title">{{ $title }}</h3>
    </li>
</ul>

<div class="clearfix"></div>
</nav>

</div>
<!-- Top Bar End -->

<!-- ==================
     PAGE CONTENT START
     ================== -->

<div class="page-content-wrapper">

    <div class="container-fluid">
        <div class="row">
            @foreach ($orders as $order)
                <a href="{{ route('place-catering-order', ['idOrder' => $order->idcatering_order_items]) }}">
                    <div class="col-lg-3" style="padding-bottom: 50px">
                        <div class="card" style="width: 18rem;">
                            <img class="card-img-top" src="assets/images/orders/{{ $order->image }}" height="250"
                                alt="Card image cap">

                            <div class="card-body">
                                <h4 style="color:black">{{ $order->name }}</h4>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <p style="color:black"><b>Rs: {{ number_format($order->price, 2) }}</b></p>
                                    </div>
                                    {{-- <div class="col-lg-6">
                                        <p style="color:black;text-align:right"><b>Qty:
                                                {{ number_format($order->qty, 2) }}</b></p>
                                    </div> --}}
                                    <div class="col-lg-12">
                                        <p style="color:black"> {{ $order->description }}</p>
                                    </div>
                                </div>
                                <button class="btn btn-outline-primary btn-block">Order Now</button>
                            </div>

                        </div>
                    </div>
                </a>
            @endforeach

        </div>
    </div>
</div>




@include('includes/footer_start')
<script type="text/javascript">
    $(document).ready(function() {
        $('form').parsley();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
    $(document).on("wheel", "input[type=number]", function(e) {
        $(this).blur();
    });
    $(document).ready(function() {
        $(document).on('focus', ':input', function() {
            $(this).attr('autocomplete', 'off');
        });
    });

    function addToCart(id) {
        $.post('add-to-cart', {
            id: id  
        }, function(data) {
            if (data.success != null) {
                notify({
                    type: "success", //alert | success | error | warning | info
                    title: 'ADDED TO CART',
                    autoHide: true, //true | false
                    delay: 2500, //number ms
                    position: {
                        x: "right",
                        y: "top"
                    },
                    icon: '<img src="{{ URL::asset('assets/images/correct.png') }}" />',
                    message: data.success,
                });
            }
        })
    }
</script>


@include('includes/footer_end')
