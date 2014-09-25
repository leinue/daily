<?php require("header.php"); ?>

<?php

$date=test_input($_GET['av']);

if(!(strlen($date)==0)){
	date_default_timezone_set("UTC");
	$nowDate=date("Ymd");

	if($date<20130520 || $date>$nowDate){
		header("location:index.php");
	}

	$im=infoMgr::getInstance();
	$tm=new detailMgr($im);
	$gb=new getBase();

	$url=new urlMgr();
	$urlAvailable=$url->getBeforeUrl($date);
	$jsonData=$tm->getJSON($gb,$urlAvailable);
	$jsonArray=$tm->getContext(new decodeJSON($jsonData));

	//print_r($jsonArray);

	$dataParser=new DataObj($jsonArray,DataObj::_LIST);
	//echo $dataParser->getDate();

	$stories=$dataParser->getStories();

	echo '<div class="body-content">';
	foreach ($stories as $key => $value) {
		$singleNew=$dataParser->getSingleNew($key);
		echo '			<div class="content-main-box">
			<div class="main-box-img">
				<img src="'.$dataParser->getImages().'" alt="'.$dataParser->getID().'" />
			</div>
			<div class="main-box-content">
				<div class="main-title">
					<a href="read.php?av='.$dataParser->getID().'">'.$dataParser->getTitle().'</a>
				</div>
				<div class="main-time">'.$dataParser->getDate().'</div>
			</div>
			<div class="main-box-footer">
				<div class="main-box-postscript">来自:<a href="http://zhihu.com">zhihu.com</a></div>
			</div>
		</div>';
	}
	echo '</div>';

}else{
	$dm=new dateMgr();
	$allDate=$dm->getAllDate();

	foreach ($allDate as $key => $value) {
		echo '		<div class="body-content">
			<div class="before-box">
				<p><a href="before.php?av='.$value.'">'.$value.'</a></p>
			</div>
		</div>';
	}

?>

<?php
}

?>

<?php require("footer.php"); ?>