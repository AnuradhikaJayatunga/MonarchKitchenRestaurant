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
            @if ($order !== null)
                <div class="col-lg-4">
                    <div class="card">
                        <img class="card-img-top" src="/assets/images/orders/{{ $order->image }}" alt="Card image cap"
                            height="250">
                        <input type="hidden" id="availableQty" value="{{ $order->qty }}" />
                        <input type="hidden" id="orderPrice" value="{{ number_format($order->price, 2) }}" />
                        <input type="hidden" id="cateringItemId" value="{{ $order->idcatering_order_items }}" />
                        <div class="card-body">
                            <h4 style="color:black">{{ $order->name }}</h4>
                            <div class="row">
                                <div class="col-lg-6">

                                    <p style="color:black"><b>Rs: {{ number_format($order->price, 2) }}</b></p>
                                </div>
                                {{-- <div class="col-lg-6">
                                    <p style="color:black"><b>Quantity:
                                            {{ number_format($order->qty, 2) }}</b></p>
                                </div> --}}
                                <div class="col-lg-12">
                                    <p style="color:black"> {{ $order->description }}</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Quantity<span class="text-danger"> *</span></label>
                                        <input type="number" class="form-control" name="noOfPersons" id="noOfPersons"
                                            min="0" oninput="this.value = Math.abs(this.value)"
                                            onkeyup="selectMaxQty(this.value)" placeholder="Quantity" />
                                        <span class="text-danger" id="noOfPersonsError"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Date<span class="text-danger"> *</span></label>
                                        <input type="date" class="form-control" name="date" id="date" />
                                        <span class="text-danger" id="dateError"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Time<span class="text-danger"> *</span></label>
                                        <input type="time" class="form-control" name="time" id="time" />
                                        <span class="text-danger" id="timError"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <span id="error" class="text-danger"></span>
                                    <div class="form-group">
                                        <button class="btn btn-primary" type="button" onclick="payNow()">
                                            Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            @endif
        </div>
    </div>
</div>

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
    });
    $(document).on("wheel", "input[type=number]", function(e) {
        $(this).blur();
    });
    $(document).ready(function() {
        $(document).on('focus', ':input', function() {
            $(this).attr('autocomplete', 'off');
        });
    });

    function selectMaxQty(value) {
        let noOfPersons = parseFloat($('#noOfPersons').val());
        if (noOfPersons > 150) {
            $("#noOfPersons").val(150);
        }
    }

    function payNow() {
        $("#noOfPersonsError").html('');
        $("#error").html('');
        $("#dateError").html('');
        $("#timError").html('');

        var noOfPersons = $("#noOfPersons").val();
        var extraItem = $("#extraItem").val();
        var date = $("#date").val();
        var time = $("#time").val();
        var cateringItemId = $("#cateringItemId").val();
        var extraItem = $("#extraItem").val();

        $.post('pay-catering-order', {
            noOfPersons: noOfPersons,
            extraItem: extraItem,
            date: date,
            time: time,
            cateringItemId: cateringItemId,
            extraItem: extraItem
        }, function(data) {
            if (data.error) {
                var p = document.getElementById('error');
                p.innerHTML = data.error;
            }
            if (data.errors != null) {
                if (data.errors.noOfPersons) {
                    var p = document.getElementById('noOfPersonsError');
                    p.innerHTML = data.errors.noOfPersons[0];
                }
                if (data.errors.date) {
                    var p = document.getElementById('dateError');
                    p.innerHTML = data.errors.date[0];
                }
                if (data.errors.time) {
                    var p = document.getElementById('timError');
                    p.innerHTML = data.errors.time[0];
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
                    window.location.href = 'catering-orders';
                }, 200);
            }
        })
    }
</script>


@include('includes/footer_end')
