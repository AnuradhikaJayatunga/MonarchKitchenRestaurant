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

<style>
    .qty .count {
        color: #000;
        display: inline-block;
        vertical-align: top;
        font-size: 20px;
        font-weight: 700;
        line-height: 30px;
        min-width: 30px;
        text-align: center;
        margin-top: -6px;
    }

    .qty .plus {
        cursor: pointer;
        display: inline-block;
        vertical-align: top;
        color: white;
        width: 20px;
        height: 20px;
        font: 20px/1 Arial, sans-serif;
        text-align: center;
        border-radius: 50%;
    }

    .qty .minus {
        cursor: pointer;
        display: inline-block;
        vertical-align: top;
        color: white;
        width: 20px;
        height: 20px;
        font: 20px/1 Arial, sans-serif;
        text-align: center;
        border-radius: 50%;
        background-clip: padding-box;
    }

    .minus:hover {
        background-color: #717fe0 !important;
    }

    .plus:hover {
        background-color: #717fe0 !important;
    }

    /*Prevent text selection*/
    span {
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
    }

    input {
        border: 0;
        width: 2%;
    }

    nput::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input:disabled {
        background-color: white;
    }
</style>
<!-- Top Bar End -->

<!-- ==================
     PAGE CONTENT START
     ================== -->

<div class="page-content-wrapper">

    <div class="container-fluid">
        <div class="row">
            @if (count($carts) == 0)
                <div class="col-lg-12">
                    <div class="card m-b-20">
                        <div class="card-body">
                            <h6 class="text-center">Sorry No Results Found</h6>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-lg-7">
                    <div class="card m-b-20">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-3">
                                    <p style="color:black">Name</p>
                                </div>
                                <div class="col-lg-3">
                                    <p style="color:black">Qty</p>
                                </div>
                                <div class="col-lg-3">
                                    <p style="text-align: right;color:black">Price (1 Qty)</p>
                                </div>
                                <div class="col-lg-3">
                                    <p style="text-align: right;color:black">Delete</p>
                                </div>
                            </div>
                            <div class="row">
                                @foreach ($carts as $cart)
                                    <div class="col-lg-3">
                                        <p>{{ $cart->deliveryOrderItems->name }}</p>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="qty">
                                            <span class="minus bg-dark"
                                                onclick="changeQty('minus',{{ $cart->idcart }})">-</span>
                                            <input type="number" class="count" name="qty"
                                                id="qty-{{ $cart->idcart }}" value="{{ $cart->qty }}">
                                            <span class="plus bg-dark"
                                                onclick="changeQty('plus',{{ $cart->idcart }})">+</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <p style="text-align: right">
                                            {{ number_format($cart->deliveryOrderItems->item_price, 2) }}</p>
                                    </div>
                                    <div class="col-lg-3">
                                        <button class="btn btn-danger btn-sm pull-right"
                                            onclick="deleteCartrecord({{ $cart->idcart }})"><i
                                                class="fa fa-trash"></i></button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card m-b-20">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="example-text-input" class="col-form-label">Name</label>
                                <input type="text" class="form-control" name="name" id="name"
                                    placeholder="name" value="{{ $name }}" />
                                <span id="nameError" class="text-danger"></span>

                            </div>
                            <div class="form-group">
                                <label for="example-text-input" class="col-form-label">Address</label>
                                <input type="text" class="form-control" name="address" id="address"
                                    placeholder="Address" value="{{ $address }}" />
                                <span id="addressError" class="text-danger"></span>

                            </div>
                            <div class="form-group">
                                <label for="example-text-input" class="col-form-label">Payment Type</label>
                                <select class="select2" id="paymentType">
                                    <option disabled selected>Payment Type</option>
                                    <option value="Online">Online</option>
                                    <option value="Card on Delivery">Card on Delivery</option>
                                    <option value="Cash On delivery">Cash On delivery</option>
                                </select>
                                <span id="paymentTypeError" class="text-danger"></span>

                            </div>
                            <div class="row">
                                <div class="col-lg-6" style="line-height: 10px">
                                    <p>Item Cost (Rs)</p>
                                </div>
                                <div class="col-lg-6" style="line-height: 10px">
                                    <p style="text-align:right" id="itemCost"></p>
                                </div>
                                <div class="col-lg-6" style="line-height: 10px">
                                    <p>Delivery Charge (Rs)</p>
                                </div>
                                <div class="col-lg-6" style="line-height: 10px">
                                    <p style="text-align:right;">200.00</p>
                                </div>
                                <div class="col-lg-12">
                                    <hr />
                                </div>

                                <div class="col-lg-6" style="line-height: 10px">
                                    <p style="font-weight:bold">Total Cost (Rs)</p>

                                </div>
                                <div class="col-lg-6" style="line-height: 10px">
                                    <p style="font-weight:bold;text-align:right" id="totalCost"></p>
                                </div>


                                <div class="col-lg-12">
                                    {{-- <p>(Additional charges will be added if you cancel the order)</p> --}}
                                    <span id="qtyError" class="text-danger text-center"></span>
                                    <button class="btn btn-secondary btn-block" onclick="payBill()">Pay Now</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div><!-- container -->

    </div> <!-- Page content Wrapper -->

</div> <!-- content -->

@include('includes/footer_start')

<script type="text/javascript">
    $(document).ready(function() {
        $('form').parsley();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        setCost();
    });

    function setCost() {
        $.get('get-total-cost', {}, function(data) {
            document.getElementById('itemCost').innerHTML = data.itemPrice;
            document.getElementById('totalCost').innerHTML = data.total;
        })
    }

    function changeQty(type, id) {
        $.post('change-qty', {
            type: type,
            id: id
        }, function(data) {
            $("#qty-" + id).val(data);
            setCost();
        })
    }

    function deleteCartrecord(id) {
        $.post('delete-chart-record', {
            id: id
        }, function(data) {
            location.reload();
        })
    }
    $(document).on("wheel", "input[type=number]", function(e) {
        $(this).blur();
    });

    $(document).ready(function() {
        $(document).on('focus', ':input', function() {
            $(this).attr('autocomplete', 'off');
        });
    });

    function payBill() {

        $("#qtyError").html('');
        $("#nameError").html('');
        $("#addressError").html('');
        $("#paymentTypeError").html('');

        var name = $("#name").val();
        var address = $("#address").val();
        var paymentType = $("#paymentType").val();

        $.post('pay-delivery-order', {
            name: name,
            address: address,
            paymentType: paymentType
        }, function(data) {
            if (data.qty_error) {
                var p = document.getElementById('qtyError');
                p.innerHTML = data.qty_error;
            }
            if (data.errors) {
                if (data.errors.name) {
                    var p = document.getElementById('nameError');
                    p.innerHTML = data.errors.name[0];
                }
                if (data.errors.address) {
                    var p = document.getElementById('addressError');
                    p.innerHTML = data.errors.address[0];
                }
                if (data.errors.paymentType) {
                    var p = document.getElementById('paymentTypeError');
                    p.innerHTML = data.errors.paymentType[0];
                }
            }

            if (data.success) {
                notify({
                    type: "success", //alert | success | error | warning | info
                    title: 'ORDER SAVED',
                    autoHide: true, //true | false
                    delay: 2500, //number ms
                    position: {
                        x: "right",
                        y: "top"
                    },
                    icon: '<img src="{{ URL::asset('assets/images/correct.png') }}" />',
                    message: data.success,
                });
                setTimeout(function() {
                    window.location.href = "index";
                }, 100);
            }

        })
    }
</script>


@include('includes/footer_end')
