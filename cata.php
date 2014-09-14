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

$deJSON=json_decode($contents,true);
$countJSON_stories=count($deJSON['stories']);
$countJSON_top_stories=count($deJSON['top_stories']);

//echo $countJSON;

//print_r($deJSON);

//today
for ($i=0; $i < $countJSON_stories; $i++) { 
	$shareDate=$deJSON['date'];
	$title=$deJSON['stories'][$i]['title'];
	$sharingURL=$deJSON['stories'][$i]['share_url'];
	$sharingID=$deJSON['stories'][$i]['id'];
	$ga_prefix=$deJSON['stories'][$i]['ga_prefix'];
	$imgURL=$deJSON['stories'][$i]['images'][0];
	echo '	<div class="main-news-panel">
				<div class="main-news-panel-heading">
					<img src="'.$imgURL.'" alt="'.$sharingID.'" />
				</div>
				<div class="main-news-panel-content">
					<div class="news-title"><a href="contents.php?method=read&id='.$sharingID.'">'.$title.'</a></div>
					<p class="news-date">日期:'.$shareDate.'</p>
				</div>
			</div>';
}

//yesterday
for ($i=0;$i<$countJSON_top_stories;$i++) { 
	$shareDate=$deJSON['date'];
	$title=$deJSON['top_stories'][$i]['title'];
	$sharingURL=$deJSON['top_stories'][$i]['share_url'];
	$sharingID=$deJSON['top_stories'][$i]['id'];
	$ga_prefix=$deJSON['top_stories'][$i]['ga_prefix'];
	$imgURL=$deJSON['top_stories'][$i]['image'];
	echo '	<div class="main-news-panel">
				<div class="main-news-panel-heading">
					<img src="'.$imgURL.'" alt="'.$sharingID.'" />
				</div>
				<div class="main-news-panel-content">
					<div class="news-title"><a href="contents.php?method=read&id='.$sharingID.'">'.$title.'</a></div>
					<p class="news-date">日期:'.$shareDate.'</p>
				</div>
			</div>';
}

?>
		</div>