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

		$allContents=$dataParser->getBody();
		$newsTitle=$dataParser->getTitle();

		$keyTitle = array("整","点","儿","新","闻");
		$flag=0;

		foreach ($keyTitle as $key => $value) {
			if (stripos($newsTitle,$value)) {
				$flag++;
			}
		}

		if(!$flag>0){
			//<img class="avatar" src=" ">
			$eregPattern="<img class=\"avatar\" src=\"+[a-zA-Z0-9\.\_\/\:]+\">";
			ereg($eregPattern,$allContents,$faceUrl);
			$faceUrlFixed=substr($faceUrl[0],25);
			$faceUrlFixed=substr($faceUrlFixed,0,strlen($faceUrlFixed)-2);
			$data=file_get_contents($faceUrlFixed);

			$filepath="\/img/avatar/".$id.".".substr($faceUrlFixed,-3,3);

			if(!is_dir("\/img/avatar/")){
            	mkdir("\/img/avatar/",0777,true);
        	}

			if(!file_exists($filepath)){
				$fp=@fopen($filepath,"w"); 
       			@fwrite($fp,$data);
        		fclose($fp);
			}

			$allContents=str_replace($faceUrlFixed,$filepath,$allContents);
		}

?>
		<div class="read-content">
			<div class="read-left">
				<div class="read-title">
					<div class="read-headline"><?php echo $newsTitle; ?></div>
					<div class="help-info">
						<p>图片来源：<?php  echo $dataParser->getImageSource(); ?></p>
					</div>
				</div>
				<div class="read-main-content"><?php echo $allContents; ?></div>
				<div class="read-footer">
					<span class="read-copyright">版权声明：除非注明，本站文章均为原创或编译，转载请注明： 文章来自 <a href="http://zhihu.com" target="_blank">知乎</a></span>
				</div>
			</div>
			<div class="read-right">
				<img src="daily.ivydom.com/<?php echo $dataParser->getImages(); ?>" alt="125152" width="339" height="200">
			</div>
		</div>
<?php
	}
}

?>

<?php require("footer.php"); ?>