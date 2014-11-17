<!doctype html>
<!--

      Copyright (c) 2008-2013 Symplified Inc.
      All rights reserved.

-->
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
  <title>MyGLG</title>
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css">
  <link rel="stylesheet" type="text/css" href="css/main.css">
  <style>
  body, #icons-background
  {
  background: url(<?php displayBackground();?>) no-repeat center center fixed;
  /*<?php displayBackground();?>*/
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
  background-position: center bottom;
  background-attachment: fixed;
  height: 100%;
  font-family: 'Helvetica Nueu', Helvetica, sans-serif;
  text-align: center;
  font-size: 1.6vh;
  font-weight: 300;
  margin: 0px;
  padding: 0px;
  }

#icons_container {
  position: relative;
}

#icons-background {
 /* Absolutely position it, but stretch it to all four corners, then put it just behind #search's z-index */
  position: absolute;
  top: 0px;
  right: -15px;
  bottom: 0px;
  left: -15px;
  z-index: 99;
  /* Pull the background 70px higher to the same place as #bg's */
  background-position: center -0px;
  padding: 0px;
  margin: 0px!important;
  filter: blur(3px);
  -webkit-filter: blur(3px);
  -moz-filter: blur(3px);
  filter: url('#blur'); /* for Firefox */
}

#icons-themselves {
    /* Put this on top of the blurred layer */
  position: relative;
  z-index: 100;
  background: rgb(0,0,0); /* for IE */
  background: rgba(0,0,0,0.65);
  padding-top: 3%;
}

  </style>
</head>

<body>
<?php include_once ("analyticstracking.php")?>
<div class="container">
  <div id="title" class="row">
    <img src="img/LOGO_MYGLG.svg"></img>
  </div>

  <div id="icons_container" >
    <div id="icons-background"><svg xmlns="http://www.w3.org/2000/svg"  version="1.1">
<defs>
<filter id="blur">
<feGaussianBlur stdDeviation="5"/>
</filter>
</defs>
</svg></div>
      <div id="icons-themselves" class="row">
<?php

if (!function_exists('getallheaders')) {
	function getallheaders() {
		$headers = '';
		foreach ($_SERVER as $name => $value) {
			if (substr($name, 0, 5) == 'HTTP_') {
				$headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
			}
		}
		return $headers;
	}
}

function getUsername($email_address) {
	$url = 'http://admttu.glgroup.com/getusername';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "email=$email_address");
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $url);

	$res = curl_exec($ch);

	curl_close($ch);

	return $res;
}

function getOffice($email_address) {
	$url = 'http://admttu.glgroup.com/getofficelocation';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "email=$email_address");
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $url);

	$res = curl_exec($ch);

	curl_close($ch);

	return $res;
}

function getVegaToken($username) {
	$url = 'http://brewmaster.glgroup.com/superproxy/encrypt/'.$username.':AD/12h';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'HEAD');
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	curl_setopt($ch, CURLOPT_NOBODY, true);
	curl_setopt($ch, CURLOPT_URL, $url);

	$res = curl_exec($ch);

	curl_close($ch);

	$head = preg_replace('/\s+/', ' ', trim($res));

	$ptn = "/X-glgEnDecResult: (.+) X-glgEnDecResultSize/";
	preg_match($ptn, $head, $matches);

	return $matches[1];
}

function writeSinglePointLinkHtml($spApps, $appName, $style) {
	if ($spApps != null) {
		for ($i = 0; $i < sizeof($spApps); $i++) {
			$app = $spApps[$i];
			if ($appName == $app['text']) {
				$partialFileName = strtolower($app["text"]);
				$imageFileName   = strtolower($partialFileName.".svg");
				writeLinkHtml($app["url"], $imageFileName, $app["text"], $style);
				return true;
			}
		}
		return false;
	}
	return false;
}

function writeLinkHtml($appUrl, $imageFileName, $appText, $style) {

	// Use the default image if a bad path is given
	$imgPath = "./img/";
	$path    = $imgPath.$imageFileName;
	if (!file_exists($path)) {
		$path = $imgPath."default.svg";
	}

	//print icon image
	print("<div class=\"col-sm-2 icon col-xs-1\"><a rel=\"noreferrer\" href=\"".$appUrl."\" target=\"_blank\">"."<img style=\"".$style."\" src=\"".$path."\" onclick=\"ga('send','event', 'Outgoing Links','".$appText."','icon')\"></a><br>");

	//print app name text
	print("<p class=\"content-text\"><a rel=\"noreferrer\" href=\"".$appUrl."\" target=\"_blank\" onclick=\"ga('send','event', 'Outgoing Links','".$appText."','text')\">".$appText."</a></p>"."</div>\n");
}

function console_log($data) {
	if (is_array($data)) {
		$output = "<script>console.log( 'portal.php Error Msg: ".implode(',', $data)."' );</script>";
	} else {

		$output = "<script>console.log( 'portal.php Error Msg: ".$data."' );</script>";
	}

	echo $output;
}

// display links to applications provided by the request header
$spApps = null;
$email  = null;
$uname  = null;
$oLoca  = null;

