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
                            <table id="datatable" class="table table-striped table-bordered data-table" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>GRN No</th>
                                        <th>Supplier</th>
                                        <th style="text-align: right">Total</th>
                                        <th style="text-align: right">Paid</th>
                                        <th>User</th>
                                        <th>Date</th>
                                        <th>Options</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @if (count($grnHistories) != 0)

                                        @foreach ($grnHistories as $grnHistory)
                                            <tr>
                                                <td>{{ str_pad($grnHistory->idmaster_grn, 5, '0', STR_PAD_LEFT) }}</td>
                                                <td>{{ $grnHistory->Supplier->company_name }}</td>
                                                <td style="text-align: right">{{ number_format($grnHistory->total, 2) }}
                                                </td>
                                                <td style="text-align: right">{{ number_format($grnHistory->paid, 2) }}
                                                </td>
                                                <td>{{ $grnHistory->User->first_name }}</td>
                                                <td>{{ $grnHistory->date }}</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button
                                                            class="btn btn-success waves-effect btn-sm dropdown-toggle"
                                                            type="button" id="dropdownMenuButton"
                                                            data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            Option
                                                        </button>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            <a href="#" class="dropdown-item" data-toggle="modal"
                                                                data-id="{{ $grnHistory->idmaster_grn }}" id="grnId"
                                                                data-target="#viewGrn">View Items</i>
                                                            </a>
                                                            <a href="#" class="dropdown-item" data-toggle="modal"
                                                                data-id="{{ $grnHistory->idmaster_grn }}" id="masterId"
                                                                data-target="#viewMore">More</i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
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

<!--view more-->
<div class="modal fade" id="viewMore" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">More</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">

                        <div class="table-rep-plugin">
                            <div class="table-responsive b-0" data-pattern="priority-columns">
                                <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                                    <tbody id="viewMoreArea">

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

<!--view irems-->
<div class="modal fade" id="viewGrn" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">View Products</h5>
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
                                            <TH style="text-align: right">BUYING PRICE</TH>
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
        $(".select2").val('').trigger('change');
    });

    function adMethod(dataID, tableName) {

        $.post('activateDeactivate', {
            id: dataID,
            table: tableName
        }, function(data) {

        });
    }
    $(document).on("wheel", "input[type=number]", function(e) {
        $(this).blur();
    });

    $(document).on('click', '#grnId', function() {
        var grnId = $(this).data("id");
        $.post('getGrnByID', {
            grnId: grnId
        }, function(data) {
            $('#viewItem').html(data.tableData);
        });
    });


    $(document).on('click', '#masterId', function() {
        var masterId = $(this).data("id");

        $.post('getMoreByGrnID', {
            masterId: masterId
        }, function(data) {
            $('#viewMoreArea').html(data.tableData);
        });
    });
    $(document).on('focus', ':input', function() {
        $(this).attr('autocomplete', 'off');
    });
</script>


@include('includes.footer_end')
