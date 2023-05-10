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
                    <div class="row">
                        <div class="col-lg-6 form-group" style="padding-top: 6px">

                        </div>
                        <div class="col-lg-2">

                        </div>
                        <div class="col-lg-4 form-group" style="padding-top: 6px">
                            <button type="button" class="btn btn-primary waves-effect float-right" data-toggle="modal"
                                data-target="#addSupplier">
                                Add Supplier</button>
                        </div>
                    </div>


                    <div class="table-rep-plugin">
                        <div class="table-responsive b-0" data-pattern="priority-columns">
                            <table id="datatable" class="table table-striped table-bordered" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>Supplier Name</th>
                                        <th>Address</th>
                                        <th>Contact No</th>
                                        <th>Email</th>
                                        <th>Bank Name</th>
                                        <th>Account No</th>
                                        <th>Status</th>
                                        <th>Options</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($suppliers))
                                        @if (count($suppliers) > 0)

                                            @foreach ($suppliers as $supplier)
                                                <tr>
                                                    <td>{{ $supplier->company_name }}</td>
                                                    <td>{{ $supplier->address }}</td>
                                                    <td>{{ $supplier->contact_no }}</td>
                                                    <td>{{ $supplier->email }}</td>
                                                    <td>{{ $supplier->bank_name }}</td>
                                                    <td>{{ $supplier->account_no }}</td>

                                                    @if ($supplier->status == 1)
                                                        <td>
                                                            <p>
                                                                <input type="checkbox"
                                                                    onchange="adMethod('{{ $supplier->idsupplier }}','supplier')"
                                                                    id="{{ 'c' . $supplier->idsupplier }}" checked
                                                                    switch="none" />
                                                                <label for="{{ 'c' . $supplier->idsupplier }}"
                                                                    data-on-label="On" data-off-label="Off"></label>
                                                            </p>
                                                        </td>
                                                    @else
                                                        <td>
                                                            <p>
                                                                <input type="checkbox"
                                                                    onchange="adMethod('{{ $supplier->idsupplier }}','supplier')"
                                                                    id="{{ 'c' . $supplier->idsupplier }}"
                                                                    switch="none" />
                                                                <label for="{{ 'c' . $supplier->idsupplier }}"
                                                                    data-on-label="On" data-off-label="Off"></label>
                                                            </p>
                                                        </td>
                                                    @endif
                                                    <td>

                                                        <p>
                                                            <button type="button"
                                                                class="btn btn-sm btn-warning  waves-effect waves-light"
                                                                data-toggle="modal"
                                                                data-id="{{ $supplier->idsupplier }}" id="uSupplierId"
                                                                data-target="#updateSupplier"><i class="fa fa-edit"></i>
                                                            </button>
                                                            <button type="button"
                                                                onclick="deleteSupplier({{ $supplier->idsupplier }})"
                                                                class="btn btn-sm btn-danger  waves-effect waves-light"><i
                                                                    class="fa fa-trash"></i>
                                                            </button>
                                                        </p>
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


<!--add supplier model-->
<div class="modal fade" id="addSupplier" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Add Supplier</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger alert-dismissible " id="errorAlert" style="display:none">

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <label for="example-text-input" class="col-form-label">Supplier Name<span style="color: red">
                                *</span></label>
                        <input type="text" class="form-control" name="supplierName" id="supplierName" required
                            placeholder="Supplier Name" />
                    </div>
                    <div class="col-lg-6">

                        <label for="example-text-input" class="col-form-label">Contact No<span style="color: red">
                                *</span></label>
                        <input type="number" class="form-control" name="contactNo1"
                            oninput="this.value = Math.abs(this.value)" id="contactNo1" required
                            placeholder="(+94) XXX XXXXXX" />

                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <label for="example-text-input" class="col-form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="email" required
                            placeholder="abc@gmail.com" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 form-group">
                        <label for="example-text-input" class="col-form-label">Address</label>
                        <input type="text" class="form-control" name="address" id="address" required
                            placeholder="Address" />
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 form-group">
                        <label for="example-text-input" class="col-form-label">Bank Name<span style="color: red">
                            *</span></label>
                        <input type="text" class="form-control" name="bankName" id="bankName" required
                            placeholder="Bank Name" />
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 form-group">
                        <label for="example-text-input" class="col-form-label">Account No<span style="color: red">
                            *</span></label>
                        <input type="text" class="form-control" name="accountNo" id="accountNo" required
                            placeholder="A/c No" />
                    </div>
                </div>
              
                <div class="row">
                    <div class="col-lg-4 "style="padding-top: 80px">
                        <button type="button" class="btn btn-primary waves-effect " onclick="saveSupplier()">
                            Save Supplier</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<!--update supplier-->
