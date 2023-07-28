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
        <h4 class="page-title" >{{ $title }}</h4>
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
                                                            {{-- <button type="button"
                                                                class="btn btn-sm btn-warning  waves-effect waves-light"
                                                                data-toggle="modal"
                                                                data-id="{{ $order->iddelivery_order_items  }}" id="deliveryOrderItemId"
                                                                data-target="#editDeliveryItem"><i class="fa fa-edit"></i>
                                                            </button> --}}
                                                            <button type="button"
                                                                onclick="deleteOrderItems({{ $order->iddelivery_order_items}})"
                                                                class="btn btn-sm btn-danger waves-effect waves-light"><i
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

<!--update delivery item-->
<div class="modal fade" id="editDeliveryItem" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title mt-0">Edit Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                </button> 
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger alert-dismissible " id="errorAlert1" style="display:none">

                        </div>
                    </div>
                </div>
                <form id="editDeliveryOrderItem" class="form-horizontal" action="{{ route('editDeliveryItem') }}"
                        method="POST">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-lg-12">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="example-text-input" class="col-form-label">Item Name<span
                                            style="color: red">
                                            *</span></label>

                                    <input type="text" class="form-control" name="itemName" id="itemName"
                                        placeholder="Item Name" />
                                    <span id="itemNameError" class="text-danger"></span>
                                </div>
                            </div>
                         <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="example-text-input" class="col-form-label">Item Price<span
                                            style="color: red">
                                            *</span></label>
                                    <input type="number" class="form-control" name="itemPrice" id="itemPrice"
                                        placeholder="Item Price" />
                                    <span id="itemPriceError" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="example-text-input" class="col-form-label">Qty<span style="color: red">
                                            *</span></label>
                                    <input type="number" class="form-control" name="itemQty" id="itemQty"
                                        placeholder="Qty" />
                                    <span id="itemQtyError" class="text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="example-text-input" class="col-form-label">Image<span
                                            style="color: red">
                                            *</span></label>
                                    <input class="form-control form-control-lg" id="image" name='image'
                                        type="file" />
                                    <span id="imageError" class="text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <span id="ingredientError" class="text-danger"></span>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-warning waves-effect ">
                                        Edit Item</button>
                                </div>
                            </div>
                        </div>
                    </form>
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

    $("#deliveryOrderItemId").on("submit", function(event) {
        $("#itemNameError").html('');
        $("#itemPriceError").html('');
        $("#itemQtyError").html('');
        $("#imageError").html('');
        event.preventDefault();

        $.ajax({ 
            url: 'editDeliveryItem',
            type: 'POST',
            data: new FormData(this),
            dataType: 'JSON',
            contentType: false,
            cache: false, 
            processData: false,
            success: function(data) {
                if (data.ingredientError != null) {
                    if (data.ingredientError) {
                        var p = document.getElementById('ingredientError');
                        p.innerHTML = data.ingredientError;
                    }
                }
                if (data.errors != null) {
                    if (data.errors.itemName) {
                        var p = document.getElementById('itemNameError');
                        p.innerHTML = data.errors.itemName[0];
                    }
                    if (data.errors.itemPrice) {
                        var p = document.getElementById('itemPriceError');
                        p.innerHTML = data.errors.itemPrice[0];
                    }
                    if (data.errors.itemQty) {
                        var p = document.getElementById('itemQtyError');
                        p.innerHTML = data.errors.itemQty[0];
                    }
                    if (data.errors.image) {
                        var p = document.getElementById('imageError');
                        p.innerHTML = data.errors.image[0];
                    }
                }
                if (data.success != null) {
                    notify({
                        type: "success", //alert | success | error | warning | info
                        title: 'DELIVERY ORDER UPDATED',
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
                    },200);
                }
            }
        });
    });

    
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
                        console.log(data)
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
