<?php

use \Catalyst\Controller;
use \Catalyst\User\User;

spl_autoload_register("\\Catalyst\\Controller::loadClass");

// Choose what error handling we want to do
if (Controller::isDevelMode()) {
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	ini_set('scream.enabled', 1);
} else {
	error_reporting(0);
	ini_set('display_errors', 0);
	ini_set('display_startup_errors', 0);
	ini_set('scream.enabled', 0);
}

set_error_handler("\\Catalyst\\Controller::handleError", E_ALL);

// LEGACY
require_once __DIR__."/Secrets.php";
require_once __DIR__."/Database/Connector.inc.php";

require_once __DIR__."/vendor/autoload.php";

if (!session_id()) {
	ini_set("session.cookie_lifetime", 24*60*60);
	ini_set("session.gc_maxlifetime", 24*60*60);
	ini_set("session.name", "catalyst");
	session_start();
}

// remove pending_user if not actively logging in
if (User::isPending2FA()) {
	if (strpos(strrev($_SERVER["SCRIPT_NAME"]), strrev("/api/internal/totp_login/index.php")) !== 0 && 
		strpos(strrev($_SERVER["SCRIPT_NAME"]), strrev("/Login/TOTP/index.php")) !== 0 &&
		strpos(strrev($_SERVER["SCRIPT_NAME"]), strrev("/css/color.css.php")) !== 0 &&
		strpos(strrev($_SERVER["SCRIPT_NAME"]), strrev("/js/main.js.php")) !== 0) {
		unset($_SESSION["pending_user"]);
	}
}
