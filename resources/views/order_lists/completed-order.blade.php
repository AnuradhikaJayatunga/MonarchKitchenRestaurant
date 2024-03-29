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
                                        <th>Name</th>
                                        <th>Address</th>
                                        <th>Type</th>
                                        <th>Items</th>
                                        <th>Total Cost</th>
                                        <th>No of Persons</th>
                                        <th>Driver</th>
                                        <th>Required Date</th>
                                        <th>Accepted Person</th>
                                        <th>Accepted Date and Time</th>
                                        <th>Print</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($orders))
                                        @if (count($orders) > 0)

                                            @foreach ($orders as $order)
                                                <tr>
                                                    <td>{{ $order->name }}</td>
                                                    <td>{{ $order->address }}</td>
                                                    <td>{{ $order->type }}</td>
                                                    <td><button class="btn btn-primary btn-sm" data-toggle="modal"
                                                            data-id="{{ $order->idorder }}" id="orderId"
                                                            data-target="#viewItems">View Item</button></td>
                                                    <td>{{ $order->total_cost }}</td>
                                                    <td>{{ $order->no_of_persons }}</td>
                                                    @if($order->type=='Reservation Order')
                                                    <td>-</td>
                                                @else
                                                    <td>{{ $order->User->first_name }}
                                                        {{ $order->User->last_name }}</td>
                                                    @endif
                                                    <td>{{ $order->date }}</td>
                                                   
                                                    <td>{{ $order->acceptUser->first_name }}
                                                        {{ $order->acceptUser->last_name }}</td>
                                                   
                                                    <td>{{ $order->accept_date_time }}</td>
                                                    <td>
                                                        <button href="#" class="btn btn-primary btn-sm"
                                                            onclick="print({{ $order->idorder }})">Print
                                                            Invoice</button>
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


<div class="modal fade" id="viewItems" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">View Items</h5>
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

<iframe style="display: none;" id="iframeprint" src=""></iframe>
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
    $(document).ready(function() {
        $(document).on('focus', ':input', function() {
            $(this).attr('autocomplete', 'off');
        });
    });

    function print(id) {
        let _this = this,
            iframeId = 'iframeprint',
            $iframe = $('iframe#iframeprint');
        $iframe.attr('src', 'print_invoice/' + id);
        $iframe.load(function() {
            _this.callPrint(iframeId);
        });
    }

    function callPrint(iframeId) {
        let PDF = document.getElementById(iframeId);
        PDF.focus();
        PDF.contentWindow.print();
    }


    $(document).on('click', '#orderId', function() {
        var id = $(this).data("id");
        $.post('view-order-items', {
            id: id
        }, function(data) {
            $('#viewItem').html(data.data);
        });
    });
</script>


@include('includes/footer_end')
