<!--

      Copyright (c) 2008-2013 Symplified Inc.
      All rights reserved.

-->
<html>
<head>
<title>Cloud Security Experts | Symplified</title>
<meta name="Author" content="Symplified Inc.">

</head>
<body>
	<div class="toolbar">
		<br> <br>
	</div>
	<table width="300" align="center" cellpadding="5" cellspacing="0">

		<!-- Compute the Error Message -->

	<?php

	// globals
	include '../INCLUDES/vars.php';


	// set the <brand>-auth-error query string correctly
	$strong_auth_command = 'x-singlepoint-strongauth-command';

	// display authorization error
	$error = $_REQUEST[$strong_auth_command];

	$display_strongauth = "true";

	if($error != null) {
		$message = "";

		if ($error == "AUTHENTICATE") {
			$message_title= "Strong Authentication Required";
			$message = "The application you are trying to access requires that you enter the following information.";
		} else if ($error == "REGISTER") {
			$message_title= "Credential Registration Required.<br>";
			$message = "Register your credential.  Enter your information and click continue.";
		} else if ($error == "RESET_CREDENTIAL") {
			$message_title= "Reset Credential<br>";
			$message = "Reset your credential.  Enter two security codes below to reset your credential";
		} else if ($error == "REQUEST_SECURITY_CODE") {
			$message_title= "Request Security Code<br>";
			$message = "To receive a security code, enter the email address you have provided in your account profile.";
		} else {
			$message_title= "Unknown Error";
			$message = "An unknown error occurred.";
			$display_strongauth = "false";
		}

	}


	// set the <brand>-auth-error query string correctly
	$q_portal_event = 'singlepoint-portal-event';

	$portalevent = $_REQUEST[$q_portal_event];

	if($portalevent != null) {
		$message = "";

		if ($portalevent == "strong-auth-failed") {

			$message_title= "Strong Authentication Failed";
			$message = "No strong authentication provider specified in request.";
		}

	}

	print("<tr><td colspan=\"2\"> <span class=\"error-title\"><strong>Error</strong></span></td></tr>");
	print("<tr><td colspan=\"2\"> <span class=\"error-title\"><font size=\"3\" face=\"Arial, Helvetica, sans-serif\">" . $message_title . "</font></span><br>");
	print("<font size=\"2\" face=\"Arial, Helvetica, sans-serif\" class=\"error-content\">" . $message . "</font></td></tr>");



	//Setup all required Strong Auth Headers (BRANDED)
	$strong_auth_fields = 'x-singlepoint-strongauth-fields';
	$strong_auth_id = 'x-singlepoint-strongauth-id';
	$strong_auth_command = 'x-singlepoint-strongauth-command';
	$strong_auth_redirect = 'x-singlepoint-post-strong-auth-redirect';
	$strong_auth_get_page= 'x-singlepoint-strongauth-get-page';

	$json = base64_decode($_REQUEST[$strong_auth_fields]);
	$saname = $_REQUEST[$strong_auth_id];
	$sacommand = $_REQUEST[$strong_auth_command];
	$saredirect = $_REQUEST[$strong_auth_redirect];

	$close_form = "true";
	//we know that we have apps in the array
	if ($json != null) {

		print("<form id=\"form\" action=\"" . %server . "/StrongAuthServlet\" method=\"POST\">");
		print("<tr><td colspan=\"2\" class=\"content_header_gray\"><strong> <span class=\"keychain-title\">$saname</a></span></strong></td></tr>");
		print("<tr class=\"dottedline-horz\"><td height=\"16\" colspan=\"2\" class=\"dottedline-horz\"></td></tr>");

		$ja = (json_decode($json,true));

		for ($i = 0; $i < sizeof($ja); $i++) {

			$name = $ja[$i]["name"];
			$type = $ja[$i]["type"];
			$config = $ja[$i]["config"];

			//Type of text means we have an input field of some sort
			if ($type == "text"){
				$label = $config["label"];
				print("<tr><td><label for=\"" . $name. "\">" . $label . ":</label></td>");
				print("<td><input id=\"" . $name. "\" name=\"" . $name. "\" type=\"" . $type . "\" value=\"\"></td></tr>");
			}
			//type of link means we are done with our input fields and have some links to display
			else if ($type == "link"){

				//First let's close our form from above if we need to

				if ($close_form == "true"){

					//hidden fields.  Not sure if they are needed or not
					print("<tr><td colspan=\"2\"><input type=\"hidden\" name=\"" . $strong_auth_id . "\" value=\"" . $saname . "\" /></td></tr>");
					print("<tr><td colspan=\"2\"><input type=\"hidden\" name=\"" . $strong_auth_command . "\" value=\"" . $sacommand. "\" /></td></tr>");
					print("<tr><td colspan=\"2\"><input type=\"hidden\" name=\"" . $strong_auth_redirect . "\" value=\"" . $saredirect . "\" /></td></tr>");
					print("<tr><td colspan=\"2\"><input type=\"Submit\"></td></tr></form>");
					print("<tr><td colspan=\"2\"></td><tr>");
					print("<tr><td colspan=\"2\"></td><tr>");
					print("<tr><td colspan=\"2\"><b>Account Maintenance and Registration</b></td><tr>");
					$close_form="false";
				}

				// Now we display all links
				$label = $config["label"];
				$href = $config["href"];
				print("<tr><td colspan=\"2\"><a href=\"$href\">$label</a></td></tr>");

			}
		}

		//close out form used to go here
		if ($close_form == "true"){

			//hidden fields.  Not sure if they are needed or not
			print("<tr><td colspan=\"2\"><input type=\"hidden\" name=\"" . $strong_auth_id . "\" value=\"" . $saname . "\" /></td></tr>");
			print("<tr><td colspan=\"2\"><input type=\"hidden\" name=\"" . $strong_auth_command . "\" value=\"" . $sacommand. "\" /></td></tr>");
			print("<tr><td colspan=\"2\"><input type=\"hidden\" name=\"" . $strong_auth_redirect . "\" value=\"" . $saredirect . "\" /></td></tr>");
			print("<tr><td colspan=\"2\"><input type=\"Submit\"></td></tr></form>");
			print("<tr><td colspan=\"2\"></td><tr>");
			print("<tr><td colspan=\"2\"></td><tr>");
			print("<tr><td colspan=\"2\"><b>Account Maintenance and Registration</b></td><tr>");
			$close_form="false";
		}
	}
	?>


	</table>

</body>
</html>
