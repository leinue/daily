<?php require("header.php"); ?>

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
						<a href="read.php?av='.$dataParser->getID().'">'.$dataParser->getTitle().'</a>
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

<?php require("footer.php"); ?>