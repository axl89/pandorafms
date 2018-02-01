<?php

// Pandora FMS - http://pandorafms.com
// ==================================================
// Copyright (c) 2005-2011 Artica Soluciones Tecnologicas
// Please see http://pandorafms.org for full contribution list

// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; version 2

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.

global $config;
check_login ();
ui_require_css_file ('firts_task');
?>
<?php 

ui_print_info_message ( array('no_close'=>true, 'message'=>  __('There are no clusters defined yet.') ) ); 
?>

<div class="new_task">
	<div class="image_task">
		<?php echo html_print_image('images/firts_task/icono-cluster-activo.png', true, array("title" => __('Clusters')));?>
	</div>
	<div class="text_task">
		<h3> <?php echo __('Create Cluster'); ?></h3>
		<p id="description_task"> <?php
    
    echo __('A cluster is a group of devices that provide the same service in high availability.').'<br><br>';
    
    echo __('Depending on how they provide that service, we can find two types:').'<br><br>';
    
    echo __('<b>Clusters to balance the service load</b>: these are  active - active (A/A)  mode clusters. It means that all the nodes (or machines that compose it) are working. They must be working because if one stops working, it will overload the others.').'<br><br>';
    
    echo __('<b>Clusters to guarantee service</b>: these are active - passive (A/P) mode clusters. It means that one of the nodes (or machines that make up the cluster) will be running (primary) and another won\'t (secondary). When the primary goes down, the secondary must take over and give the service instead. Although many of the elements of this cluster are active-passive, it will also have active elements in both of them that indicate that the passive node is "online", so that in the case of a service failure in the master, the active node collects this information.');
    
    ?></p>
		<form action="index.php?sec=reporting&sec2=enterprise/godmode/reporting/cluster_builder&step=1" method="post">
			<input type="submit" class="button_task" value="<?php echo __('Create Cluster'); ?>" />
		</form>
	</div>
</div>