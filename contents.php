<?php 

$method=$_GET['method'];
$id=$_GET['id'];

if(strlen($method)==0 && strlen($id)==0){
	require('cata.php');
}else{
	if($method=='read' && is_numeric($id)){
		header("location:read.php?av=$id");
	}else{
		header("location:index.php");
	}
}

?>