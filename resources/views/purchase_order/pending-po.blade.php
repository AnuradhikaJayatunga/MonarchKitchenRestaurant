@include('includes/header_start')
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

        <div class="col-lg-12">
            <div class="card m-b-20">
                <div class="card-body">
                                        <div class="table-rep-plugin">
                        <div class="table-responsive b-0" data-pattern="priority-columns">
                            <table id="datatable"   class="table table-striped table-bordered"
                                    cellspacing="0"
                                    width="100%">
                                <thead>
                                <tr>
                                    <th>PO No</th>
                                    <th>Supplier</th>
                                    <th>Total</th>
                                    <th>Date</th>
                                    <th>Created At</th>
                                    <th>Options</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($pendingPO))
                                    @if(count($pendingPO)!=0)
                                       
                                   
                                    @foreach($pendingPO as $PO)
                                        <tr>
                                            <td>{{ str_pad($PO->idpurchase_order,5,'0',STR_PAD_LEFT) }}</td>
                                            <td>{{$PO->Supplier->company_name}}</td>
                                            <td>{{ number_format($PO->total,2) }}</td>
                                            <td>{{$PO->date}}</td>
                                         
                                            <td>{{$PO->created_at}}</td>

                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary waves-effect btn-sm dropdown-toggle" type="button"
                                                            id="dropdownMenuButton" data-toggle="dropdown"
                                                            aria-haspopup="true" aria-expanded="false">
                                                        Option
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <a href="#" class="dropdown-item" data-toggle="modal"
                                                           data-id="{{ $PO->idpurchase_order}}" onclick="completedPO({{ $PO->idpurchase_order}})"
                                                          >Approved
                                                        </a>
                                                          <a href="#" class="dropdown-item" data-toggle="modal"
                                                           data-id="{{ $PO->idpurchase_order}}" onclick="cancelPO({{ $PO->idpurchase_order}})"
                                                          >Cancel
                                                        </a>
                                                        <a href="#" class="dropdown-item" data-toggle="modal"
                                                           data-id="{{ $PO->idpurchase_order}}" id="viewItems"
                                                           data-target="#viewPOItemModal">View
                                                        </a>
                                                    </div>
                                                </div>

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



<!--view supplier-->

<div class="modal fade" id="viewPOItemModal" tabindex="-1"
     role="dialog"
     aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">View Products</h5>
                <button type="button" class="close" data-dismiss="modal"
                        aria-hidden="true">×
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">

                        <div class="table-rep-plugin">
                            <div class="table-responsive b-0" data-pattern="priority-columns">
                                <table class="table table-striped table-bordered"
                                       cellspacing="0"
                                       width="100%">
                                    <thead>
                                    <tr>
                                        <th>CATEGORY</th>
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
    $(document).ready(function () {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
    $(document).on('focus', ':input', function () {
        $(this).attr('autocomplete', 'off');
    });

    $(document).on('click','#viewItems', function () {
        var poId = $(this).data("id");
        $.post('viewPOById', {poId: poId}, function (data) {
            $("#viewItem").html(data.tableData);
        });
    });


    function cancelPO(id){
        swal({
                    title: 'Do you really want to cancel this PO?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Cancel!',
                    cancelButtonText: 'No!',
                    confirmButtonClass: 'btn btn-md btn-outline-primary waves-effect',
                    cancelButtonClass: 'btn btn-md btn-outline-danger waves-effect',
                    buttonsStyling: false
                }).then(function () {
                    $.ajax({

                        type: 'POST',

                        url: " {{ route('cancelPO') }}",

                        data: {id: id},

                        success: function (data) {

                            notify({
                                type: "success", //alert | success | error | warning | info
                                title: 'PO CANCELED',
                                autoHide: true, //true | false
                                delay: 2500, //number ms
                                position: {
                                    x: "right",
                                    y: "top"
                                },
                                icon: '<img src="{{ URL::asset('assets/images/correct.png')}}" />',

                                message: data.success,
                            });
                        location.reload();
                        }
                    })


                }), function () {

                }
    }

    function completedPO(id) {


        swal({
            title: 'Do you really want to approved this PO?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Approved!',
            cancelButtonText: 'No, cancel!',
            confirmButtonClass: 'btn btn-md btn-outline-primary waves-effect',
            cancelButtonClass: 'btn btn-md btn-outline-danger waves-effect',
            buttonsStyling: false
        }).then(function () {
            $.ajax({

                type: 'POST',

                url: " {{ route('approvedPO') }}",

                data: {id: id},

                success: function (data) {

                    notify({
                        type: "success", //alert | success | error | warning | info
                        title: 'PO APPROVED',
                        autoHide: true, //true | false
                        delay: 2500, //number ms
                        position: {
                            x: "right",
                            y: "top"
                        },
                        icon: '<img src="{{ URL::asset('assets/images/correct.png')}}" />',

                        message: data.success,
                    });
                   location.reload();
                }
            })


        }), function () {

        }

    }


</script>


@include('includes.footer_end')