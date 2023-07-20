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
        <h4 class="page-title">{{ $title }}</h4>
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
                    <form id="saveDeliveryOrder" class="form-horizontal" action="{{ route('save-delivery-order') }}"
                        method="POST">
                        <div class="row">
                            <div class="col-lg-12">
                                <h6><u>Item Creation</u></h6>
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
                        {{-- <div class="row">
                            <div class="col-lg-12">
                                <h6><u>Add Ingrediant</u></h6>
                            </div>
                        </div> --}}
                        {{-- <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="example-text-input" class="col-form-label">Ingrediant<span
                                            style="color: red">
                                            *</span></label>
                                    <select class="select2" id="itemId" name="itemId"
                                        onchange="selectIngrediant(this.value)">
                                        <option disabled selected>Select Ingrediant</option>
                                        @foreach ($ingredients as $ingredient)
                                            <option value="{{ "$ingredient->idproduct" }}">
                                                {{ $ingredient->product_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span id="itemIdError" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="example-text-input" class="col-form-label">Available Qty</label>

                                    <input type="number" disabled class="form-control" name="availableQty"
                                        id="availableQty" placeholder="0" />
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="example-text-input" class="col-form-label">Qty<span style="color: red">
                                            *</span></label>

                                    <input type="number" class="form-control" name="ingrediantQty" id="ingrediantQty"
                                        placeholder="0" />
                                    <span id="ingrediantQtyError" class="text-danger"></span>
                                </div>
                            </div>
                        </div> --}}
                        {{-- <div class="row">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary waves-effect "
                                        onclick="saveIngredient()">
                                        Add Ingredient</button>
                                </div>
                            </div>
                        </div> --}}

                        {{-- <div class="table-rep-plugin">
                            <div class="table-responsive b-0" data-pattern="priority-columns">
                                <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Ingredient Name</th>
                                            <th>Qty</th>
                                            <th>Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody id="ingredientTable">
                                    </tbody>
                                </table>
                            </div>
                        </div> --}}
                        <div class="row">
                            <div class="col-lg-12">
                                <span id="ingredientError" class="text-danger"></span>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary waves-effect ">
                                        Save Item</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div><!-- container -->

</div> <!-- Page content Wrapper -->

</div> <!-- content -->

@include('includes/footer_start')
<script type="text/javascript">
    $(document).ready(function() {
        $('form').parsley();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        fetchIngrediantTable();
    });
    $(document).on("wheel", "input[type=number]", function(e) {
        $(this).blur();
    });
    $(document).ready(function() {
        $(document).on('focus', ':input', function() {
            $(this).attr('autocomplete', 'off');
        });
    });

    function fetchIngrediantTable() {
        $.get('fetch-ingredient-table', {}, function(data) {
            $("#ingredientTable").html(data);
        })
    }

    function selectIngrediant(id) {
        if (id) {
            $.post('get-available-qty', {
                id: id
            }, function(data) {
                $('#availableQty').val(data)
            })
        }

    }

    function deleteIngredient(id) {
        $.post('delete-delivery-ingredient', {
            id: id
        }, function(data) {
            fetchIngrediantTable();
        })
    }

    function saveIngredient() {
        $("#ingrediantQtyError").html('');
        $("#itemIdError").html('');

        var itemId = $('#itemId').val();
        var ingrediantQty = $('#ingrediantQty').val();
        $.post('save-ingredient', {
            itemId: itemId,
            ingrediantQty: ingrediantQty
        }, function(data) {
            if (data.errors) {
                if (data.errors.ingrediantQty) {
                    var p = document.getElementById('ingrediantQtyError');
                    p.innerHTML = data.errors.ingrediantQty[0];
                }
                if (data.errors.itemId) {
                    var p = document.getElementById('itemIdError');
                    p.innerHTML = data.errors.itemId[0];
                }
            }

            if (data.qtyError) {
                var p = document.getElementById('ingrediantQtyError');
                p.innerHTML = data.qtyError;
            }

            if (data.success != null) {
                $("#ingrediantQty").val('');
                $("#availableQty").val(0);
                $("#itemId").val('').trigger('change')
                notify({
                    type: "success", //alert | success | error | warning | info
                    title: 'INGREDIENT SAVED',
                    autoHide: true, //true | false
                    delay: 2500, //number ms
                    position: {
                        x: "right",
                        y: "top"
                    },
                    icon: '<img src="{{ URL::asset('assets/images/correct.png') }}" />',
                    message: data.success,
                });
                fetchIngrediantTable();
            }
        })
    }

    $("#saveDeliveryOrder").on("submit", function(event) {
        $("#itemNameError").html('');
        $("#itemPriceError").html('');
        $("#itemQtyError").html('');
        $("#imageError").html('');
        event.preventDefault();
        $.ajax({
            url: 'save-delivery-order',
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
                        title: 'DELIVERY ORDER SAVED',
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
</script>


@include('includes/footer_end')
