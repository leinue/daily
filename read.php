<?php 
require('header.php');

$id=$_GET['av'];

$handle = fopen ("http://news-at.zhihu.com/api/3/news/$id", "rb");
$contents = "";

if(!$handle){header('location:404.php');}

do{
	$data = fread($handle, 1024);
	if (strlen($data) == 0) {break;}
	$contents .= $data;
}while(true);

fclose ($handle);

$deContents=json_decode($contents,true);

//print_r($deContents);
echo '<div class="main-wrap">
	<div class="main-wrap-img">
		<div class="main-wrap-img-top"><img src="'.$deContents['image'].'" alt="'.$id.'" /></div>
		<div class="main-wrap-img-foot"><h3>'.$deContents['title'].'</h3></div>
	</div>
	<div class="main-wrap-text">'.$deContents['body'].'</div>
</div>';

?>
<script>
document.getElementById('zhihu-model').style.display="none";
</script>
<?php
require('footer.php');
?>