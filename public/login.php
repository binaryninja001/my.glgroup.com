<!doctype html>
<!--

      Copyright (c) 2008-2013 Symplified Inc.
      All rights reserved.

-->
<html>
<head>
  <title>MyGLG Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css">
  <link rel="stylesheet" type="text/css" href="css/main.css">
</head>

<body>

<div class="container">
  <div class="row">
    <div class="col-sm-12">
      <img src="./img/glg-sso-logo.png" alt="" class="logo">
    </div>
  </div>

<div class="row">
  <div class="col-sm-12">
<form method="post" action="https://my.glgroup.com/LoginServlet">


<?php
	//print $_REQUEST;

	if (!function_exists('getallheadersS'))
	{
	    function getallheadersS()
	    {
	           $headers = '';
	           print("SERVER:\n");
	       foreach ($_SERVER as $name => $value)
	       {
	           //if (substr($name, 0, 5) == 'HTTP_')
	           //{
	           //    $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
	           //}
	           print($name);
	           print(": ");
	           print($value);
	           print("\n");
	       }
	       //return $headers;
	    }
	}

	if (!function_exists('getallheadersR'))
	{
	    function getallheadersR()
	    {
	           $headers = '';
	           print("RESPONSE:\n");
	       foreach ($http_response_header as $name => $value)
	       {
	           //if (substr($name, 0, 5) == 'HTTP_')
	           //{
	           //    $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
	           //}
	           print($name);
	           print(": ");
	           print($value);
	           print("\n");
	       }
	       //return $headers;
	    }
	}
	
	function console_log($data) {
		if ( is_array($data) )
			$output = "<script>console.log( 'login.php Error Msg: " . implode( ',', $data) . "' );</script>";
		else
			$output = "<script>console.log( 'login.php Error Msg: " . $data . "' );</script>";
	
		echo $output;
	}

//	getallheadersS();
//	getallheadersR();

	// determine target url, will default to portal if none is provided
	$redirectUrl = $_REQUEST["singlepoint-next-redirect"];
	if($redirectUrl != null) {
		print("<input name=\"singlepoint-next-redirect\" type=\"hidden\" value=\"" . $redirectUrl . "\" />");
	}
?>



<?php
	// display authorization error
	$error = $_REQUEST["singlepoint-auth-error"];
	if($error != null) {
		$message = "";
		if ($error == "COOKIE_EXPIRED") {
			$message = "Your session has expired.";
		} else if($error == "NOT_AUTHENTICATED") {
			$message = "Invalid login credentials.";
		} else if($error == "PASSWORD_RESET_REQUIRED") {
			$message = "Your password has expired.";
			$query = $_SERVER["QUERY_STRING"];
			$url = "password.php?" . $query;
			$reset_pass = "<br><br>Click here to reset:<br><a href=\"" . $url . "\">Reset Password</a>";
			//$message = $message . $reset_pass;
			//$hd = "<br><br>Please contact Helpdesk for assistance.<br><a href=\"" . $url . "\" style=\"display:none\">Reset Password</a>";
			$message = $message . $reset_pass;
		} else {
			$message = "An unknown error occurred.<br><br>Please contact Helpdesk.";
			console_log("Invalid param value -- singlepoint-auth-error");
		}
		print("<tr><td colspan=\"2\"><br><br>" . $message . "<br><br></td></tr>\n");
	}
?>
    <div><input name="singlepoint-username" type="text" placeholder="Email" class="uname" autofocus/></div>
    <div><input name="singlepoint-password" type="password" placeholder="Password" class="pass"/></div>
    <input type="submit" value="Log In" class="button"/>
  </div>
</div>
<div class="row">
  <div class="col-sm-12">
    <div class="disclaimer">
      This computer system is for authorized use only. Improper use of this system is strictly prohibited and could result in criminal prosecution. Your use of this system may be monitored and recorded at any time without further notice to you. Removal of data from this system is strictly forbidden.
    </div>
  </div>
</div>

</form>
</div>

</body>
</html>
<script type="text/javascript" src="lib/js/jquery.min.js"></script>
<script type="text/javascript" src="lib/js/placeholders.js"></script>
<script>
  $(function() {
    if($.browser.msie){
      setTimeout(function(){
        $('input').blur();
      },100);
    }
  });

</script>
