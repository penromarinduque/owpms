<!-- loader -->
<style>
    .loader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        /* background-color: rgba(199, 199, 199, 0.5); */
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;

        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px); /* for Safari */
        background-color: rgb(255, 255, 255); /* optional: translucent */
        /* background-color: rgba(255, 255, 255, 0.2); optional: translucent */
    }

    .loader-text{
        color: #006c32;
        position: absolute;
        margin-top : 18.5rem;
        
    }

    .loader-text h2,.loader-text h3,.loader-text h4 {
        text-align: center;
        margin-bottom: 0px;
        margin-top: 0px;
    }

    .c{
        position: absolute;
        border : 0.4rem solid #0035aa;
        border-top : 0.4rem solid #f3f3f300;
        border-radius: 50%;
        width: 12rem;
        height: 12rem;
        animation: spin 2s linear infinite;
        opacity: 0.9;
    }

    .c-outer{
        position: absolute;
        /* border : 0.4rem solid #006c32; */
        border : 0.4rem solid #006c32;
        border-top : 0.4rem solid #f3f3f300;
        border-radius: 50%;
        width: 13.5rem;
        height: 13.5rem;
        animation: spin 2.5s linear infinite reverse;
        opacity: 0.9;
    }


    .logo {
        animation: fade 2s linear infinite ;
        width: 170px !important;
        height: 170px !important;
    }

    @keyframes fade {
        0%, 100% { opacity: 1; }
        25%, 75% { opacity: 0.7; }
        50% { opacity: 0.4; }
    }


    .spinner {
    width: 48px;
    height: 48px;
    border: 6px solid #e0e0e0;
    border-top: 6px solid #3498db; /* change color here */
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: auto; /* center horizontally */
    }

    @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
    }
</style>
<div class="loader">
    <div class="c">
    </div>
    <div class="c-outer">
    </div>
    <img class="logo" src="{{ asset('images/logo-small.png') }}" width="100" height="100" alt="">
    {{-- <div class="loader-text">
        <h2 class="">
            Please wait...
        </h4>
        <h3>This might take a while!</h6>
    </div> --}}
</div>

<script>
	document.addEventListener('DOMContentLoaded', function () {
		console.log("All Loaded");
		document.getElementsByClassName("loader")[0].style.display = "none";
	});
</script>