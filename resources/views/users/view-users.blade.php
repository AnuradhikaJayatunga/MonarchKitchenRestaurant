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
                            <table id="datatable" class="table table-striped table-bordered data-table" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Contact No</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Options</th>

                                    </tr>
                                </thead>

                                <tbody>


                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $user->first_name }}</td>
                                            <td>{{ $user->last_name }}</td>
                                            <td>{{ $user->contact_no }}</td>
                                            <td>{{ $user->userRole->user_role }}</td>
                                            @if ($user->status == 1)
                                                <td>Active</td>
                                            @else
                                                <td>Inactive</td>
                                            @endif

                                            @if ($user->user_role_iduser_role == 3)
                                                <td>
                                                    <p style="color: red">-</p>
                                                </td>
                                            @else
                                                @if ($user->status == 1)
                                                    <td>
                                                        <p>
                                                            <input type="checkbox"
                                                                onchange="adMethod('{{ $user->iduser_master }}',' user_master')"
                                                                id="{{ 'c' . $user->iduser_master }}" checked
                                                                switch="none" />
                                                            <label for="{{ 'c' . $user->iduser_master }}"
                                                                data-on-label="On" data-off-label="Off"></label>
                                                        </p>
                                                    </td>
                                                @else
                                                    <td>
                                                        <p>
                                                            <input type="checkbox"
                                                                onchange="adMethod('{{ $user->iduser_master }}',' user_master')"
                                                                id="{{ 'c' . $user->iduser_master }}" switch="none" />
                                                            <label for="{{ 'c' . $user->iduser_master }}"
                                                                data-on-label="On" data-off-label="Off"></label>
                                                        </p>
                                                    </td>
                                                @endif
                                            @endif
                                        </tr>
                                    @endforeach


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


    $(document).on('click', '#uPasswordId', function() {
        var userId = $(this).data("id");

        $("#hiddenPID").val(userId);

    });

    $(document).on('click', '#uEmployee', function() {
        var userId = $(this).data("id");
        $.post('getUserById', {
            userId: userId
        }, function(data) {
            console.log(data)
            $("#fName").val(data.first_name);
            $("#lName").val(data.last_name);
            $("#contactNo").val(data.contact_no);
            $("#username").val(data.username);
            $("#address").val(data.address);
            $("#hiddenUID").val(data.iduser_master);

        })
    });

    function adMethod(dataID, tableName) {

        $.post('activateDeactivate', {
            id: dataID,
            table: tableName
        }, function(data) {

        });
    }

    $('.modal').on('hidden.bs.modal', function() {

        $("input").val('');

        $('#errorAlert1').html('');
        $('#errorAlert2').html('');

        $('#errorAlert1').hide();
        $('#errorAlert2').hide();


        $('#titleError').html('');
        $('#fNameError').html('');
        $('#lNameError').html('');
        $('#contactNoError').html('');
        $('#employeeTypeError').html('');
        $('#usernameError').html('');
        $('#passwordError').html('');
        $("#address").html('');
        $("#usernameError").html('');
    });

    function editPassword() {


        $('#errorAlert2').hide();
        $('#errorAlert2').html("");

        var update_pass2 = $("#update_pass2").val();
        var hiddenPID = $("#hiddenPID").val();
        var compass = $("#compass").val();



        $.post('updatePassword', {
            update_pass2: update_pass2,
            hiddenPID: hiddenPID,
            compass: compass

        }, function(data) {
            if (data.errors != null) {
                $('#errorAlert2').show();
                $.each(data.errors, function(key, value) {
                    $('#errorAlert2').append('<p>' + value + '</p>');
                });

            }
            if (data.success != null) {
                notify({
                    type: "success", //alert | success | error | warning | info
                    title: 'PASSWORD UPDATED',
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
                    $('#passUpModal').modal('hide');
                }, 200);
            }

        });
    }


    $("#updateUserId").on("submit", function(event) {



        $("#fNameError").html('');
        $("#emailError").html('');
        $("#lNameError").html('');
        $("#contactNoError").html('');
        $("#address").html('');
        $("#usernameError").html('');
        event.preventDefault();

        $.ajax({
            url: '{{ route('updateUser') }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(data) {

                if (data.errors != null) {



                    if (data.errors.fName) {
                        var p = document.getElementById('fNameError');
                        p.innerHTML = data.errors.fName[0];
                    }
                    if (data.errors.email) {
                        var p = document.getElementById('emailError');
                        p.innerHTML = data.errors.email[0];
                    }

                    if (data.errors.lName) {
                        var p = document.getElementById('lNameError');
                        p.innerHTML = data.errors.lName[0];
                    }
                    if (data.errors.contactNo) {
                        var p = document.getElementById('contactNoError');
                        p.innerHTML = data.errors.contactNo[0];
                    }

                    if (data.errors.address) {
                        var p = document.getElementById('addressError');
                        p.innerHTML = data.errors.address[0];
                    }
                    if (data.errors.username) {
                        var p = document.getElementById('usernameError');
                        p.innerHTML = data.errors.username[0];
                    }


                }

                if (data.success != null) {


                    notify({
                        type: "success", //alert | success | error | warning | info
                        title: 'CUSTOMER UPDATED',
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
                    }, 200);

                }
            }
        });
    });
</script>


@include('includes.footer_end')
