<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title') | PENRO Marinduque - Online Wildlife Permitting and Management System (OWPMS)</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('images/logo-icon.ico') }}" sizes="16x16 32x32">
        <link href="{{ asset('assets/fontawesome-free-6.5.1-web/css/fontawesome.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/simple-datatables/style.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
        <!-- Select2 -->
        <link rel="stylesheet" href="{{ asset('assets/select2/dist/css/select2.min.css') }}">
        <link href="{{ asset('css/customize.css') }}" rel="stylesheet" />
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            @include('layouts.navtop')
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                @include('layouts.navside')
            </div>
            <div id="layoutSidenav_content">
                <main>
                    @yield('content')
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; DENR - PENRO Marinduque 2024</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <!-- jQuery -->
        <script src="{{ asset('assets/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/bootstrap-5.2.3-dist/js/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script>
        <script src="{{ asset('assets/fontawesome-free-6.5.1-web/js/all.min.js') }}" crossorigin="anonymous"></script>
        <!-- Select2 -->
        <script src="{{ asset('assets/select2/dist/js/select2.min.js') }}"></script>
        <script src="{{ asset('js/jquery.ajaxrequestlaravel.js') }}" defer></script>
        <script type="text/javascript">
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        </script>
        <script src="{{ asset('js/scripts.js') }}"></script>
        <script src="{{ asset('js/extra.js') }}"></script>
        <script src="{{ asset('assets/simple-datatables/simple-datatables.min.js') }}" crossorigin="anonymous"></script>
        <script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
        <script type="text/javascript"> 
            var $ = jQuery.noConflict();
            jQuery(document).ready(function ($){

                // Select2
                $(".select2").select2();
            });
        </script>
        <!-- on page scripts -->
        @yield('script-extra')
    </body>
</html>

@include('components.toast')