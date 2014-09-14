<?php
require('header.php');

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

$pages=ceil($days/20);

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


$rgbValue=array(
	"#1abc9c","#2ecc71","#3498db","#9b59b6","#34495e",
	"#16a085","#27ae60","#2980b9","#8e44ad","#2c3e50",
	"#f1c40f","#e67e22","#e74c3c","#ecf0g1","#95a5a6",
	"#f39c12","#d35400","#c0392b","#bdc3c7","#7f8c8d");

$timeWithoutUnix=date("Y-m-d",time());
$timeWithoutUnix_exploded=explode("-",$timeWithoutUnix);
$timeWithoutUnix="";
foreach ($timeWithoutUnix_exploded as $key => $value) {
	$timeWithoutUnix.=$value;
}

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

$march2013=printDate("2013","05");
$march2013_fixed=array();
for ($i=19; $i <=30; $i++) { 
	$march2013_fixed[]=$march2013[$i];
}

$mon2013=array();

for ($i=6; $i <=12; $i++) { 
	$mon2013[]=printDate("2013",$i);
}

//print_r($mon2013);

$monOther=array();
for ($i=2014; $i <= $timeWithoutUnix_exploded[0]; $i++) { 
	for ($j=1; $j <=$timeWithoutUnix_exploded[1]; $j++) { 
		$monOther[]=printDate($i,$j);
	}
}

//print_r($monOther);

$monOther_fixed=array();

/*$nowMon=array();
for ($i=0; $i < getMonthLastDay($timeWithoutUnix_exploded[1],$timeWithoutUnix_exploded[0])-$timeWithoutUnix_exploded[2]-2; $i++) { 
	$monOther_fixed[]=$monOther[$timeWithoutUnix_exploded[1]-1][$i];
}*/
array_splice($monOther[count($monOther)-1],0,(getMonthLastDay($timeWithoutUnix_exploded[1],$timeWithoutUnix_exploded[0])-$timeWithoutUnix_exploded[2]));

foreach ($monOther[count($monOther)-1] as $key => $value) {
	$monOther[count($monOther)-1][$key]=$value-16;
}

$totalMon=array_merge($march2013_fixed,$mon2013,$monOther);

//print_r($totalMon);

echo '<div class="zhihu-body">';

foreach ($totalMon as $key => $value) {
	$randmath=rand(0,19);
	if($key<12){
		echo '<a href=""><div class="main-news-panel-before" style="background: '.$rgbValue[$randmath].'">
		<p>'.$value.'</p>
	</div></a>';	
	}else{
		foreach ($value as $keyInner => $valueInner) {
			echo '<a href=""><div class="main-news-panel-before" style="background: '.$rgbValue[$randmath].'">
		<p>'.$valueInner.'</p>
	</div></a>';	
		}
	}
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
?>

<?php
require('footer.php');
?>