<!DOCTYPE html>
<html lang="zh-cn">
 	<head>
 		<title>zhihu daily</title>
 		<link href="style.css" rel="stylesheet">
 	</head>
	<body>

		<div class="zhihu-heading">
			<span class="zhihu-heading-title">直呼日报</span>
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
$countJSON=count($deJSON);

//print_r($deJSON);
			
for ($i=0; $i < $countJSON; $i++) { 
	$nowDate=$deJSON['date'];
	$title=$deJSON['stories'][$i]['title'];
	$sharingURL=$deJSON['stories'][$i]['share_url'];
	$imgURL=$deJSON['stories'][$i]['images'][0];
	$sharingID=$deJSON['stories'][$i]['id'];

?>
			<div class="main-news-panel">
				<div class="main-news-panel-heading">
<?php
	echo '					<img src="'.$imgURL.'" alt="'.$sharingID.'">';
?>
				</div>
				<div class="main-news-panel-content">
					<span class="news-title">
<?php
	echo '						<a href="">'.$title.'</a>';
?>
					</span>
<?php
	echo '					<p class="news-date">'.$nowDate.'</p>';
?>
				</div>
			</div>
<?php
}

?>
		</div>

	</body>
</html>
