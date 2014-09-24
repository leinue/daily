<?php require("header.php"); ?>

<?php

$id=test_input($_GET['av']);

if(strlen($id)==0 || !is_numeric($id)){
	header("location:index.php");
}else{
	$im=infoMgr::getInstance();
	$tm=new detailMgr($im);
	$gb=new getBase();
	$url=new urlMgr();

	$urlAvailable=$url->getContextUrl($id);
	$jsonData=$tm->getJSON($gb,$urlAvailable);
	$jsonArray=$tm->getContext(new decodeJSON($jsonData));

	$dataParser=new DataObj($jsonArray,DataObj::BODY);
	$stories=$dataParser->getSingleNew();
	if(!$stories){
		header("location:index.php");
	}else{
		//print_r($stories);
		//echo "<img src=\"".$dataParser->getImages()."\">";
?>
		<div class="read-content">
			<div class="read-left">
				<div class="read-title">
					<p>羽翼</p>
					<div class="help-info">
						<ul>
							<li>1</li>
							<li>2</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="read-right">
				
			</div>
		</div>
<?php
	}
}

?>

<?php require("footer.php"); ?>