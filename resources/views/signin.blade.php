@include('includes.header_account')

<style>
    body {
      background-image: url('https://png.pngtree.com/background/20210710/original/pngtree-hamburg-fast-food-anniversary-flyer-background-material-picture-image_1056361.jpg');
      background-repeat: no-repeat;
      background-attachment: fixed;
      background-size: 100% 100%;
    }
    </style> 

<!-- Begin page -->
<div class="accountbg" style="background-image: url('https://png.pngtree.com/background/20210710/original/pngtree-hamburg-fast-food-anniversary-flyer-background-material-picture-image_1056361.jpg);" ></div>
<div class="page-content-wrapper">

    <div class="container">
        <div class="row">
            <div class="col-lg-3">

            </div>
            <div class="card m-b-15">
            <div class="card" >
        
        <div class="card-body">
            <h3 class="text-center m-0">
                <a href="index" class="logo logo-admin"><img src="assets/images/logo.jpeg" height="100"
                        alt="logo"></a>
            </h3>

            <div class="p-3">
                <h4 class="text-muted font-18 m-b-5 text-center">WelcomeBack !</h4>
                <p class="text-muted text-center">Sign in to continue to The Monarch Kitchen Family Restaurant</p>

                @if (\Session::has('error'))
                    <div class="alert alert-danger alert-dismissible ">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <p>{{ \Session::get('error') }}</p>
                    </div>
                @endif

                @if (\Session::has('warning'))
                    <div class="alert alert-warning alert-dismissible ">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <p>{{ \Session::get('warning') }}</p>
                    </div>
                @endif


                <form class="form-horizontal m-t-30" action="{{ route('loginMy') }}" method="POST">
                
                    <div class="form-group">
                        <label for="username">Email Address</label>
                        <input type="text" class="form-control" id="username" name="username"
                            placeholder="Enter Email Address">
                        <small class="text-danger">{{ $errors->first('email Address') }}</small>
                    </div>

                    <div class="form-group">
                        <label for="pass">Password</label>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Enter password">
                        <small class="text-danger">{{ $errors->first('password') }}</small>
                    </div>
                    <input type="hidden" name="_token" value="{{ Session::token() }}">

                    <div class="form-group row m-t-20">
                        <div class="col-sm-6">

                        </div>
                        <div class="col-sm-6 text-right">
                            <button class="btn btn-primary w-md waves-effect waves-light" type="submit">Log In</button>
                        </div>
                    </div>

                   
                    <div class="form-group mb-0 row">
                        <div class="col-12">
                            <p style="text-align: center">Not a member yet? <a href="{{ URL::asset('sign-up') }}"
                                    class="text-muted">Sign Up</a></p>
                            <p style="text-align: center"><a href="{{ URL::asset('forgot-password') }}"
                                    class="text-muted">Forgot Password</a></p>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@include('includes.footer_account')