foreach (getallheaders() as $name => $value) {
	if (strtolower($name) == "x-singlepoint-username") {
		$email = $value;
	}
	if (strtolower($name) == "x-singlepoint-applications") {
		$spApps = json_decode($value, true);
	}
	if (strtolower($name) == "referer") {
		$referer = $value;
	}
}

try {
	// Convert the provided email into an AD username
	$uname   = getUsername($email);
	$uname_l = strtolower($uname);

	// Convert the provided email into an AD office location
	$oLoca   = getOffice($email);
	$oLoca_l = str_replace(' ', '_', $oLoca);

	// Create token for Vega user
	$vega_token = getVegaToken($uname_l);

	//set theUser2 auth cookie to expire in 8hrs, true means secure only
	setrawcookie("theUser2", $vega_token, null, "/", ".glgroup.com", true);
	setrawcookie("glgSAM", $uname_l, null, "/", ".glgroup.com", true);

	// cookie for HR app
	setrawcookie("glgOffice", $oLoca_l, null, "/", ".glgroup.com", true);
} catch (Exception $e) {
	$e_msg = $e->getMessage();

	print("<br><br>Unable to load all icons.<br><br>Please contact Helpdesk.<br><br>");
	console_log("Error making api calls to admttu. -- Caught exception: ".$e_msg);
}

// cookie for MeetingRooms app
setrawcookie("glgMR", $email, null, "/", ".glgroup.com", true);

if (strpos($referer, 'redirect_url') !== false) {
	$str = explode('=', $referer, 2);

	// Code for vega urls
	if (strpos($referer, 'vega.glgroup.com') !== false) {
		if (strpos($str[1], '?') !== false) {
			$redirect_url = $str[1];
			header("Location: ".$redirect_url);
			exit;
		} elseif (strpos($str[1], '/vega/index.aspx') !== false) {
			$redirect_url = $str[1];
			header("Location: ".$redirect_url);
			exit;
		} elseif (strpos($str[1], '/vega') !== false) {
			$redirect_url = $str[1];
			header("Location: ".$redirect_url);
			exit;
		} else {
			//user probably just typed in 'vega.glgroup.com'
			$redirect_url = $str[1].'vega/index.aspx';
			header("Location: ".$redirect_url);
			exit;
		}
	} else {
		// for all other urls
		$redirect_url = $str[1];
		header("Location: ".$redirect_url);
		exit;
	}
}

// Uncomment to emulate internal SSO app list
// $spAppsText = file_get_contents('sso_example.json');
// $spApps     = json_decode($spAppsText, true);

// Add apps specified in portal_content.json
$jsonAppsText   = file_get_contents('portal_content.json');
$jsonApps       = json_decode($jsonAppsText, true);
$renderedSpApps = array();
if (($uname != null && $uname != "error") && ($jsonApps["apps"] != null)) {
	for ($i = 0; $i < sizeof($jsonApps["apps"]); $i++) {
		$app = $jsonApps["apps"][$i];

		if ($app["url"] != "" && $app["icon"] != "") {
			writeLinkHtml($app["url"], $app["icon"], $app["text"], $app["style"]);
		} else {
			$success                      = writeSinglePointLinkHtml($spApps, $app['text'], $app['style']);
			$renderedSpApps[$app['text']] = true;
		}
	}
}

//BEGIN RANDOM BACKGROUND

$val = null;

function displayBackground() {
	$dir     = 'img/bg/';
	$cnt     = 0;
	$bgArray = array();

	/*if we can load the directory*/
	if ($handle = opendir($dir)) {

		/* Loop through the directory here */
		while (false !== ($entry = readdir($handle))) {

			$pathToFile = $dir.$entry;
			if (is_file($pathToFile))//if the files exists
			{

				//make sure the file is an image...there might be a better way to do this
				if (getimagesize($pathToFile) != FALSE) {
					//add it to the array
					$bgArray[$cnt] = $pathToFile;
					$cnt           = $cnt+1;

				}

			}

		}
		//create a random number, then use the image whos key matches the number
		$myRand = rand(0, ($cnt-1));
		$val    = $bgArray[$myRand];

	}
	closedir($handle);
	echo ('"'.$val.'"');

}
// END RANDOM BACKGROUND

// Add remaining Singlepoint apps
if ($spApps != null) {
	for ($i = 0; $i < sizeof($spApps); $i++) {
		$app = $spApps[$i];
		if ($renderedSpApps[$app["text"]] == false) {
			$partialFileName = strtolower($app["text"]);
			$imageFileName   = strtolower($partialFileName.".svg");
			writeLinkHtml($app["url"], $imageFileName, $app["text"], "");
		}
	}
}
?>
    </div>
    </div>
    <div id="controls" class="row">
      <div class="col-sm-12">
        <a href="https://my.glgroup.com/LogoutServlet" class="logout">Log Out</a>
        <a href="password.php?singlepoint-auth-error=PASSWORD_CHANGE_REQUESTED&singlepoint-auth-user-name=<?php echo urlencode($email);?>" style="display:none">Change Password</a>
      </div>
    </div>
  </div>
</body>
</html>
<script src="js/jquery-2.1.1.min.js"></script>
<script src="js/bootstrap.js"></script>
