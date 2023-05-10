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
        <h3 class="page-title">{{  $title }}</h3>
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
                        <div class="col-lg-4 form-group">
                            <button type="button" class="btn btn-primary waves-effect float-right" data-toggle="modal"
                                data-target="#addPackage">
                                Add Package</button>
                        </div>
                    </div>


                    <div class="table-rep-plugin">
                        <div class="table-responsive b-0" data-pattern="priority-columns">
                            <table id="datatable" class="table table-striped table-bordered" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>Package Name</th>
                                        <th>Status</th>
                                        <th>Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($packages))
                                        @if (count($packages) > 0)

                                            @foreach ($packages as $package)
                                                <tr>
                                                    <td>{{ $package->name }}</td>
                                                    @if ($package->status == 1)
                                                        <td>
                                                            <p>
                                                                <input type="checkbox"
                                                                    onchange="adMethod('{{ $package->idpackage }}','package')"
                                                                    id="{{ 'c' . $package->idpackage }}" checked
                                                                    switch="none" />
                                                                <label for="{{ 'c' . $package->idpackage }}"
                                                                    data-on-label="On" data-off-label="Off"></label>
                                                            </p>
                                                        </td>
                                                    @else
                                                        <td>
                                                            <p>
                                                                <input type="checkbox"
                                                                    onchange="adMethod('{{ $package->idpackage }}','package')"
                                                                    id="{{ 'c' . $package->idpackage }}"
                                                                    switch="none" />
                                                                <label for="{{ 'c' . $package->idpackage }}"
                                                                    data-on-label="On" data-off-label="Off"></label>
                                                            </p>
                                                        </td>
                                                    @endif
                                                    <td>

                                                        <p>
                                                            <button type="button"
                                                                class="btn btn-sm btn-warning  waves-effect waves-light"
                                                                data-toggle="modal" data-id="{{ $package->idpackage }}"
                                                                id="uPackageId" data-target="#updatePackage"><i
                                                                    class="fa fa-edit"></i>
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
<div class="modal fade" id="addPackage" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Add Package</h5>
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
                    <div class="col-lg-12">
                        <label for="example-text-input" class="col-form-label">Name<span style="color: red">
                                *</span></label>
                        <input type="text" class="form-control" name="name" id="name" required
                            placeholder="Package Name" />
                    </div>

                </div>
                <div class="row pt-3">
                    <div class="col-lg-4 ">
                        <button type="button" class="btn btn-primary waves-effect " onclick="savePackage()">
                            Save Package</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<!--update supplier-->
<div class="modal fade" id="updatePackage" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Edit Package</h5>
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
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="example-text-input" class="col-form-label">Name<span style="color: red">
                                    *</span></label>
                            <input type="text" class="form-control" name="uName" id="uName" required
                                placeholder="Package Name" />
                        </div>
                    </div>

                </div>
                <input id="hiddenPackageId" type="hidden">
                <div class="row">
                    <div class="col-lg-4">
                        <button type="submit" class="btn btn-warning waves-effect " onclick="updatePackage()">
                            Update Package</button>

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

    function savePackage() {

        $('#errorAlert').hide();
        $('#errorAlert').html("");

        var name = $("#name").val();

        $.post('savePackage', {
            name: name,

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
                    title: 'PACKAGE SAVED',
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
                    $('#addPackage').modal('hide');
                }, 200);
                location.reload();
            }

        })
    }



    $(document).on('click', '#uPackageId', function() {

        var id = $(this).data("id");

        $.post('getPackageById', {
            id: id
        }, function(data) {
            $("#hiddenPackageId").val(data.idpackage);
            $("#uName").val(data.name);
        });
    });

    function updatePackage() {

        $('#errorAlert1').hide();
        $('#errorAlert1').html("");

        var uName = $("#uName").val();
        var hiddenPackageId = $("#hiddenPackageId").val();

        $.post('updatePackage', {
            uName: uName,
            hiddenPackageId: hiddenPackageId
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
                    title: 'PACKAGE UPDATED',
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
                    $('#updatePackage').modal('hide');
                }, 200);
                location.reload();
            }

        })
    }
</script>
@include('includes.footer_end')
