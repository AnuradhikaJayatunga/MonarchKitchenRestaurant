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
                                     data-toggle="modal" data-target="#addCategoryModal">
                                    Add Category</button>

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
                                        <th>Status</th>
                                        <th>Edit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($categories))
                                        @if (count($categories) == 0)
                                            <tr>
                                                <td colspan="6" style="text-align: center;font-weight: bold  ">Sorry No
                                                    Results Found.
                                                </td>
                                            </tr>
                                        @endif
                                        @foreach ($categories as $category)
                                            <tr>
                                                <td>{{ $category->category_name }}</td>
                                                @if ($category->fixed == 1)
                                                    <td>
                                                        <p style="color: red">Fixed</p>
                                                    </td>

                                                @else
                                                    @if ($category->status== 1)

                                                        <td>
                                                            <p>
                                                                <input type="checkbox"
                                                                    onchange="adMethod('{{ $category->idcategory }}','category')"
                                                                    id="{{ 'c' . $category->idcategory }}" checked
                                                                    switch="none" />
                                                                <label for="{{ 'c' . $category->idcategory }}"
                                                                    data-on-label="On" data-off-label="Off"></label>
                                                            </p>
                                                        </td>
                                                    @else
                                                        <td>
                                                            <p>
                                                                <input type="checkbox"
                                                                    onchange="adMethod('{{ $category->idcategory }}','category')"
                                                                    id="{{ 'c' . $category->idcategory }}"
                                                                    switch="none" />
                                                                <label for="{{ 'c' . $category->idcategory }}"
                                                                    data-on-label="On" data-off-label="Off"></label>
                                                            </p>
                                                        </td>
                                                    @endif
                                                @endif
                                                <td>

                                                    <p>
                                                        <button type="button"
                                                            class="btn btn-sm btn-warning  waves-effect waves-light"
                                                            data-toggle="modal" data-id="{{ $category->idcategory }}"
                                                            id="uCategoryID" data-target="#updateCategoryModal"><i
                                                                class="fa fa-edit"></i>
                                                        </button>
                                                    </p>
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
    </div><!-- container -->

</div> <!-- Page content Wrapper -->

</div> <!-- content -->

<!--add category modal-->
<div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Add Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger alert-dismissible " id="errorAlert" style="display:none">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>

                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="example-text-input" class="col-form-label">Category<span style="color: red">
                            *</span></label>

                    <input type="text" class="form-control" name="category" id="category" required
                        placeholder="Category" />
                    <small class="text-danger">{{ $errors->first('category') }}</small>
                </div>
                <button type="button" class="btn btn-md btn-primary waves-effect " onclick="saveCategory()"
                   >
                    Save Category</button>
            </div>
        </div>
    </div>
</div>
</div>


<!--update category modal-->
<div class="modal fade" id="updateCategoryModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Edit Category</h5>
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
                    <label for="example-text-input" class="col-form-label">Category<span style="color: red">
                            *</span></label>

                    <input type="text" class="form-control" name="uCategory" id="uCategory" required
                        placeholder="Category" />
                    <small class="text-danger">{{ $errors->first('uCategory') }}</small>
                </div>
                <button type="submit" class="btn btn-md btn-warning waves-effect " onclick="updateCategory()"
                   >
                    Update Category</button>
            </div>
            <input type="hidden" id="hiddenCatId">
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
        $('input').val('');

    });

    function saveCategory() {

        $('#errorAlert').hide();
        $('#errorAlert').html("");

        var category = $("#category").val();

        $.post('saveCategory', {
            category: category,
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
                    title: 'CATEGORY SAVED',
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
                    $('#addCategoryModal').modal('hide');
                }, 200);
            }
            location.reload();
        });


    }

    function updateCategory() {

        $('#errorAlert1').hide();
        $('#errorAlert1').html("");

        var uCategory = $("#uCategory").val();
        var hiddenCatId = $("#hiddenCatId").val();

        $.post('updateCategory', {
            uCategory: uCategory,
            hiddenCatId: hiddenCatId,
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
                            title: 'CATEGORY UPDATED',
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
                    $('#updateCategoryModal').modal('hide');
                }, 200);
            }
                        location.reload();

        });


    }

    $(document).on('click', '#uCategoryID', function() {
        var categoryId = $(this).data("id");

        $.post('getByCategoryId', {
            categoryId: categoryId
        }, function(data) {
            $("#hiddenCatId").val(data.idcategory);
            $("#uCategory").val(data.category_name);
        });
    });

    $(document).ready(function() {
        $(document).on('focus', ':input', function() {
            $(this).attr('autocomplete', 'off');
        });
    });

</script>


@include('includes/footer_end')