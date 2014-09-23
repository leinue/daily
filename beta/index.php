<?php require("fun/cls.php"); ?>
<!DOCTYPE html>
<html lang="zh-cn">
 	<head>
 		<title>直呼日报 2.0 Beta</title>
 		<link href="style/style.css" rel="stylesheet">
 		<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
 		<meta name="Description" content="直呼日报">
		<meta name="keywords" content="直呼日报,每天⑨次,干杯,如何正确的吐槽,知乎,知识,好奇心" />
 	</head>

 	<body>
		<div class="body-head">
			<div class="body-name">直呼日报 Beta
				<p class="help-block">每天⑨次,⑨次每天</p>
			</div>
			<div class="body-menu">
				<ul class="main-menu">
					<li class="menu-logo"><a href=""><img src="favicon.ico" alt="logo" /></a></li>
					<li class="menu-active"><a href="">日报首页</a></li>
					<li><a href="">过往消息</a></li>
					<li><a href="">联系我</a></li>
					<li><a href="">API</a></li>
				</ul>
			</div>
		</div>

		<div class="body-content">

		<?php

		//ini
		$im=infoMgr::getInstance();
		$tm=new detailMgr($im);
		$gb=new getBase();

		//print today
		$jsonData=$tm->getJSON($gb);
		$jsonArray=$tm->getContext(new decodeJSON($jsonData));

		//print_r($jsonArray);
		$dataParser=new DataObj($jsonArray,DataObj::_LIST);
		//echo $dataParser->getDate();

		$stories=$dataParser->getStories();

		foreach ($stories as $key => $value) {
			$singleNew=$dataParser->getSingleNew($key);
			echo '			<div class="content-main-box">
				<div class="main-box-img">
					<img src="'.$dataParser->getImages().'" alt="'.$dataParser->getID().'" />
				</div>
				<div class="main-box-content">
					<div class="main-title">
						<a href="http://news-at.zhihu.com/api/3/news/'.$dataParser->getID().'">'.$dataParser->getTitle().'</a>
					</div>
					<div class="main-time">'.$dataParser->getDate().'</div>
				</div>
				<div class="main-box-footer">
					<div class="main-box-postscript">来自:<a href="http://zhihu.com">zhihu.com</a></div>
				</div>
			</div>';
		}

		?>

		</div>

	 	<div class="footer">
	 		<ul>
	 			<li>@2014 直呼日报 |</li>
	 			<li>数据来自 <a href="http://zhihu.com" target="_blank">知乎</a> |</li>
	 			<li><a href="http://ivydom.com" target="_blank">ivydom</a> 版权所有</li>
	 		</ul>
 		</div>

 	</body>

</html>
