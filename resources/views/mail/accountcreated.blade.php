<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Account Created</title>
	<style type="text/css">
		p { font-family: 'Roboto', sans-serif; font-size: 15px; }
		a { text-decoration:none; }
		.btn {
			padding: 6px 30px;
		    border: 1px solid #0d6efd;
		    background-color: #0d6efd;
		    color: #FFFFFF!important;
		    border-radius: 3px;
		    cursor: pointer;
		    text-decoration: none;
		}

		.btn:hover {
			background-color: #4283e3;
		}
	</style>
</head>
<body>
    <h2>{{ $mailData['title'] }}</h2>
    <p>Hi <b>{{ $mailData['email'] }}</b></p>
  
    <p>Congratulations! Your account to access the PENRO - Marinduque Online Wildlife Permitting and Management System (OWPMS) has been created.</p>
    <p>Username: <b>{{ $mailData['username'] }}</b></p>
    <p>Default Password: <b>{{ $mailData['password'] }}</b></p>
    <br>
	<p>Please click the button below to activate your account.</p>

    <p style="padding-top: 10px;padding-bottom: 10px;"><a href="{{ $mailData['activationLink'] }}" class="btn">ACTIVATE ACCOUNT</a></p>
     
    <p><b>For security, this link will expire in 60 minutes.</b> If your link has expired, you can <a href="">request again</a>.</p>
</body>
</html>