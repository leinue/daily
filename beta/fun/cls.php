<?php

class infoMgr{

	public static $handle;
	static private $_instance=NULL; 
	
	private function __construct(){}
	private function __clone(){}

	static function getInstance(){
		if(self::$_instance==NULL){
			self::$_instance=new infoMgr();
		}
		return self::$_instance;
	}

	protected function read(){
		do{
			$data = fread(self::$handle, 1024);
			if (strlen($data) == 0) {break;}
			$contents .= $data;
		}while(true);

		return $contents;
	}
}

class decodeJSON{
	protected $contents;
	function __construct($contents){$this->contents=$contents;}

	function decode(){return json_decode($this->contents,true);}
}

class getBase extends infoMgr{

	function __construct(){}

	function get($url=NULL){

		if($url==NULL){
			$url="http://news-at.zhihu.com/api/3/news/latest";
		}

		parent::$handle=fopen($url,"rb");
		if(parent::$handle){
			return parent::read();
		}else{
			return false;
		}
	}
}

class detailMgr extends infoMgr{
	protected $infomgr;
	
	function __construct(infoMgr $infoMgr){$this->infomgr=$infoMgr;}

	//若$url为空,则显示今天的消息,否则显示特定的消息
	function getJSON(getBase $getBase,$url=NULL){return $getBase->get($url);}

	function getContext(decodeJSON $DJSON){
		$jsonData=$DJSON->decode();
		return $jsonData;
	}
}

class urlMgr{
	private $before;
	private $context;
	private $latest="http://news-at.zhihu.com/api/3/news/latest";

	function getContextUrl($id=NULL){
		if($id==NULL){
			$this->context=$this->latest;
		}else{
			$this->context="http://news-at.zhihu.com/api/3/news/$id";
		}
		return $this->context;
	}

	function getBeforeUrl($date=NULL){
		if($date==NULL){
			$this->before=$this->latest;
		}else{
			$this->before="http://news.at.zhihu.com/api/3/news/before/$date";
		}
		return $this->before;
	}
}

class DataObj{
	public $_data;
	public $storiesData;
	public $singleNew;
	const _LIST='1';
	const BODY='2';
	public $method;

	function __construct($data,$method){
		$this->_data=$data;
		$this->method=$method;
	}

	private function isList(){
		if($this->method==self::_LIST){return true;}
	}

	function getDate(){
		if($this->isList()){
			return $this->_data['date'];
		}
	}

	function getNewsNum(){
		if($this->isList()){
			return count($this->_data["stories"]);
		}
	}

	function getStories(){
		if($this->isList()){
			$this->storiesData=$this->_data["stories"];
			return $this->storiesData;
		}
	}

	function getSingleNew($key=NULL){
		if($this->isList()){
			$this->singleNew=$this->storiesData[$key];
		}else{
			$this->singleNew=$this->_data;
		}
		return $this->singleNew;
	}

	function getImageSource(){return $this->singleNew['image_source'];}

	function getTitle(){return $this->singleNew['title'];}

	function getShareUrl(){return $this->singleNew['share_url'];}

	function getGaOrefix(){return $this->singleNew['ga_prefix'];}

	function getImages(){
		if($this->method==self::_LIST){
			$namePrefix="";
			$pic=$this->singleNew["images"][0];//远程文件路径
		}else{
			$namePrefix="body_";
			$pic=$this->singleNew["image"];
		}
    	
        $filepath="\/img/".$this->getDate()."/";
        if(!is_dir($filepath)){
            mkdir($filepath,0777,true);
        }
        $filename=$namePrefix.$this->getID().'.'.substr($pic,-3,3);
        if(!file_exists($filepath.$filename)){
        	$data=file_get_contents($pic); // 读文件内容 
        	$fp=@fopen($filepath.$filename,"w"); 
       		@fwrite($fp,$data);
        	fclose($fp);
        }
        return $filepath.$filename;
	}

	function getType(){return $this->singleNew['type'];}

	function getID(){return $this->singleNew['id'];}

	function getCSS(){return $this->singleNew['css'][0];}

	function getBody(){return $this->singleNew['body'];}

}

class dateMgr{

	function __construct(){date_default_timezone_set('UTC');}

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
			$daysarr[$key+1]=$this->getMonthLastDay($monthv,$year);
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

	function getMonthLastDay($month,$year){
		$nextMonth=(($month+1)>12) ? 1 : ($month+1);
		$year= ($nextMonth>12) ? ($year+1) : $year;
		$lastDay=date('d',mktime(0,0,0,$nextMonth,0,$year));
		return $lastDay;
	}


	function getAllDate(){
		$nowTime=time();

		$unixtimestamp=strtotime("2013-05-20");

		$days=round(($nowTime-$unixtimestamp)/3600/60);

		$timeWithoutUnix=date("Y-m-d",time());
		$timeWithoutUnix_exploded=explode("-",$timeWithoutUnix);
		$timeWithoutUnix="";
		foreach ($timeWithoutUnix_exploded as $key => $value) {
			$timeWithoutUnix.=$value;
		}

		//获得20130520到0531的天数,因为知乎日报开始自20130520
		$march2013=$this->printDate("2013","05");
		$march2013_fixed=array();
		for ($i=19; $i <=30; $i++) { 
			$march2013_fixed[]=$march2013[$i];
		}

		//获得2013年剩下的天数
		$mon2013=array();

		for ($i=6; $i <=12; $i++) { 
			$mon2013[]=$this->printDate("2013",$i);
		}

		//获得2014年及其以后的天数
		$monOther=array();
		for ($i=2014; $i <= $timeWithoutUnix_exploded[0]; $i++) { 
			for ($j=1; $j <=$timeWithoutUnix_exploded[1]; $j++) { 
				$monOther[]=$this->printDate($i,$j);
			}
		}

		$monOther_fixed=array();

		//剔除当月的多余部分
		array_splice($monOther[count($monOther)-1],0,($this->getMonthLastDay($timeWithoutUnix_exploded[1],$timeWithoutUnix_exploded[0])-$timeWithoutUnix_exploded[2]));

		foreach ($monOther[count($monOther)-1] as $key => $value) {
			$monOther[count($monOther)-1][$key]=$value-($this->getMonthLastDay($timeWithoutUnix_exploded[1],$timeWithoutUnix_exploded[0])-date("d"));
		}

		//将多个数组混合到一起
		$totalMon=array_merge($march2013_fixed,$mon2013,$monOther);

		//释放内存
		$march2013=array();
		$march2013_fixed=array();
		$mon2013=array();
		$monOther=array();

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

		return $intervalDate;
	}
}


function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

/*
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
	echo "<img src=\"".$dataParser->getImages()."\" alt=\"233\"/>";
}

//print before
$url=new urlMgr();
$urlAvailable=$url->getBeforeUrl("20140920");
$jsonData=$tm->getJSON($gb,$urlAvailable);
$jsonArray=$tm->getContext(new decodeJSON($jsonData));

//print_r($jsonArray);

//print context with id
$url=new urlMgr();
$urlAvailable=$url->getContextUrl("4170735");
$jsonData=$tm->getJSON($gb,$urlAvailable);
$jsonArray=$tm->getContext(new decodeJSON($jsonData));

//print_r($jsonArray);
$dataParser=new DataObj($jsonArray,DataObj::BODY);
$stories=$dataParser->getSingleNew();
//echo "<img src=\"".$dataParser->getImages()."\" alt=\"233\"/>";
//echo $dataParser->getBody();

$allDate=new dateMgr();
//print_r($allDate->getAllDate());
*/
?>