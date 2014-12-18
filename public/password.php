<!doctype html>
<html>

<head>
  <title>Change Password</title>
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css">
  <link rel="stylesheet" type="text/css" href="css/main.css">
</head>

<body style="text-align: center;">
  <div class="main">
    <img src="img/LOGO_MYGLG.svg"></img>
<?php
function console_log($data) {
	if (is_array($data)) {
		$output = "<script>console.log( 'password.php Error Msg: ".implode(',', $data)."' );</script>";
	} else {

		$output = "<script>console.log( 'password.php Error Msg: ".$data."' );</script>";
	}

	echo $output;
}

function submitData($username, $old_pass, $new_pass, $user_req_chg) {
	$url = 'http://admttu.glgroup.com/changePassword';

	$arr  = array('username' => $username, 'oldPassword' => $old_pass, 'newPassword' => $new_pass, 'userRequestedChange' => $user_req_chg);
	$body = json_encode($arr);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: '.strlen($body))
	);

	$res = curl_exec($ch);

	curl_close($ch);

	return $res;
}

function showFields($uname, $userReq) {
	?>
		    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" onsubmit="return checkResetForm(this);">
		      <div>
		        <input name="user_name" type="text" placeholder="Email" class="uname" value="<?php echo $uname;?>"/>
		      </div>
		      <div>
		        <input name="old_password" type="password" placeholder="Old Password" class="old-pass" autofocus/>
		      </div>
		      <div>
		        <input name="new_password" type="password" placeholder="New Password" class="new-pass1"/>
		      </div>
		      <div>
		        <input name="confirm_new_password" type="password" placeholder="Confirm New Password" class="new-pass2"/>
		      </div>
		      <div>
		        <input name="user_requested_change" type="hidden" value="<?php echo $userReq;?>"/>
		      </div>
		      <div>
		        <input type="submit" value="Submit" class="button"/>
		      </div>
		    </form>

	<?php
}

$postError     = "";
$message       = "";
$fieldsNeeded  = false;
$userRequested = "false";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$post_user_name             = "";
	$post_old_password          = "";
	$post_new_password          = "";
	$post_user_requested_change = "";

	if (isset($_POST["user_name"])) {$post_user_name                         = $_POST["user_name"];}
	if (isset($_POST["old_password"])) {$post_old_password                   = $_POST["old_password"];}
	if (isset($_POST["new_password"])) {$post_new_password                   = $_POST["new_password"];}
	if (isset($_POST["user_requested_change"])) {$post_user_requested_change = $_POST["user_requested_change"];}

	$params = "UN: ".$post_user_name." OP: ".$post_old_password." NP: ".$post_new_password." User_Req: ".$post_user_requested_change;

	if ((empty($post_user_name) || empty($post_old_password) || empty($post_new_password) || empty($post_user_requested_change)) != true) {
		try {
			$changeResult = submitData($post_user_name, $post_old_password, $post_new_password, $post_user_requested_change);

			if (strpos($changeResult, 'Success') === false) {
				// check for error code 19; occurs when old password is incorrect or new password does not meeting AD requirements
				if (strpos($changeResult, '19') !== false) {
					$fieldsNeeded  = true;
					$userRequested = $post_user_requested_change;

					$message = "Password change failed!<br><br>";
					$message = $message."Please make sure your old password was entered correctly and your new password adheres to the following:<br><br>";
					$message = $message."Minimum length 8 characters<br>";
					$message = $message."Require 3 of the following 4 character types:<br>";
					$message = $message."Lowercase<br>";
					$message = $message."Uppercase<br>";
					$message = $message."Numeric<br>";
					$message = $message."Special characters";
					console_log("Bad old password / new password. -- Message from admttu: ".$changeResult);
				} else {
					$message = "An unexpected error occurred.<br><br>Please contact Helpdesk.";
					console_log("Unknown error code returned from api call to admttu. -- Message from admttu: ".$changeResult);
				}
			} else {
				$message = "Password was successfully changed!<br><br>";
				$message = $message."<a href=\"http://my.glgroup.com\" style=\"color: #0000FF\">Return to portal</a>";
				//console_log("Success changing your password. -- Message from admttu: " . $changeResult);
			}
		} catch (Exception $e) {
			$e_msg = $e->getMessage();

			$message = "An unexpected error occurred.<br><br>Please contact Helpdesk.";
			console_log("Error making api call to admttu. -- Caught exception: ".$e_msg);
		}
	} else {
		$message = "An unexpected error occurred.<br><br>Please contact Helpdesk.";
		//console_log("One of the required params was not provided. -- " . $params);
	}
} else {
	$message = "Your password has expired.";
	?>
	<!-- next redirect parameter -->
	<?php
	// determine target url, will default to portal if none is provided
	$redirectUrl = $_REQUEST["singlepoint-next-redirect"];
	if ($redirectUrl != null) {
		print("<input name=\"singlepoint-next-redirect\" type=\"hidden\" value=\"".$redirectUrl."\" />");
	}
	?>
	<!-- error message -->
	<?php
	// display authorization error
	$error = NULL;
	if (isset($_REQUEST["singlepoint-auth-error"])) {$error = $_REQUEST["singlepoint-auth-error"];}

	if ($error != null) {
		if ($error == "COOKIE_EXPIRED") {
			$message = "Your session has expired.";
		} else if ($error == "NOT_AUTHENTICATED") {
			$message = "Invalid login credentials.";
		} else if ($error == "PASSWORD_RESET_REQUIRED") {
			$fieldsNeeded = true;

			$sp_user_name                                                      = "";
			if (isset($_REQUEST["singlepoint-auth-user-name"])) {$sp_user_name = $_REQUEST["singlepoint-auth-user-name"];}
		} else if ($error == "PASSWORD_CHANGE_REQUESTED") {
			$fieldsNeeded  = true;
			$userRequested = "true";

			$sp_user_name                                                      = "";
			if (isset($_REQUEST["singlepoint-auth-user-name"])) {$sp_user_name = $_REQUEST["singlepoint-auth-user-name"];}
		} else {
			$message = "An unknown error occurred.<br><br>Please contact Helpdesk.";
			console_log("Invalid param value -- singlepoint-auth-error");
		}
	} else {
		$message = "You have reached this page in error.<br><br><a href=\"http://my.glgroup.com\">Return to portal</a>";
		console_log("Missing URL param value -- singlepoint-auth-error");
	}

}
if ($message != "") {
	print("<tr><td colspan=\"2\"><br><br>".$message."<br><br></td></tr>\n");
}

if ($fieldsNeeded === true) {
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		showFields($post_user_name, $userRequested);
	} else {
		showFields($sp_user_name, $userRequested);
	}
}
?>
</div>
</body>

<script type="text/javascript" src="js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="js/placeholders.js"></script>
<script type="text/javascript" src="js/password.js"></script>

</html>
