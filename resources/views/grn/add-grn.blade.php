@include('includes.header_start')
@include('includes.header_end')

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
        <div class="col-md-12">
            <div class="card m-b-20">
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-danger alert-dismissible " id="errorAlert" style="display:none">

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <input type="hidden" id="poId" name="poId" />
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Select PO No</label>
                                <select class="form-control select2 tab" onchange="getPODetail(this.value);"
                                    name="PoNoId" id="PoNoId" required>
                                    <option value="" disabled selected>Select PO No
                                    </option>
                                    @if (isset($purchaseOrders))
                                        @foreach ($purchaseOrders as $purchaseOrder)
                                            <option value="{{ "$purchaseOrder->idpurchase_order" }}">
                                                {{ str_pad($purchaseOrder->idpurchase_order, 5, '0', STR_PAD_LEFT) }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>PO No</label>
                                <input type="number" class="form-control" name="poNo" id="poNo" min="0"
                                    oninput="this.value = Math.abs(this.value)" readonly placeholder="PO No" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="supplier">Supplier</label>
                                <select class="form-control" name="supplier" disabled id="supplier" required>
                                    <option disabled value="" selected> Supplier
                                    </option>
                                    @if (isset($suppliers))
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ "$supplier->idsupplier" }}">
                                                {{ "$supplier->company_name" }}</option>
                                        @endforeach
                                    @endif

                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card m-b-20">
                <div class="card-body">

                    <div class="table-rep-plugin">
                        <div class="table-responsive b-0" data-pattern="priority-columns">
                            <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>PRODUCT NAME</th>
                                        <th>QTY</th>
                                        <th style="text-align: right;">BUYING PRICE</th>
                                        <th style="text-align: right;">TOTAL PRICE</th>
                                        <th style="text-align: center">EDIT</th>
                                    </tr>
                                </thead>
                                <tbody id="grnBody">
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-lg-10"></div>
                        <div class="col-lg-2">
                            <button type="button" class="btn btn-primary waves-effect float-right"
                                onclick="showSaveModal()" id="saveGRNButton">
                                Make a Payment</button>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



{{-- save GRN moal --}}
<div class="modal fade" id="saveGrn" tabindex="-1" role="dialog" style="overflow-y:scroll;"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Save GRN</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="_token" value="{{ Session::token() }}">
                <input type="hidden" name="hiddenGrnID" id="hiddenGrnID">
                <div class="row">

                    <div class="col-md-12">
                        <div class="alert alert-danger alert-dismissible " id="errorAlert2" style="display:none">

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="total">Sub Total</label>
                            <input class="form-control" type="number" min="0" readonly
                                oninput="this.value = Math.abs(this.value);countNet()" onchange="countNet()"
                                id="total" name="total">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="discount">Discount</label>
                            <div class="input-group">
                                <input class="form-control" type="number" min="0"
                                    oninput="this.value = Math.abs(this.value);countNet()" onchange="countNet()"
                                    id="discount" name="discount">
                                <div class="input-group-append">
                                    <select class="form-control select2 doNotClear" name="discountType"
                                        onchange="$('#discount').val('0');countNet();" id="discountType" required>
                                        <option value="1" selected>% &nbsp;</option>
                                        <option value="2">Rs. &nbsp;</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="net">Net Total</label>
                            <input class="form-control" min="0" readonly type="number" id="net"
                                name="net">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label>Payment Type</label>
                        <select class="form-control" name="payment" onchange="paymentTypeChanged(this)"
                            id="payment" required>
                            <option disabled value="" selected>Payment Type
                            </option>
                            @if (isset($paymentTypes))
                                @foreach ($paymentTypes as $paymentType)
                                    <option value="{{ "$paymentType->idmeta_payment" }}">{{ $paymentType->name }}
                                    </option>
                                @endforeach
                            @endif

                        </select>
                    </div>
                </div>
                <br>
                <div style="display: none" id="paidDueDiv">
                    <div class="row col-md-12">
                        <h6>CASH PAYMENTS</h6>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Paid Amount</label>
                            <div class="input-group mb-2 ">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">Rs.</div>
                                </div>
                                <input class="form-control" type="number" placeholder="0.00"
                                    id="paid" name="paid">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-md btn-primary waves-effect float-right" onclick="saveGrn()">
                    Save GRN</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="updateItemModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Edit Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger alert-dismissible " id="errorAlert1" style="display:none">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>

                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="example-text-input" class="col-form-label">Qty<span style="color: red">
                            *</span></label>

                    <input type="number" class="form-control" name="uQty" id="uQty" required
                        placeholder="0.00" />
                    <small class="text-danger">{{ $errors->first('uQty') }}</small>
                </div>
                <div class="form-group">
                    <label for="example-text-input" class="col-form-label">Buying Price<span style="color: red">
                            *</span></label>

                    <input type="number" class="form-control" name="uBp" id="uBp" required
                        placeholder="0.00" />
                    <small class="text-danger">{{ $errors->first('bp') }}</small>
                </div>
                <button type="submit" class="btn btn-md btn-warning waves-effect " onclick="updateProduct()">
                    Update Product</button>
            </div>
            <input type="hidden" id="hiddenProId">
        </div>
    </div>
