@include('includes/header_start')
<meta name="csrf-token" content="{{ csrf_token() }}" />
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
                    <form action="{{ route('supplier-report') }}" method="get">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-lg-4">
                                <input type="text" class="form-control" name="poNo" id="poNo"
                                        placeholder="Search by PO No" />
                            </div>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" name="grnNo" id="grnNo"
                                        placeholder="Search by GRN No" />
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group row">
                                    <div class="col-sm-10">
                                        <button type="submit" id="saveBtn" class="btn btn-primary">Search</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card m-b-20">
                <div class="card-body">


                    <div class="table-rep-plugin">
                        <div class="table-responsive b-0" data-pattern="priority-columns">
                            <table id="datatable-buttons" class="table table-striped table-bordered data-table"
                                cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Company Name</th>
                                        <th>Address</th>
                                        <th>Contact No</th>
                                        <th>Email</th>
                                        <th>Bank Name</th>
                                        <th>Account No</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @if (count($suppliers) != 0)

                                        @foreach ($suppliers as $supplier)
                                            <tr>
                                              
                                                <td>{{ $supplier->company_name}}</td>
                                                <td>{{ $supplier->address}}</td>
                                                <td>{{ $supplier->contact_no}}</td>
                                                <td>{{ $supplier->email}}</td>  
                                                <td>{{ $supplier->bank_name}}</td>
                                                <td>{{ $supplier->account_no}}</td>                                                
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
