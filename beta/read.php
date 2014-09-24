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
		//echo $dataParser->getImages();
?>
		<div class="read-content">
			<div class="read-left">
				<div class="read-title">
					<div class="read-headline">羽翼</div>
					<div class="help-info">
						<p>日期：20100520 图片来源：蛤蛤蛤</p>
					</div>
				</div>
				<div class="read-main-content">
					sdsdsddsd
				</div>
				<div class="read-footer">
					<span class="read-copyright">版权声明：除非注明，本站文章均为原创或编译，转载请注明： 文章来自 <a href="http://zhihu.com" target="_blank">知乎</a></span>
				</div>
			</div>
			<div class="read-right">
				<img src="<?php echo $dataParser->getImages(); ?>" alt="125152" width="339" height="200">
			</div>
		</div>
<?php
	}
}

?>

<?php require("footer.php"); ?>