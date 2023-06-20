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
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Quantity<span class="text-danger"> *</span></label>
                                    <input type="number" class="form-control" name="noOfPersons" id="noOfPersons"
                                        min="0" oninput="this.value = Math.abs(this.value)"
                                        onkeyup="selectMaxQty()" placeholder="Quantity" />
                                    <span class="text-danger" id="noOfPersonsError"></span>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Date<span class="text-danger"> *</span></label>
                                    <input type="date" class="form-control" name="date" id="date" />
                                    <span class="text-danger" id="dateError"></span>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Time<span class="text-danger"> *</span></label>
                                    <input type="time" class="form-control" name="time" id="time" />
                                    <span class="text-danger" id="timeError"></span>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Table No<span class="text-danger"> *</span></label>
                                    <select class="select2" id="tableNo">
                                        <option selected disabled>Select Table</option>
                                        <option value="1">Table 1
                                        </option>
                                        <option value="2">Table 2
                                        </option>
                                        <option value="3">Table 3
                                        </option>
                                        <option value="4">Table 4
                                        </option>
                                        <option value="5">Table 5
                                        </option>
                                    </select>
                                    <span class="text-danger" id="tableNoError"></span>
                                </div>
                            </div>
                            {{-- <div class="col-lg-4">
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
                    </div>
                </div>

            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Product<span class="text-danger"> *</span></label>
                                    <select class="select2" onchange="selectProduct(this.value)" id="productId">
                                        <option selected disabled>Select Product</option>
                                        @foreach ($items as $item)
                                            <option value="{{ $item->iddelivery_order_items }}">{{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger" id="productIdError"></span>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Price</label>
                                    <input type="text" disabled class="form-control" name="price" id="price" />
                                </div>
                            </div>
                            {{-- <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Available Qty</label>
                                    <input type="text" class="form-control" name="availableQty" id="availableQty"
                                        disabled />
                                </div>
                            </div> --}}
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Qty<span class="text-danger"> *</span></label>
                                    <input type="number" class="form-control" name="qty" id="qty" />
                                    <span class="text-danger" id="qtyError"></span>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <button class="btn btn-primary" type="button" onclick="addItem()">
                                        Add Item</button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="table-rep-plugin">
                                    <div class="table-responsive b-0" data-pattern="priority-columns">
                                        <table class="table table-striped table-bordered" cellspacing="0"
                                            width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Product Name</th>
                                                    <th>Qty</th>
                                                    <th style="text-align: right;">Price</th>
                                                    <th style="text-align: right">Delete</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tableBody">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="display:none" id="saveReservation">
                            <div class="col-lg-12">
                                <span id="commonError" class="text-danger"></span>
                                <div class="form-group">
                                    <button class="btn btn-primary" type="button" onclick="saveReservation()">
                                        Save Reservation</button>
                                        
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

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
        fetchTable();
    });
    $(document).on("wheel", "input[type=number]", function(e) {
        $(this).blur();
    });
    $(document).ready(function() {
        $(document).on('focus', ':input', function() {
            $(this).attr('autocomplete', 'off');
        });
    });

    function fetchTable() {
        $.get('get-reservation-table', {}, function(data) {
            $("#tableBody").html(data.tableData);
            if (data.total != 0) {
                document.getElementById("saveReservation").style.display = "block";
            } else {
                document.getElementById("saveReservation").style.display = "none";
            }
        })
    }

    function selectProduct(id) {
        $.post('get-product-details', {
            id: id
        }, function(data) {
            $("#price").val(data.item_price);
            $("#availableQty").val(data.qty);
        })
    }

    function selectMaxQty() {
        let noOfPersons = parseFloat($('#noOfPersons').val());
        if (noOfPersons > 50) {
            $("#noOfPersons").val(50);
        }
    }

    function addItem() {
        $("#productIdError").html('');
        $("#qtyError").html('');

        var productId = $("#productId").val();
        var qty = $("#qty").val();

        $.post('add-reservation-item', {
            productId: productId,
            qty: qty
        }, function(data) {
            if (data.errors != null) {
                if (data.errors.productId) {
                    var p = document.getElementById('productIdError');
                    p.innerHTML = data.errors.productId[0];
                }
                if (data.errors.qty) {
                    var p = document.getElementById('qtyError');
                    p.innerHTML = data.errors.qty[0];
                }
            }
            if (data.success != null) {
                $("#productIdError").html('');
                $("#qtyError").html('');
                $("#price").val('');
                $("#availableQty").val('');
                $("#qty").val('');
                $("#productId").val('').trigger('change')
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

                fetchTable();
            }
        })
    }

    function deleteItem(id) {
        $.post('delete-chart-record', {
            id: id
        }, function(data) {
            fetchTable();
        })
    }

    function saveReservation() {
        $("#commonError").html('');
        $("#noOfPersonsError").html('');
        $("#dateError").html('');
        $("#timeError").html('');
        $("#tableNoError").html('');


        var noOfPersons = $("#noOfPersons").val();
        var date = $("#date").val();
        var time = $("#time").val();
        var tableNo = $("#tableNo").val();
        var extraItem = $("#extraItem").val();

        $.post('save-reservation', {
            noOfPersons: noOfPersons,
            date: date,
            time: time,
            tableNo: tableNo,
            extraItem: extraItem
        }, function(data) {
            console.log(data);
            if (data.error) {
                var p = document.getElementById('commonError');
                p.innerHTML = data.error;
            }
            if (data.errors != null) {
                if (data.errors.noOfPersons) {
                    var p = document.getElementById('noOfPersonsError');
                    p.innerHTML = data.errors.noOfPersons[0];
                }
                if (data.errors.tableNo) {
                    var p = document.getElementById('tableNoError');
                    p.innerHTML = data.errors.tableNo[0];
                }
                if (data.errors.date) {
                    var p = document.getElementById('dateError');
                    p.innerHTML = data.errors.date[0];
                }
                if (data.errors.time) {
                    var p = document.getElementById('timeError');
                    p.innerHTML = data.errors.time[0];
                }
            }
            if (data.success != null) {
                notify({
                    type: "success", //alert | success | error | warning | info
                    title: 'RESERVATION SAVED',
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
    }
</script>


@include('includes/footer_end')
