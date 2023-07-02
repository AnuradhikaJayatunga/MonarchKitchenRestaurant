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
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Available Quantity</th>
                                        <th>Price</th>
                                        <th>Option</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($orders))
                                        @if (count($orders) > 0)

                                            @foreach ($orders as $order)
                                                <tr>
                                                    <td>
                                                        <img src="assets/images/orders/{{ $order->image }}"
                                                            width="50px" />
                                                    </td>
                                                    <td>{{ $order->name }}</td>
                                                    <td>{{ $order->qty }}</td>
                                                    <td>{{ number_format($order->item_price, 2) }}</td>
                                                    <td>
                                                    <p>
                                                            <button type="button"
                                                                class="btn btn-sm btn-warning  waves-effect waves-light"
                                                                data-toggle="modal"
                                                                data-id="{{ $order->idorder_items }}" id="uOrderItemId"
                                                                data-target="#updateItem"><i class="fa fa-edit"></i>
                                                            </button>
                                                            <button type="button"
                                                                onclick="deleteOrderItems({{ $order->idorder_items }})"
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

<!--update delivery orders list-->
<div class="modal fade" id="updatePackage" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Edit Order Items</h5>
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
                                placeholder="Item Name" />
                        </div>
                    </div>

                </div>
                <input id="iddenOrderItemId" type="hidden">
                <div class="row">
                    <div class="col-lg-4">
                        <button type="submit" class="btn btn-warning waves-effect " onclick="updateItem()">
                            Update Item</button>

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

    $(document).on('click', '#uOrderItemId', function() {

    var id = $(this).data("id");

    $.post('getOrderItemById', {
    id: id
    }, function(data) {
    $("#hiddenOrderItemId").val(data.idpackage);
    $("#uName").val(data.name);
    });
    });

    function updateItem() {

    $('#errorAlert1').hide();
    $('#errorAlert1').html("");

    var uName = $("#uName").val();
    var hiddenOrderItemId = $("#hiddenOrderItemId").val();

    $.post('updateItem', {
        uName: uName,
        hiddenOrderItemId: hiddenOrderItemId
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
            title: 'ORDER ITEM UPDATED',
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
            $('#updateItem').modal('hide');
        }, 200);
        location.reload();
    }

})
}

function deleteOrderItems(id) {
        swal({
                title: 'Do you really want to delete this Item?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Delete!',
                cancelButtonText: 'No!',
                confirmButtonClass: 'btn btn-md btn-outline-primary waves-effect',
                cancelButtonClass: 'btn btn-md btn-outline-danger waves-effect',
                buttonsStyling: false
            }).then(function() {
                $.ajax({

                    type: 'POST',

                    url: " {{ route('deleteOrderItems') }}",

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
                                title: 'ITEM DELETED',
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
