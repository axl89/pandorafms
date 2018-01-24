<?php

global $config;

check_login ();

if (! check_acl ($config['id_user'], 0, "AR")) {
	db_pandora_audit("ACL Violation", "Trying to access agent main list view");
	require ("general/noaccess.php");
	
	return;
}

require_once ('include/functions_agents.php');
require_once('include/functions_clusters.php');

$id_cluster = get_parameter('id',0);

$buttons['list'] = array('active' => false,
  'text' => '<a href="index.php?sec=estado&sec2=enterprise/operation/cluster/cluster">' . 
    html_print_image("images/list.png", true, array ("title" => __('Clusters list'))) .'</a>');
    
    
$buttons['editor'] = array('active' => false,
  'text' => '<a href="index.php?sec=estado&sec2=godmode/reporting/cluster_builder.cluster_editor&id='.$id_cluster.'">' . 
    html_print_image("images/gm_setup.png", true, array ("title" => __('Cluster editor'))) .'</a>');
    
    
ui_print_page_header ( __("Cluster detail").' » '.clusters_get_name($id_cluster), "images/agent_mc.png", false, "agent_status", false, $buttons);



echo "<table style='width:100%;'>";
  echo "<tr>";

    echo "<td style='border:1px solid black;min-width:800px;min-height:800px;vertical-align: top;'>";
      echo "<div id='cluster_map' style='width:600px;height:600px;'></div>";
    echo "</td>";

    echo "<td style='min-width:600px;min-height:600px;vertical-align: top;'>";

      echo "<div style='width:50%;background-color:red;float:left;'>";
        // 
        // echo "<div style='float:left;width:100px;margin-left:20px;margin-top:20px;font-size:2em;text-align:center;'>".__('CLUSTER STATUS')."</div>";
        // echo "<div style='float:left;width:100px;margin-left:50px;margin-top:20px;height:50px;background-color:#82b92e;'></div>";
        // 
        // echo "<div style='float:left;width:300px;margin-left:50px;margin-top:20px;height:400px;background-color:#82b92e;'></div>";

      echo "</div>";

      echo "<div style='width:50%;background-color:blue;float:right;'>";
        // 
        // echo "<div style='float:left;width:100px;px;margin-top:20px;font-size:2em;text-align:center;'>".__('LAST UPDATE')."</div>";
        // echo "<div style='float:left;width:220px;margin-left:20px;margin-top:30px;font-size:1.5em;text-align:center;'>88 Hours 88 Min 88 sec ago</div>";

      echo "</div>";


          
      

    echo "</td>";

    echo "</tr>";

    echo "<tr>";
    echo "<td colspan='2' style='min-width:600px;min-height:600px;vertical-align: top;'>
    <div id='cluster_modules' style='min-height:150px;'></div>
    </td>";
  echo "</tr>";
echo "</table>";


echo 'El estado del modulo del cluster '.clusters_get_name($id_cluster).' es '.agents_get_status(40);

?>