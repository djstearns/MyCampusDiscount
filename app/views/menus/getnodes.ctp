<?php

$data = array();

foreach ($nodes as $node){
	$data[] = array(
		"text" => $node['Menu']['name'],
		"id" => $node['Menu']['id'],
		"cls" => "folder",
		"leaf" => ($node['Menu']['lft'] + 1 == $node['Menu']['rght'])
	);
}

echo $javascript->object($data);

?>