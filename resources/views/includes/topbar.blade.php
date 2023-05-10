            <!-- Start right Content here -->
            <div class="content-page">
                <!-- Start content -->
                <div class="content">

                    <!-- Top Bar Start -->
                    <div class="topbar">

                        <nav class="navbar-custom">
                            <!-- Search input -->
                            <div class="search-wrap" id="search-wrap">
                                <div class="search-bar">
                                    <input class="search-input" type="search" placeholder="Search" />
                                    <a href="#" class="close-search toggle-search" data-target="#search-wrap">
                                        <i class="mdi mdi-close-circle"></i>
                                    </a>
                                </div>
                            </div>

                            <ul class="list-inline float-right mb-0">
                                <!-- Search -->
                                <li class="list-inline-item dropdown notification-list">
                                    <a href="cart">
                                        <i class="fa fa-cart-plus" aria-hidden="true"
                                            style="font-size:25px;color:black"></i>
                                    </a>
                                </li>

                                <li class="list-inline-item dropdown notification-list">
                                    <a class="nav-link dropdown-toggle arrow-none waves-effect nav-user"
                                        data-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                                        aria-expanded="false">
                                        <img src="{{ URL::asset('assets/images/avatar-1.jpg') }}" height="20"
                                            alt="user" class="rounded-circle">

                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                                        {{-- <a class="dropdown-product" href="#"><i class="dripicons-wallet text-muted"></i> My Wallet</a> --}}
                                        {{-- <a class="dropdown-product" href="#"><span class="badge badge-success pull-right m-t-5">5</span><i class="dripicons-gear text-muted"></i> Settings</a> --}}
                                        {{-- <a class="dropdown-product" href="#"><i class="dripicons-lock text-muted"></i> Lock screen</a> --}}
                                        {{-- <div class="dropdown-divider"></div> --}}
                                        <a class="dropdown-item" href="#"><em
                                                class="mdi mdi-account"></em>&nbsp;{{ \Illuminate\Support\Facades\Auth::user()->first_name }}
                                        </a>

                                        <a class="dropdown-item" href="profile"><i
                                                class="dripicons-user text-muted"></i> Profile</a>
                                        <a class="dropdown-item" href="logout"><i
                                                class="dripicons-exit text-muted"></i> Logout</a>
                                    </div>
                                </li>
                            </ul>
