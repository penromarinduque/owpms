<!DOCTYPE html>
<html lang="en">
    <head>
        <title>PENRO Marinduque - Online Wildlife Permitting and Management System (OWPMS)</title>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/x-icon" href="{{ asset('images/logo-icon.ico') }}" sizes="16x16 32x32">

        {{-- font-awesome --}}
        <link href="{{ asset('assets/fontawesome-free-6.5.1-web/css/fontawesome.min.css') }}" rel="stylesheet" />

        {{-- datatables --}}
        <link href="{{ asset('assets/simple-datatables/style.min.css') }}" rel="stylesheet" />

        {{-- QUILL --}}
        <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">

        {{-- croppie --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css">

        {{-- bladewind --}}
        @yield('bladewind-ui')
        
        {{-- <link href="{{ asset('css/theme.css') }}" rel="stylesheet" /> --}}

        {{-- sbadmin --}}
        <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
        
        {{-- volt --}}
        {{-- <link href="{{ asset('volt-assets/css/all.min.css') }}" rel="stylesheet" /> --}}
        <link href="{{ asset('volt-assets/css/volt.css') }}" rel="stylesheet" />

        <!-- Select2 -->
        <link rel="stylesheet" href="{{ asset('assets/select2/dist/css/select2.min.css') }}">
        <link href="{{ asset('css/customize.css') }}" rel="stylesheet" />

        {{-- Custom styles --}}
        <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

        <style>
            main.content {
                margin-left: 300px;
            }
            @media (max-width: 992px) {
                main.content {
                    margin-left: 0 !important;
                }
            }
        </style>

    </head>
    <body class="vh-100">
        @include('components.fullLoader')

        @include('layouts.navtop')
        
        @include('layouts.navside')
        <main class="content" >
            {{-- TopBar --}}
            @include('layouts.topbar')
            <div style="min-height: 700px">
                @yield('content')
            </div>
            {{-- Footer --}}
            @include('layouts.footer')
        </main>
        
        @include('components.notificationOffCanvas')

    </body>
    <!-- jQuery -->
    <script src="{{ asset('assets/jquery/jquery.min.js') }}"></script>

    <!-- Core -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/fontawesome-free-6.5.1-web/js/all.min.js') }}" crossorigin="anonymous"></script>

    <!-- Select2 -->
    <script src="{{ asset('assets/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('js/jquery.ajaxrequestlaravel.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="{{ asset('vendor/bladewind/js/helpers.js') }}"></script>
    
    {{-- Image Cropper --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.js"></script>

    <script type="text/javascript">
        // Fetch token from session
        var sanctumToken = '{{ session('sanctumToken') }}';

        // Setup AJAX headers
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Authorization': sanctumToken ? "Bearer " + sanctumToken : ""
            }
        });

    </script>
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/extra.js') }}"></script>

    {{-- datatables --}}
    <script src="{{ asset('assets/simple-datatables/simple-datatables.min.js') }}" crossorigin="anonymous"></script>
    <script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
    
    <script type="text/javascript"> 
        var $ = jQuery.noConflict();
        jQuery(document).ready(function ($){

            // Select2
            $(".select2Paginate").select2({
                ajax : {
                    delay: 250,
                    data : function(params){
                        var query = {
                            search: params.term,
                            type: 'public',
                            page: params.page || 1
                        }

                        // Query parameters will be ?search=[term]&type=public
                        return query;
                    },
                    processResults: function (data) {
                        return {
                            results: data.data,
                            pagination: {
                                more: (data.current_page < data.last_page)
                            }
                        };
                    },
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
        });
    </script>
    <script>
        $(document).ready(function() {
            $(".select2").select2();
            $(".btn-submit").click(function() {
                $(this).prop('disabled', true);
                $(this).prepend('<i class="fas fa-spinner fa-spin me-2"></i>');
                $(this).closest("form").submit();
            });
        })
    </script>
    <!-- on page scripts -->
    @yield('script-extra')
    @yield('includes')

    <!-- Vendor JS -->
    <script src="{{ asset('volt-assets/assets/js/on-screen.umd.min.js') }}"></script>

    <!-- Slider -->
    <script src="{{ asset('volt-assets//assets/js/nouislider.min.js') }}"></script>

    <!-- Smooth scroll -->
    <script src="{{ asset('volt-assets//assets/js/smooth-scroll.polyfills.min.js') }}"></script>

    <!-- Apex Charts -->
    <script src="{{ asset('volt-assets//vendor/apexcharts/apexcharts.min.js') }}"></script>

    <!-- Charts -->
    <script src="{{ asset('volt-assets//assets/js/chartist.min.js') }}"></script>
    <script src="{{ asset('volt-assets//assets/js/chartist-plugin-tooltip.min.js') }}"></script>

    <!-- Datepicker -->
    <script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.1.4/dist/js/datepicker.min.js"></script>

    <!-- Sweet Alerts 2 -->
    <script src="{{ asset('volt-assets//assets/js/sweetalert2.all.min.js') }}"></script>

    <!-- Moment JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js"></script>

    <!-- Notyf -->
    <script src="{{ asset('volt-assets//vendor/notyf/notyf.min.js') }}"></script>

    <!-- Simplebar -->
    <script src="{{ asset('volt-assets//assets/js/simplebar.min.js') }}"></script>


    <!-- Volt JS -->
    <script src="{{ asset('volt-assets/assets/js/volt.js') }}"></script>
</html>

@include('components.toast')