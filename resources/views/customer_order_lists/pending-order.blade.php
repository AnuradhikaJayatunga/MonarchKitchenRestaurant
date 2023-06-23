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

        <div class="col-lg-12">
            <div class="card m-b-20">
                <div class="card-body">
                    <div class="table-rep-plugin">
                        <div class="table-responsive b-0" data-pattern="priority-columns">
                            <table id="datatable" class="table table-striped table-bordered" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Address</th>
                                        <th>Type</th>
                                        <th>Items</th>
                                        <th>Total Cost</th>
                                        {{-- <th>Addtional Cost</th> --}}
                                        <th>No of Persons</th>
                                        <th>Date</th>
                                        <th>Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($orders))
                                        @if (count($orders) > 0)

                                            @foreach ($orders as $order)
                                                <tr>
                                                    <td>{{ $order->name }}</td>
                                                    <td>{{ $order->address }}</td>
                                                    <td>{{ $order->type }}</td>

                                                    <td><button class="btn btn-primary btn-sm" data-toggle="modal"
                                                            data-id="{{ $order->idorder }}" id="orderId"
                                                            data-target="#viewItems">View Item</button></td>
                                                    <td>{{ number_format($order->total_cost, 2) }}</td>
                                                    {{-- <td>{{ $order->additional_cost }}</td> --}}
                                                    <td>{{ $order->no_of_persons }}</td>
                                                    <td>{{ $order->date }}</td>
                                                    <td>
                                                        @if ($order->type == 'Delivery Order' || $order->type == 'Reservation Order')
                                                            -
                                                        @else
                                                            <div class="dropdown">
                                                                <button
                                                                    class="btn btn-success waves-effect btn-sm dropdown-toggle"
                                                                    type="button" id="dropdownMenuButton"
                                                                    data-toggle="dropdown" aria-haspopup="true"
                                                                    aria-expanded="false">
                                                                    Option
                                                                </button>

                                                                <div class="dropdown-menu"
                                                                    aria-labelledby="dropdownMenuButton">
                                                                    @if ($order->type == 'Catering Order')
                                                                        <a href="#" class="dropdown-item"
                                                                            data-toggle="modal"
                                                                            data-id="{{ $order->idorder }}"
                                                                            id="updateCatering"
                                                                            data-target="#cateringEdit">Edit</i>
                                                                    @endif

                                                                    </a>

                                                                    </a>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endif
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="viewItems" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">View Items</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">

                        <div class="table-rep-plugin">
                            <div class="table-responsive b-0" data-pattern="priority-columns">
                                <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>PRODUCT</th>
                                            <th>QTY</th>
                                        </tr>
                                    </thead>
                                    <tbody id="viewItem">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- catering edit --}}
<div class="modal fade" id="cateringEdit" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Catering Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>No of Persons<span class="text-danger"> *</span></label>
                            <input type="number" class="form-control" name="noOfPersons" id="noOfPersons"
                                min="0" oninput="this.value = Math.abs(this.value)"
                                onkeyup="selectMaxQty(this.value)" placeholder="No of Persons" />
                            <span class="text-danger" id="noOfPersonsError"></span>
                        </div>
                    </div>
                    {{-- <div class="col-lg-6">
                        <div class="form-group">
                            <label>Extra Items</label>
                            <select class="form-control select2 tab" name="extraItem" id="extraItem">
                                <option value="" disabled selected>Extra Items
                                </option>
                                <option value="extra cheese">Extra Cheese</option>
                                <option value="Tomato Sauce">Tomato Sauce</option>
                                <option value="Chili Flakes">Chili Flakes</option>
                                <option value="Kochchi">Kochchi</option>
                                <option value="Onion">Onion</option>
                            </select>
                        </div>
                    </div> --}}
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Date<span class="text-danger"> *</span></label>
                            <input type="date" class="form-control" name="date" id="date" />
                            <span class="text-danger" id="dateError"></span>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Time<span class="text-danger"> *</span></label>
                            <input type="time" class="form-control" name="time" id="time" />
                            <span class="text-danger" id="timError"></span>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Address<span class="text-danger"> *</span></label>
                            <input type="text" class="form-control" name="address" id="time" />
                            <span class="text-danger" id="timError"></span>
                        </div>
                    </div>
                </div>
                <input type="hidden" class="form-control" name="hiddenCateringOrderId"
                    id="hiddenCateringOrderId" />

                <div class="row">
                    <div class="col-lg-4">
                        <span id="error" class="text-danger"></span>
                        <div class="form-group">
                            <span class="text-danger" id=commonError"></span>

                            <button class="btn btn-primary" type="button" onclick="editCatering()">
                                Catering Edit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- reservation edit --}}
<div class="modal fade" id="reservationEdit" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Reservation Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                </button>
            </div>
            <div class="modal-body">

            </div>
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

    function selectMaxQty(value) {
        let noOfPersons = parseFloat($('#noOfPersons').val());
        if (noOfPersons > 150) {
            $("#noOfPersons").val(150);
        }
    }

    $(document).on('click', '#orderId', function() {
        var id = $(this).data("id");
        $.post('view-order-items', {
            id: id
        }, function(data) {
            $('#viewItem').html(data.data);
        });
    });

    $(document).on('click', '#updateCatering', function() {
        var id = $(this).data("id");
        $.post('get-order-details', {
            id: id
        }, function(data) {
            $("#noOfPersons").val(data.order.no_of_persons);
            // $("#extraItem").val(data.order.extra_item).trigger('change');
            $("#date").val(data.order.date);
            $("#time").val(data.order.time);
            $("#hiddenCateringOrderId").val(data.order.idorder);
            $("#cateringItemId").val(data);
        });
    });

    function editCatering() {
        $("#noOfPersonsError").html('');
        $("#error").html('');
        $("#dateError").html('');
        $("#timError").html('');

        var noOfPersons = $("#noOfPersons").val();
        // var extraItem = $("#extraItem").val();
        var date = $("#date").val();
        var time = $("#time").val();
        var hiddenCateringOrderId = $("#hiddenCateringOrderId").val();
        var cateringItemId = $("#cateringItemId").val();


        $.post('editCatering', {
            noOfPersons: noOfPersons,
            // extraItem: extraItem,
            date: date,
            time: time,
            hiddenCateringOrderId: hiddenCateringOrderId,
            cateringItemId: cateringItemId
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
                    title: 'ORDER UPDATED',
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
                    location.reload();
                }, 200);
            }
        });
    }

    function cancelorder(id) {

        swal({
                title: 'Do you really want to cancel this order?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Cancel!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-md btn-outline-primary waves-effect',
                cancelButtonClass: 'btn btn-md btn-outline-danger waves-effect',
                buttonsStyling: false
            }).then(function() {
                $.ajax({

                    type: 'POST',

                    url: " {{ route('cancel-order') }}",

                    data: {
                        id: id
                    },

                    success: function(data) {

                        notify({
                            type: "success", //alert | success | error | warning | info
                            title: 'ORDER APPROVED',
                            autoHide: true, //true | false
                            delay: 2500, //number ms
                            position: {
                                x: "right",
                                y: "top"
                            },
                            icon: '<img src="{{ URL::asset('assets/images/correct.png') }}" />',

                            message: data.success,
                        });
                        location.reload();
                    }
                })


            }),
            function() {

            }
    }
</script>


@include('includes/footer_end')
