<?php
require('header.php');

include('fun.php');

$method=test_input($_GET['method']);
$date=test_input($_GET['token']);

//初始化颜色数据
$rgbValue=array(
	"#1abc9c","#2ecc71","#3498db","#9b59b6","#34495e",
	"#16a085","#27ae60","#2980b9","#8e44ad","#2c3e50",
	"#f1c40f","#e67e22","#e74c3c","#ecf0g1","#95a5a6",
	"#f39c12","#d35400","#c0392b","#bdc3c7","#7f8c8d");

if(strlen($method)==0 || strlen($date)==0){

function getMonthLastDay($month,$year){
	$nextMonth=(($month+1)>12) ? 1 : ($month+1);
	$year= ($nextMonth>12) ? ($year+1) : $year;
	$lastDay=date('d',mktime(0,0,0,$nextMonth,0,$year));
	return $lastDay;
}

date_default_timezone_set('UTC');

$nowTime=time();

$unixtimestamp=strtotime("2013-05-20");

$days=round(($nowTime-$unixtimestamp)/3600/60);

$timeWithoutUnix=date("Y-m-d",time());
$timeWithoutUnix_exploded=explode("-",$timeWithoutUnix);
$timeWithoutUnix="";
foreach ($timeWithoutUnix_exploded as $key => $value) {
	$timeWithoutUnix.=$value;
}

//判断是不是合法月份
function isMonth($mon){
	$montharr=array(1,2,3,4,5,6,7,8,9,10,11,12);
	$flag=0;
	foreach ($montharr as $key => $value) {
		if($mon==$value){
			$flag++;
		}
	}
	if($flag>0){
		return true;
	}else{
		return false;
	}
}

//打印出某年某月的天数
function printDate($year,$mon){
	//20130520
	$montharr=array(1,2,3,4,5,6,7,8,9,10,11,12);
	$stand = "$year-$mon-";

  	//取得每个月的天数
	foreach ($montharr as $key => $monthv) {
		$daysarr[$key+1]=getMonthLastDay($monthv,$year);
	}

	$tmp_mon=substr($mon,0,1);
	if($tmp_mon=="0"){
		$tmp_mon=substr($mon,1,1);
	}

	for ($i=1;$i<=$daysarr[$tmp_mon];$i++) {  
    	$time=strtotime($stand.$i);  
    	$date[]=date("Ymd",$time);  
	}  
  
	return $date;
}

//获得20130520到0531的天数,因为知乎日报开始自20130520
$march2013=printDate("2013","05");
$march2013_fixed=array();
for ($i=19; $i <=30; $i++) { 
	$march2013_fixed[]=$march2013[$i];
}

//获得2013年剩下的天数
$mon2013=array();

for ($i=6; $i <=12; $i++) { 
	$mon2013[]=printDate("2013",$i);
}

//获得2014年及其以后的天数
$monOther=array();
for ($i=2014; $i <= $timeWithoutUnix_exploded[0]; $i++) { 
	for ($j=1; $j <=$timeWithoutUnix_exploded[1]; $j++) { 
		$monOther[]=printDate($i,$j);
	}
}

$monOther_fixed=array();

/*$nowMon=array();
for ($i=0; $i < getMonthLastDay($timeWithoutUnix_exploded[1],$timeWithoutUnix_exploded[0])-$timeWithoutUnix_exploded[2]-2; $i++) { 
	$monOther_fixed[]=$monOther[$timeWithoutUnix_exploded[1]-1][$i];
}*/
//剔除当月的多余部分
array_splice($monOther[count($monOther)-1],0,(getMonthLastDay($timeWithoutUnix_exploded[1],$timeWithoutUnix_exploded[0])-$timeWithoutUnix_exploded[2]));

foreach ($monOther[count($monOther)-1] as $key => $value) {
	$monOther[count($monOther)-1][$key]=$value-(getMonthLastDay($timeWithoutUnix_exploded[1],$timeWithoutUnix_exploded[0])-date("d"));
}

//将多个数组混合到一起
$totalMon=array_merge($march2013_fixed,$mon2013,$monOther);

//释放内存
$march2013=array();
$march2013_fixed=array();
$mon2013=array();
$monOther=array();

echo '<div class="zhihu-body">';

//将二维数组转换为一维数组
foreach ($totalMon as $key => $value) {
	if(is_array($value)){
		foreach ($value as $key2 => $value2) {
			$intervalDate[]=$value2;
		}
	}else{
		$intervalDate[]=$value;
	}
}

$totalMon=array();

$intervalDate=array_reverse($intervalDate);
//print_r($intervalDate);

$pages=ceil(count($intervalDate)/30);

$currentPage=$_GET['page'];

if(strlen($currentPage)==0){$currentPage=1;}

if($currentPage==$pages){
	$nextPage=$pages;
}else{
	$nextPage=$currentPage+1;
}

if($currentPage=="1"){
	$prePage="1";
}else{
	$prePage=$currentPage-1;
}

$startKey=30*$currentPage-30;
$endKey=$startKey+30;
if($endKey>count($intervalDate)){
	$tmp=$endKey-count($intervalDate);
	$endKey=$endKey-$tmp;
}

for ($i=$startKey; $i < $endKey; $i++) { 
	$randmath=rand(0,19);
	echo '<a href="before.php?method=read&token='.$intervalDate[$i].'"><div class="main-news-panel-before" style="background: '.$rgbValue[$randmath].'">
		<p>'.$intervalDate[$i].'</p>
	</div></a>';	
}

?>

<div class="pageindex">
	<ul>
		<a href="before.php?page=<?php echo $prePage; ?>"><li>&lt</li></a>
<?php
	for ($i=1; $i <= $pages; $i++) { 
		echo '<a href="before.php?page='.$i.'"><li>'.$i.'</li></a>';
	}
?>
		<a href="before.php?page=<?php echo $nextPage; ?>"><li>&gt</li></a>
	</ul>
</div>

<?php
echo '</div>';

}else{
?>
		<div class="zhihu-body">
<?php
$handle = fopen ("http://news.at.zhihu.com/api/3/news/before/$date", "rb");
$contents = "";

do{
	$data = fread($handle, 1024);
	if (strlen($data) == 0) {break;}
	$contents .= $data;
}while(true);

fclose ($handle);

$deJSON=json_decode($contents,true);

$countJSON_stories=count($deJSON['stories']);

for ($i=0; $i < $countJSON_stories; $i++) { 
	$shareDate=$deJSON['date'];
	$title=$deJSON['stories'][$i]['title'];
	$sharingURL=$deJSON['stories'][$i]['share_url'];
	$sharingID=$deJSON['stories'][$i]['id'];
	$ga_prefix=$deJSON['stories'][$i]['ga_prefix'];
	$imgURL=$deJSON['stories'][$i]['images'][0];
	$randmath=rand(0,19);
	echo '	<div class="main-news-panel" style="background:'.$rgbValue[$randmath].';">
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
<?php
}

?>

<?php
require('footer.php');
?>