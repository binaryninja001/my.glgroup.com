<!--

      Copyright (c) 2008-2013 Symplified Inc.
      All rights reserved. 

-->
<html>
<head>
<title>Access Error</title>
</head>
<body>
<h3>Access Error</h3>
<table>

	<!-- error message -->
	<?php
		// display authorization error
		$error = $_REQUEST["singlepoint-auth-error"];
		if($error != null) {
			$message = "";
			if ($error == "DENY") {
				$message = "You are not authorized to view the page that was requested.";
			} else if ($error == "APPLICATION_AUTHENTICATION_ERROR") {
				$message = "Your single sign-on credentials do not match those required by this application.";
			} else if ($error == "KEYCHAIN_AUTHENTICATION_ERROR") {
				$message = "The credentials you have provided do not match those required by this application.";
			} else {
				$message = "An unknown error occurred.";
			}
			print("<tr><td>" . $message . "</td></tr>\n");
		}
	?>

	<!-- logout link -->
	<tr>
		<td><a href="https://my.glgroup.com/LogoutServlet">Log Out</a></td>
	</tr>

</table>
</body>
</html>
