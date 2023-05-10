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
                                        <th>Quantity</th>
                                        <th>Price</th>
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
                    <div class="col-lg-4 ">
                        <button type="button" class="btn btn-primary waves-effect " onclick="saveSupplier()">
                            Save Supplier</button>
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
</script>
@include('includes.footer_end')
