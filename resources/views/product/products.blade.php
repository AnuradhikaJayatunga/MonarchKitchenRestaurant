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
                        <div class="col-lg-8">
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                <button type="button" class="btn btn-primary waves-effect float-right"
                                    data-toggle="modal" data-target="#addProductModal">
                                    Add Extra Item</button>

                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="table-rep-plugin">
                        <div class="table-responsive b-0" data-pattern="priority-columns">
                            <table id="datatable" class="table table-striped table-bordered" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Extra Item Name</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th>Edit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($productViews))
                                        @if (count($productViews) > 0)


                                            @foreach ($productViews as $productView)
                                                <tr>
                                                    <td>{{ $productView->Category->category_name }}</td>
                                                    <td>{{ $productView->product_name }}</td>
                                                    <td>{{ number_format($productView->buying_price, 2) }}</td>
                                                    @if ($productView->status == 1)
                                                        <td>
                                                            <p>
                                                                <input type="checkbox" class="status"
                                                                    onchange="adMethod('{{ $productView->idproduct }}','product')"
                                                                    id="{{ 'c' . $productView->idproduct }}" checked
                                                                    switch="none" />
                                                                <label for="{{ 'c' . $productView->idproduct }}"
                                                                    data-on-label="On" data-off-label="Off"></label>
                                                            </p>
                                                        </td>
                                                    @else
                                                        <td>
                                                            <p>
                                                                <input type="checkbox" class="status"
                                                                    onchange="adMethod('{{ $productView->idproduct }}','product')"
                                                                    id="{{ 'c' . $productView->idproduct }}"
                                                                    switch="none" />
                                                                <label for="{{ 'c' . $productView->idproduct }}"
                                                                    data-on-label="On" data-off-label="Off"></label>
                                                            </p>
                                                        </td>
                                                    @endif

                                                    <td>

                                                        <p>
                                                            <button type="button"
                                                                class="btn btn-sm btn-warning  waves-effect waves-light"
                                                                data-toggle="modal"
                                                                data-id="{{ $productView->idproduct }}"
                                                                data-name="{{ $productView->idproduct }}"
                                                                id="updateProduct" data-target="#updateProductModal"><i
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
    </div><!-- container -->

</div> <!-- Page content Wrapper -->

</div> <!-- content -->

<!--add modal-->
<div class="modal fade" id="addProductModal" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Add Extra Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                </button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>Category <span style="color: red"> *</span></label>
                            <select class="form-control select2 tab" name="category" id="category" required>
                                <option value="" disabled selected>Select Category
                                </option>
                                @if (isset($categories))
                                    @foreach ($categories as $category)
                                        <option value="{{ "$category->idcategory" }}">{{ $category->category_name }}
                                        </option>
                                    @endforeach
                                @endif

                            </select>
                            <span class="text-danger" id="categoryError"></span>
                        </div>

                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>Extra Item Name<span style="color: red"> *</span></label>
                            <input type="text" class="form-control" name="pName" id="pName" required
                                placeholder="Product Name" />
                            <span class="text-danger" id="pNameError"></span>
                        </div>

                    </div>

                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>Buying Price<span style="color: red"> *</span></label>
                            <input type="number" class="form-control" name="buyingPrice"
                                oninput="this.value = Math.abs(this.value)" id="buyingPrice" required
                                placeholder="0.00" />
                            <span class="text-danger" id="buyingPriceError"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <label>Description</label>
                        <textarea class="form-control" rows="3" required name="description" id="description"
                            placeholder="Write some description here...."></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4" style="padding-top: 14px">
                        <button type="button" class="btn btn-primary waves-effect " onclick="saveProduct()">
                            Save Extra Item</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!--update modal-->
