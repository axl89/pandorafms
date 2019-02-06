<?php
// Pandora FMS - http://pandorafms.com
// ==================================================
// Copyright (c) 2005-2009 Artica Soluciones Tecnologicas
// Please see http://pandorafms.org for full contribution list
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation for version 2.
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
global $config;

enterprise_include_once('include/functions_dashboard.php');
require_once 'include/functions_visual_map.php';
enterprise_include_once('include/functions_visual_map.php');

$public_hash = get_parameter('hash', false);
$id_visual_console = get_parameter('id_visual_console', null);

// Try to authenticate by hash on public dashboards
if ($public_hash === false) {
    // Login check
    check_login();
} else {
    $validate_hash = enterprise_hook(
        'dasboard_validate_public_hash',
        [
            $public_hash,
            $id_visual_console,
            'visual_console',
        ]
    );
    if ($validate_hash === false || $validate_hash === ENTERPRISE_NOT_HOOK) {
        db_pandora_audit('Invalid public hash', 'Trying to access report builder');
        include 'general/noaccess.php';
        exit;
    }
}

// Fix: IW was the old ACL to check for report editing, now is RW
if (! check_acl($config['id_user'], 0, 'VR')) {
    db_pandora_audit(
        'ACL Violation',
        'Trying to access report builder'
    );
    include 'general/noaccess.php';
    exit;
}


// Fix ajax to avoid include the file, 'functions_graph.php'.
$ajax = true;

$render_map = (bool) get_parameter('render_map', false);
$graph_javascript = (bool) get_parameter('graph_javascript', false);

if ($render_map) {
    $width = (int) get_parameter('width', '400');
    $height = (int) get_parameter('height', '400');
    $keep_aspect_ratio = (bool) get_parameter('keep_aspect_ratio');

    visual_map_print_visual_map(
        $id_visual_console,
        true,
        true,
        $width,
        $height,
        '',
        false,
        $graph_javascript,
        $keep_aspect_ratio
    );
    return;
}