<div class="modal fade" id="updateSupplier" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Edit Supplier</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger alert-dismissible " id="errorAlert1" style="display:none">

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="example-text-input" class="col-form-label">Supplier Name<span
                                    style="color: red"> *</span></label>
                            <input type="text" class="form-control" name="uSupplierName" id="uSupplierName"
                                required placeholder="Supplier Name" />
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="example-text-input" class="col-form-label">Contact No<span
                                    style="color: red"> *</span></label>
                            <input type="text" class="form-control" name="uContactNo1"
                                oninput="this.value = Math.abs(this.value)" id="uContactNo1" required
                                placeholder="(+94) XXX XXXXXX" />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="example-text-input" class="col-form-label">Email</label>
                            <input type="email" class="form-control" name="uEmail" id="uEmail"
                                pplaceholder="abc@gmail.com" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="example-text-input" class="col-form-label">Address</label>
                            <input type="text" class="form-control" name="uAddress" id="uAddress"
                                placeholder="Address" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="example-text-input" class="col-form-label">Bank Name<span
                                style="color: red"> *</span></label>
                            <input type="text" class="form-control" name="uBankName" id="uBankName"
                                placeholder="Bank Name" />
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="example-text-input" class="col-form-label">Account No<span
                                style="color: red"> *</span></label>
                            <input type="text" class="form-control" name="uAccountNo" id="uAccountNo"
                                placeholder="Account No" />
                        </div>
                    </div>
                </div>
                <input id="hiddenSupplierId" type="hidden">
                <div class="row">
                    <div class="col-lg-4">
                        <button type="submit" class="btn btn-warning waves-effect " onclick="updateSupplier()">
                            Update Supplier</button>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@include('includes/footer_start')
<script type="text/javascript">
    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
    $(document).on('focus', ':input', function() {
        $(this).attr('autocomplete', 'off');
    });
    $('.modal').on('hidden.bs.modal', function() {
        $('#errorAlert').hide();
        $('#errorAlert').html('');
        $('#errorAlert1').hide();
        $('#errorAlert1').html('');
        $('input').val('');
    });

    function adMethod(dataID, tableName) {

        $.post('activateDeactivate', {
            id: dataID,
            table: tableName
        }, function(data) {

        });
    }


    function saveSupplier() {

        $('#errorAlert').hide();
        $('#errorAlert').html("");

        var supplierName = $("#supplierName").val();
        var contactNo1 = $("#contactNo1").val();
        var creditLimit = $("#creditLimit").val();
        var email = $("#email").val();
        var address = $("#address").val();
        var bankname = $("#bankName").val();
        var accountno = $("#accountNo").val();

        $.post('saveSupplier', {
            supplierName: supplierName,
            contactNo1: contactNo1,
            email: email,
            creditLimit: creditLimit,
            address: address,
            bankName: bankname,
            accountNo: accountno,

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
                    title: 'SUPPLIER SAVED',
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
                    $('#addSupplier').modal('hide');
                }, 200);
                location.reload();
            }

        })
    }



    $(document).on('click', '#uSupplierId', function() {

        var supplierId = $(this).data("id");

        $.post('getSupplierById', {
            supplierId: supplierId
        }, function(data) {
            $("#hiddenSupplierId").val(data.idsupplier);
            $("#uSupplierName").val(data.company_name);
            $("#uContactNo1").val(data.contact_no);
            $("#uEmail").val(data.email);
            $("#uAddress").val(data.address);
            $("#uBankName").val(data.bankName);
            $("#uAccountNo").val(data.accountNo);
        });
    });


    function updateSupplier() {

        $('#errorAlert1').hide();
        $('#errorAlert1').html("");

        var uSupplierName = $("#uSupplierName").val();
        var uContactNo1 = $("#uContactNo1").val();
        var uEmail = $("#uEmail").val();
        var uAddress = $("#uAddress").val();
        var uBankName = $("#uBankName").val();
        var uAccountNo = $("#uAccountNo").val();
        var hiddenSupplierId = $("#hiddenSupplierId").val();
        

        $.post('updateSupplier', {
            hiddenSupplierId: hiddenSupplierId,
            uSupplierName: uSupplierName,
            uContactNo1: uContactNo1,
            uEmail: uEmail,
            uAddress: uAddress,
            uBankName: uBankName,
            uAccountNo: uAccountNo,

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
                    title: 'SUPPLIER UPDATED',
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
                    $('#updateSupplier').modal('hide');
                }, 200);
                location.reload();
            }

        })
    }

    function deleteSupplier(id) {
        swal({
                title: 'Do you really want to cancel this supplier?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Cancel!',
                cancelButtonText: 'No!',
                confirmButtonClass: 'btn btn-md btn-outline-primary waves-effect',
                cancelButtonClass: 'btn btn-md btn-outline-danger waves-effect',
                buttonsStyling: false
            }).then(function() {
                $.ajax({

                    type: 'POST',

                    url: " {{ route('deleteSupplier') }}",

                    data: {
                        id: id
                    },

                    success: function(data) {
                        if (data.error) {
                            notify({
                                type: "error", //alert | success | error | warning | info
                                title: 'Sorry',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<img src="{{ URL::asset('assets/images/correct.png') }}" />',

                                message: data.error,
                            });
                        }
                        if (data.success) {
                            notify({
                                type: "success", //alert | success | error | warning | info
                                title: 'SUPPLIER DELETED',
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

                    }
                })


            }),
            function() {

            }
    }
</script>
@include('includes.footer_end')
