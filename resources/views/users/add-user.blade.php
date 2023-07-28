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
        <div class="col-lg-8">
            <div class="card m-b-20">
                <div class="card-body">


                    <form class="" method="post" enctype="multipart/form-data"
                        action="{{ route('saveUserByAdmin') }}" id="saveUserId">
                        {{ csrf_field() }}

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="pass">First Name<span
                                        style="color: red"> *</span></label>
                                    <input type="text" class="form-control" id="fName" autocomplete="off"
                                        name="fName" placeholder="First Name">
                                    <small class="text-danger" id="fNameError"></small>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="pass">Last Name<span
                                        style="color: red"> *</span></label>
                                    <input type="text" class="form-control" id="lName" autocomplete="off"
                                        name="lName" placeholder="Last Name">
                                    <small class="text-danger" id="lNameError"></small>
                                </div>
                            </div>
                        </div>




                        <div class="form-group">
                            <label for="pass">Contact No<span
                                style="color: red"> *</span></label>
                            <input type="number" class="form-control" id="contactNo" autocomplete="off"
                                name="contactNo" placeholder="+(94) XX XXX XXXX">
                            <small class="text-danger" id="contactNoError"></small>
                        </div>


                        <div class="form-group">
                            <label for="pass">Email Address<span
                                style="color: red"> *</span></label>
                            <input type="text" class="form-control" id="username" autocomplete="off" name="username"
                                placeholder="Email Address">
                            <small class="text-danger" id="NIcError"></small>
                        </div>
                        <div class="form-group">
                            <label for="pass">Address<span
                                style="color: red"> *</span></label>
                            <input type="text" class="form-control" id="address" autocomplete="off" name="address"
                                placeholder="Address">
                            <small class="text-danger" id="addressError"></small>
                        </div>
                        <div class="form-group">
                            <label for="pass">Role</label>
                            <select class="form-control select2 tab" name="role" id="role">
                                <option value="" disabled selected>Select Role
                                </option>
                                @if (isset($roles))
                                    @foreach ($roles as $role)
                                        <option value="{{ "$role->iduser_role" }}">
                                            {{ $role->user_role }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <small class="text-danger" id="roleError"></small>
                        </div>
                        <div class="form-group">
                            <label for="pass">Password<span
                                style="color: red"> *</span></label>
                            <input type="password" class="form-control" id="password" autocomplete="off"
                                name="password" placeholder="Enter password">
                            <small class="text-danger" id="passwordError"></small>
                        </div>


                        <button class="btn btn-primary w-md waves-effect waves-light" type="submit">Save
                            New User</button>


                    </form>
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

    $("#saveUserId").on("submit", function(event) {
        $("#fNameError").html('');
        $("#emailError").html('');
        $("#lNameError").html('');
        $("#contactNoError").html('');
        $("#NIcError").html('');
        $("#passwordError").html('');
        $("#roleError").html('');

        event.preventDefault();

        $.ajax({
            url: '{{ route('saveUserByAdmin') }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(data) {
                console.log(data.errorUser)
                if (data.errorUser) {

                    var p = document.getElementById('NIcError');
                    p.innerHTML = data.errorUser.error;

                }
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

                    if (data.errors.username) {
                        var p = document.getElementById('emailAddressError');
                        p.innerHTML = data.errors.username[0];
                    }
                    if (data.errors.password) {
                        var p = document.getElementById('passwordError');
                        p.innerHTML = data.errors.password[0];
                    }
                    if (data.errors.address) {
                        var p = document.getElementById('addressError');
                        p.innerHTML = data.errors.address[0];
                    }

                    if (data.errors.role) {
                        var p = document.getElementById('roleError');
                        p.innerHTML = data.errors.role[0];
                    }



                }

                if (data.success != null) {


                    notify({
                        type: "success", //alert | success | error | warning | info
                        title: 'USER SAVED',
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
