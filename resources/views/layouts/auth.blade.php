<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>PENRO Marinduque - Online Wildlife Permitting and Management System (OWPMS)</title>
        <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
        <link href="{{ asset('css/customize.css') }}" rel="stylesheet" />
    </head>
    <body class="bg-default">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                    	<div class="row justify-content-center mt-5">
                    		<div class="col-lg-4 text-center">
                    			<div class="auth-header">
                    				<img src="{{ asset('images/logo-small.png') }}" width="50" align="center">
	                    			<h4>Deparment of Enviroment and Natural Resources</h4>
									<h4>Provincial Enviroment and Natural Resources Office</h4>
									<h5>Boac, Marinduque</h5>
                    			</div>
                    		</div>
                    	</div>
                    	<div class="row justify-content-center">
                    		<div class="col-lg-4">
                        		@yield('content')
                    		</div>
                    	</div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer">
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright 2024 &copy; DENR PENRO - Marinduque</div>
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
        <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script> -->
		<script src="{{ asset('assets/jquery/jquery.min.js') }}"></script>
    	<script src="{{ asset('assets/bootstrap-5.2.3-dist/js/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script>
    	<script src="{{ asset('assets/fontawesome-free-6.5.1-web/js/all.min.js') }}" crossorigin="anonymous"></script>
        <script type="text/javascript">
		  var $ = jQuery.noConflict();
		</script>
        <script src="{{ asset('js/scripts.js') }}"></script>
        <script type="text/javascript">
            function showPasswords(cb_id, fld_ids) {
                var chkd = $('#'+cb_id).is(':checked');

                fld_ids.forEach(function (item) {
                    if (chkd) {
                        console.log(item);
                        document.getElementById(item).type="text";
                    } else {
                        document.getElementById(item).type="password";
                    }
                });
            }
                    </script>
        @yield('script-extra')
    </body>
</html>