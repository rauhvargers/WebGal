<?php 
header("Content-type:application/json; charset=utf-8");

$ret = array();
foreach ($galleries as $key => $value) { 
   $ret[]=array("label"=>$value, "value"=>$key ); 
   
}
 	
echo json_encode($ret);