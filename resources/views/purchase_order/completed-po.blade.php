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
                                    <th>Date</th>
                                    <th>Created At</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($completedPO))
                                    @if(count($completedPO)>0)
                                     
                                    @foreach($completedPO as $PO)
                                        <tr>
                                            <td>{{ str_pad($PO->idpurchase_order,5,'0',STR_PAD_LEFT) }}</td>
                                            <td>{{$PO->Supplier->company_name}}</td>
                                            
                                            <td>{{$PO->date}}</td>
                                            <td>{{$PO->created_at}}</td>

                                           
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
</script>
@include('includes.footer_end')