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
                    <form action="{{ route('order-report') }}" method="get">
                        {{ csrf_field() }}
                        <div class="row">

                            <div class="col-lg-4">
                                <div class="form-group">

                                    <input type="text" class="form-control" name="orderID" id="orderID"
                                        placeholder="Search by Order  ID" />
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <input type="date" class="form-control" name="date" id="date"
                                    placeholder="2021/1/5">
                            </div>
                            <div class="col-lg-4">
                                <select class="select2" id="status" name="status">
                                    <option selected disabled>Select Status</option>
                                    <option value="0">Pending</option>
                                    <option value="1">Accept</option>
                                    <option value="2">Completed</option>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <select class="select2" id="type" name="type">
                                    <option selected disabled>Select Type</option>
                                    <option value="Delivery Order">Delivery Order</option>
                                    <option value="Catering Order">Catering Order</option>
                                    <option value="Reservation Order">Reservation Order </option>
                                </select>
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
                                        <th>Order ID</th>
                                        <th>Customer Name</th>
                                        <th>Driver</th>
                                        <th>Type</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @if (count($orders) != 0)

                                        @foreach ($orders as $order)
                                            <tr>
                                                <td>{{ str_pad($order->idorder, 5, '0', STR_PAD_LEFT) }}</td>
                                                <td>{{ $order->name }}</td>
                                                @if ($order->User)
                                                    <td>{{ $order->User->first_name }} {{ $order->User->last_name }}
                                                    </td>
                                                @else
                                                    <td>-</td>
                                                @endif

                                                <td>{{ $order->type }}</td>
                                                <td>{{ $order->date }}</td>
                                                <td>{{ number_format($order->total_cost, 2) }}</td>
                                                <td>
                                                    @if ($order->status == 0)
                                                        Pending
                                                    @elseif ($order->status == 1)
                                                        Accepted
                                                    @else
                                                        Completed
                                                    @endif
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
