<?php

// Pandora FMS - http://pandorafms.com
// ==================================================
// Copyright (c) 2005-2010 Artica Soluciones Tecnologicas
// Please see http://pandorafms.org for full contribution list

// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation for version 2.
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.


// Load global vars
global $config;
require_once($config['homedir'] . "/include/functions_snmp_browser.php");
ui_require_javascript_file ('pandora_snmp_browser');

// AJAX call
if (is_ajax()) {
	
	// Read the action to perform
	$action = (string) get_parameter ("action", "");
	$target_ip = (string) get_parameter ("target_ip", '');
	$community = (string) get_parameter ("community", '');
	$snmp_version = (string) get_parameter ("snmp_browser_version", '');
	$snmp3_auth_user = get_parameter('snmp3_browser_auth_user');
	$snmp3_security_level = get_parameter('snmp3_browser_security_level');
	$snmp3_auth_method = get_parameter('snmp3_browser_auth_method');
	$snmp3_auth_pass = get_parameter('snmp3_browser_auth_pass');
	$snmp3_privacy_method = get_parameter('snmp3_browser_privacy_method');
	$snmp3_privacy_pass = get_parameter('snmp3_browser_privacy_pass');
	
	// SNMP browser
	if ($action == "snmptree") {
		$starting_oid = (string) get_parameter ("starting_oid", '.');
		
		$snmp_tree = snmp_browser_get_tree ($target_ip, $community, $starting_oid, $snmp_version,
		                                    $snmp3_auth_user, $snmp3_security_level, $snmp3_auth_method, $snmp3_auth_pass, $snmp3_privacy_method, $snmp3_privacy_pass);
		if (! is_array ($snmp_tree)) {
			echo $snmp_tree;
		}
		else {
			snmp_browser_print_tree ($snmp_tree);
		}
		return;
	}
	// SNMP get
	else if ($action == "snmpget") {
		$target_oid = htmlspecialchars_decode (get_parameter ("oid", ""));
		$custom_action = get_parameter ("custom_action", "");
		if ($custom_action != "") {
			$custom_action = urldecode (base64_decode ($custom_action));
		}
		
		$oid = snmp_browser_get_oid ($target_ip, $community,
			$target_oid, $snmp_version, $snmp3_auth_user,
			$snmp3_security_level, $snmp3_auth_method, $snmp3_auth_pass,
			$snmp3_privacy_method, $snmp3_privacy_pass);
		snmp_browser_print_oid ($oid, $custom_action);
		return;
	}
	
	return;
}

// Check login and ACLs
check_login ();
if (! check_acl ($config['id_user'], 0, "AR")) {
	db_pandora_audit("ACL Violation",
		"Trying to access SNMP Console");
	require ("general/noaccess.php");
	exit;
}

// Header
$url = 'index.php?sec=snmpconsole&sec2=operation/snmpconsole/snmp_browser&pure=' . $config["pure"];
if ($config["pure"]) {
	// Windowed
	$link['text'] = '<a target="_top" href="'.$url.'&pure=0&refr=30">' . html_print_image("images/normal_screen.png", true, array("title" => __('Normal screen')))  . '</a>';
}
else {
	// Fullscreen
	$link['text'] = '<a target="_top" href="'.$url.'&pure=1&refr=0">' . html_print_image("images/full_screen.png", true, array("title" => __('Full screen'))) . '</a>';
}
ui_print_page_header (__("SNMP Browser"), "images/op_snmp.png", false, "", false, array($link));

// SNMP tree container
snmp_browser_print_container ();

?>