</div>
@include('includes.footer_start')
<script type="text/javascript">
    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        jQuery('.datepicker-autoclose').datepicker({
            autoclose: true,
            todayHighlight: true
        });
        showGrnTempTable();

    });


    function getPODetail(POId) {
        $.post('getPOByDetails', {
            POId: POId
        }, function(data) {

            showGrnTempTable();

        })

    }

    function updateProduct() {

        $('#errorAlert1').hide();
        $('#errorAlert1').html("");

        var uQty = $("#uQty").val();
        var uBp = $("#uBp").val();
        var hiddenProId = $("#hiddenProId").val();

        $.post('updateTempProduct', {
            uQty: uQty,
            uBp: uBp,
            hiddenProId: hiddenProId,
        }, function(data) {



            if (data.errors != null) {
                $('#errorAlert1').show();
                $.each(data.errors, function(key, value) {
                    $('#errorAlert1').append('<p>' + value + '</p>');
                });
            }
            if (data.success != null) {
                notify({
                    type: "success", //alert | success | error | warning | info
                    title: 'PRODUCT UPDATED',
                    autoHide: true, //true | false
                    delay: 2500, //number ms
                    position: {
                        x: "right",
                        y: "top"
                    },
                    icon: '<img src="{{ URL::asset('assets/images/correct.png') }}" />',

                    message: data.success,
                });
                $('input').val('');
                setTimeout(function() {
                    $('#updateItemModal').modal('hide');
                }, 200);
            }
            showGrnTempTable();
        });

    }


    $(document).on('click', '#editGrn', function() {
        var productId = $(this).data("id");

        $.post('getTempProductId', {
            productId: productId
        }, function(data) {
            $("#hiddenProId").val(data.idgrn_temp);
            $("#uQty").val(data.qty);
            $("#uBp").val(data.buying_price);
        });
    });

    $(document).on("wheel", "input[type=number]", function(e) {
        $(this).blur();
    });

    $('.modal').on('hidden.bs.modal', function() {

        $('#errorAlert').hide();
        $('#errorAlert').html('');
        $('#errorAlert1').hide();
        $('#errorAlert1').html('');
        $('#errorAlert2').hide();
        $('#errorAlert2').html('');
        $("#paid").val('');
        $("#grnNo").val('');
        $("#invNo").val('');

    });

    function getProduct(categoryId) {

        $.post('getProducts', {
            categoryId: categoryId,
        }, function(data) {
            $("#bPrice").val('');
            $("#item").html(data);

        });
    }

    function getUProduct(categoryId) {

        $.post('getProducts', {
            categoryId: categoryId,
        }, function(data) {
            $("#uItem").html(data);
        });
    }



    function showGrnTempTable() {
        if (!$('#defaultChecked2').prop('checked')) {
            $('#defaultChecked2').click().prop('checked', true);
        }
        $.ajax({

            type: 'post',

            url: 'getTempTableData',

            data: {},

            success: function(data) {

                if (data.total > 0) {
                    $("#total").val(data.total.toFixed(2));
                    $('#saveGRNButton').show();
                    $("#supplier").val(data.getPO.supplier_idsupplier).trigger('change');
                    $("#poNo").val(data.getPO.idpurchase_order);
                    $("#poId").val(data.getPO.idpurchase_order)

                } else {
                    $('#saveGRNButton').hide();
                }

                $('#grnBody').html(data.tableData);
            }

        });
    }

    function getProductPrice(productId) {
        $.post('getProductById', {
            productId: productId,
        }, function(data) {
            if (data != 0) {
                $("#bPrice").val(data.buying_price.toFixed(2));
            }
        })
    }

    function addItem() {

        $('#errorAlert').hide();
        $('#errorAlert').html("");

        var category = $("#category").val();
        var item = $("#item").val();
        var qtyGrn = $("#qtyGrn").val();

        $.post('saveTempProduct', {
            category: category,
            item: item,
            qtyGrn: qtyGrn,


        }, function(data) {

            if (data.errors != null) {
                $('#errorAlert').show();
                $.each(data.errors, function(key, value) {
                    $('#errorAlert').append('<p>' + value + '</p>');
                });
            }
            if (data.success != null) {

                notify({
                    type: "success", //alert | success | error | warning | info
                    title: 'ADDED TO TABLE',
                    autoHide: true, //true | false
                    delay: 2500, //number ms
                    position: {
                        x: "right",
                        y: "top"
                    },
                    icon: '<img src="{{ URL::asset('assets/images/correct.png') }}" />',

                    message: data.success,
                });

                $('input').val('');
                $(".select2").val('').trigger('change');
            }
            showGrnTempTable();
        });
    }

    $(document).on('click', '#deleteGrn', function() {
        var dGrnId = $(this).data("id");
        $.post('deleteGrnTemp', {
            dGrnId: dGrnId
        }, function(data) {
            notify({
                type: "success", //alert | success | error | warning | info
                title: 'PRODUCT DELETED',
                autoHide: true, //true | false
                delay: 2500, //number ms
                position: {
                    x: "right",
                    y: "top"
                },
                icon: '<img src="{{ URL::asset('assets/images/correct.png') }}" />',

                message: data.success,
            });
            showGrnTempTable();
        });
    });

    function showSaveModal() {

        $('#discount').val(0);

        $('#paid').val('');
        $('#discountType').val('1').trigger('change');
        $('#payment').val('1').trigger('change');
        $('#saveGrn').modal('show');
    }


    function paymentTypeChanged(el) {
        let payment = $(el).val();
        $('#paid').val('');
        $.ajax({
            type: 'POST',

            url: 'paymentTypeChangedDelete',

            data: {},

            success: function() {
                loadBankPaymentTemp();
            }
        });

        if (payment == 1) {
            $('#paidDueDiv').show();
        }
    }

    function saveGrn() {

        $('#errorAlert2').hide();
        $('#errorAlert2').html("");

        var supplier = $("#supplier").val();
        var invNo = $("#invNo").val();
        var paymentType = $("#payment").val();
        var paid = $("#paid").val();
        var bankId = $('#bankName').val();
        var bankAccount = $('#bankAccount').val();
        var bankAccountNo = $('#bankAccountNo').val();
        let discountType = $("#discountType").val();
        var discount = $("#discount").val();
        var visaBill = $("#visaBill").val();
        var cardAmount = $("#cardAmount").val();
        var poNo = $("#poId").val();


        if (discountType == 1) {
            var discount = parseFloat(discount / 100);
        } else {
            var discount = $("#discount").val();
        }

        $.post('saveGrn', {

            supplier: supplier,
            discount: discount,
            paymentType: paymentType,
            paid: paid,
            bankId: bankId,
            bankAccount: bankAccount,
            bankAccountNo: bankAccountNo,
            discountType: discountType,
            invNo: invNo,
            visaBill: visaBill,
            cardAmount: cardAmount,
            poNo: poNo,
        }, function(data) {

            console.log(data)

            if (data.errors != null) {
                $('#errorAlert2').show();
                $('#errorAlert2').show().scrollTop(0);
                $.each(data.errors, function(key, value) {
                    $('#errorAlert2').append('<p>' + value + '</p>');
                });
            }
            if (data.success != null) {

                notify({
                    type: "success", //alert | success | error | warning | info
                    title: 'GRN SAVED',
                    autoHide: true, //true | false
                    delay: 2500, //number ms
                    position: {
                        x: "right",
                        y: "top"
                    },
                    icon: '<img src="{{ URL::asset('assets/images/correct.png') }}" />',

                    message: data.success,
                });
                $('input').val('');
                $(".supplier").val('').trigger('change');
                $('input').val('');

                $('#saveGrn').modal('hide');

                showGrnTempTable();
            }

        })

    }

    function countNet() {

        let discoutInput = parseFloat($('#discount').val());
        let total = parseFloat($('#total').val());
        let discountType = $("#discountType").val();
        if (discountType == 1) {
            discount = parseFloat(total * (discoutInput / 100));
            if (discoutInput > 100) {
                $('#discount').val(100);
            }

            let NewdiscountInput = parseFloat($('#discount').val());
            let Newtotal = parseFloat($('#total').val());
            Newdiscount = parseFloat(Newtotal * (NewdiscountInput / 100));
            if (Newdiscount != null) {
                $('#net').val(parseFloat(Newtotal - Newdiscount).toFixed(2));
            }

        }
        if (discountType == 2) {
            discount = discoutInput;
            if (discount > total) {
                $('#discount').val(parseFloat(total));
            }

            let Newdiscount = parseFloat($('#discount').val());
            let Newtotal = parseFloat($('#total').val());
            if (Newtotal != null) {
                $('#net').val(parseFloat(Newtotal - Newdiscount).toFixed(2));
            }

        }


    }


    $(document).ready(function() {

        $(document).on('focus', ':input', function() {
            $(this).attr('autocomplete', 'off');
        });
    });
</script>


@include('includes.footer_end')
