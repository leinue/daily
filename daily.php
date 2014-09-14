<!DOCTYPE html>
<html lang="zh-cn">
 	<head>
 		<title>直呼日报</title>
 		<link href="style.css" rel="stylesheet">
 	</head>
	<body>

		<div class="zhihu-top-heading">
			<span class="zhihu-main-title">直呼日报</span>
			<p class="zhihu-help-block">每天⑨次.⑨次每天</p>
		</div>

		<div class="zhihu-heading">
			<ul class="zhihu-heading-menu">
				<li><p>直呼日报</p></li>
				<li><p>最新消息</p></li>
				<li><p>过往消息</p></li>
				<li><p>联系作者</p></li>
				<li><p>API</p></li>
			</ul>
		</div>

		<div class="zhihu-body">
<?php

$handle = fopen ("http://news-at.zhihu.com/api/3/news/latest", "rb");
$contents = "";

do{
	$data = fread($handle, 1024);
	if (strlen($data) == 0) {break;}
	$contents .= $data;
}while(true);

fclose ($handle);

//echo $contents;

$deJSON=json_decode($contents,true);
$countJSON=count($deJSON['stories']);

//echo $countJSON;

//print_r($deJSON);

//today
for ($i=0; $i < $countJSON; $i++) { 
	$shareDate=$deJSON['date'];
	$title=$deJSON['stories'][$i]['title'];
	$sharingURL=$deJSON['stories'][$i]['share_url'];
	$imgURL=$deJSON['stories'][$i]['images'][0];
	$sharingID=$deJSON['stories'][$i]['id'];
	echo '	<div class="main-news-panel">
				<div class="main-news-panel-heading">
					<img src="'.$imgURL.'" alt="'.$sharingID.'" />
				</div>
				<div class="main-news-panel-content">
					<div class="news-title"><a href="http://news-at.zhihu.com/api/3/news/'.$sharingID.'">'.$title.'</a></div>
					<p class="news-date">日期:'.$shareDate.'</p>
					<div class="main-news-button">
						<p><a href="">进入阅读</a></p>
					</div>
				</div>
			</div>';
}

?>
		</div>

	</body>
</html>
