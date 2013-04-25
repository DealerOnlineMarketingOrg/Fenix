<?php
	function force_ssl() {
		if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != "on") {
			$url = "https://". $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
			redirect($url);
			exit;
		}
	}
	
	function app_url() {
		$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
		$sp = strtolower($_SERVER["SERVER_PROTOCOL"]);
		$protocol = substr($sp, 0, strpos($sp, "/")) . $s;
		$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
		return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
	}
	
	function get_protocol() {
		$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
		$sp = strtolower($_SERVER["SERVER_PROTOCOL"]);
		return substr($sp, 0, strpos($sp, "/")) . $s;
	}

?>