<div class="modal fade" id="updateProductModal" role="dialog" aria-labelledby="mySmallModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Edit Extra Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                </button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>Category <span style="color: red"> *</span></label>
                            <select class="form-control select2 tab" name="uCategory" id="uCategory" required>
                                <option value="" disabled selected>Select Category
                                </option>
                                @if (isset($categories))
                                    @foreach ($categories as $category)
                                        <option value="{{ "$category->idcategory" }}">{{ $category->category_name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <span class="text-danger" id="uCategoryError"></span>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>Extra Item Name<span style="color: red"> *</span></label>
                            <input type="text" class="form-control" name="uPName" id="uPName" required
                                placeholder="Product Name" />
                            <span class="text-danger" id="uPNameError"></span>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>Buying Price<span style="color: red"> *</span></label>
                            <input type="number" class="form-control" name="uBuyingPrice"
                                oninput="this.value = Math.abs(this.value)" id="uBuyingPrice" required
                                placeholder="0.00" />
                            <span class="text-danger" id="uBuyingPriceError"></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <label>Description</label>
                        <textarea class="form-control" rows="3" required name="uDescription" id="uDescription"
                            placeholder="Write some description here...."></textarea>
                    </div>
                </div>
                <input type="hidden" id="hiddenUItemId">
                <div class="row">
                    <div class="col-lg-4" style="padding-top: 14px">
                        <button type="submit" class="btn btn-warning waves-effect " onclick="updateProduct()">
                            Update Extra Item</button>
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

    });
    $(document).on("wheel", "input[type=number]", function(e) {
        $(this).blur();
    });

    function adMethod(dataID, tableName) {

        $.post('activateDeactivate', {
            id: dataID,
            table: tableName
        }, function(data) {

        });
    }
    $('.modal').on('hidden.bs.modal', function() {


        $('#errorAlert').hide();
        $('#errorAlert').html('');

        $('#errorAlert1').hide();
        $('#errorAlert1').html('');

        $('#categoryError').html('');
        $('#pNameError').html('');
        $('#measurementError').html('');
        $('#minQtyError').html('');
        $('#maxQtyError').html('');
        $("#buyingPriceError").html("");

        $('#uCategoryError').html('');
        $('#uPNameError').html('');
        $('#uMeasurementError').html('');
        $('#uMinQtyError').html('');
        $('#uMaxQtyError').html('');
        $("#uBuyingPriceError").html("");

        $('input').val('');
        $(".select2").val('').trigger('change');
        $("#expHave").prop("checked", false);

    });



    function saveProduct() {

        $('#categoryError').html("");
        $('#pNameError').html("");
        $('#measurementError').html("");
        $('#minQtyError').html("");
        $('#maxQtyError').html("");
        $("#buyingPriceError").html("");

        var pName = $("#pName").val();
        var category = $("#category").val();
        var measurement = $("#measurement").val();
        var description = $("#description").val();
        var buyingPrice = $("#buyingPrice").val();
        var minQty = $("#minQty").val();
        var maxQty = $("#maxQty").val();

        $.post('saveProduct', {
            pName: pName,
            category: category,
            measurement: measurement,
            description: description,
            minQty: minQty,
            maxQty: maxQty,
            buyingPrice: buyingPrice
        }, function(data) {

            if (data.errors != null) {

                if (data.errors.category) {
                    var p = document.getElementById('categoryError');
                    p.innerHTML = data.errors.category;

                }

                if (data.errors.pName) {
                    var p = document.getElementById('pNameError');
                    p.innerHTML = data.errors.pName;

                }

                if (data.errors.buyingPrice) {
                    var p = document.getElementById('buyingPriceError');
                    p.innerHTML = data.errors.buyingPrice;
                }


            }
            if (data.success != null) {
                $(".select2").val('').trigger('change');
                notify({
                    type: "success", //alert | success | error | warning | info
                    title: 'EXTRA ITEM SAVED',
                    autoHide: true, //true | false
                    delay: 2500, //number ms
                    position: {
                        x: "right",
                        y: "top"
                    },
                    icon: '<img src="{{ URL::asset('assets/images/correct.png') }}" />',

                    message: data.success,
                });

                $('input').val("");
                $('textarea').val("");
                $(".select2").val('').trigger('change');
                setTimeout(function() {
                    $('#addProductModal').modal('hide');
                }, 200);
                location.reload();
            }
        });
    }


    $(document).on('click', '#viewProduct', function() {
        var productId = $(this).data("id");
        $.post('viewProduct', {
            productId: productId
        }, function(data) {
            $("#dataViewBody").html(data.tableData);
        });
    });
    $(document).on('click', '#updateProduct', function() {
        var productId = $(this).data("id");
        $.post('getProductById', {
            productId: productId
        }, function(data) {
            console.log(data)
            $("#hiddenUItemId").val(data.idproduct);
            $("#uPName").val(data.product_name);
            $("#uBuyingPrice").val(data.buying_price);
            $("#uCategory").val(data.category_idcategory).trigger('change');
            $("#uDescription").val(data.description)

        });
    });

    function updateProduct() {

        $('#uCategoryError').html("");
        $('#uPNameError').html("");
        $('#uMeasurementError').html("");
        $('#uMinQtyError').html("");
        $('#uMaxQtyError').html("");
        $("#uBuyingPriceError").html("");

        var uPName = $("#uPName").val();
        var uCategory = $("#uCategory").val();
        var uMeasurement = $("#uMeasurement").val();
        var uDescription = $("#uDescription").val();
        var uBuyingPrice = $("#uBuyingPrice").val();
        var hiddenUItemId = $("#hiddenUItemId").val();
        var uMinQty = $("#uMinQty").val();
        var uMaxQty = $("#uMaxQty").val();

        $.post('updateProduct', {
            uPName: uPName,
            uCategory: uCategory,
            uMeasurement: uMeasurement,
            uDescription: uDescription,
            uBuyingPrice: uBuyingPrice,
            hiddenUItemId: hiddenUItemId,
            uMinQty: uMinQty,
            uMaxQty: uMaxQty,

        }, function(data) {
            console.log(data)
            if (data.errors != null) {

                if (data.errors.uCategory) {
                    var p = document.getElementById('uCategoryError');
                    p.innerHTML = data.errors.uCategory;

                }

                if (data.errors.uPName) {
                    var p = document.getElementById('uPNameError');
                    p.innerHTML = data.errors.uPName;

                }

                if (data.errors.uMeasurement) {
                    var p = document.getElementById('uMeasurementError');
                    p.innerHTML = data.errors.uMeasurement;

                }

                if (data.errors.uMinQty) {
                    var p = document.getElementById('uMinQtyError');
                    p.innerHTML = data.errors.uMinQty;

                }
                if (data.errors.uMaxQty) {
                    var p = document.getElementById('uMaxQtyError');
                    p.innerHTML = data.errors.uMaxQty;
                }
                if (data.errors.uBuyingPrice) {
                    var p = document.getElementById('uBuyingPriceError');
                    p.innerHTML = data.errors.uBuyingPrice;
                }

            }
            if (data.success != null) {
                $(".select2").val('').trigger('change');
                notify({
                    type: "success", //alert | success | error | warning | info
                    title: 'EXTRA ITEM UPDATED',
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
                    $('#updateProductModal').modal('hide');
                }, 200);
                location.reload();
            }
        });
    }
    $(document).ready(function() {
        $(document).on('focus', ':input', function() {
            $(this).attr('autocomplete', 'off');
        });
    });
</script>


@include('includes/footer_end')
