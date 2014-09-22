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

	function __construct($data){$this->_data=$data;}

	function getDate(){return $this->_data['date'];}

	function getNewsNum(){return count($this->_data["stories"]);}

	function getStories(){
		$this->storiesData=$this->_data["stories"];
		return $this->storiesData;
	}

	function getSingleNew($key){
		$this->singleNew=$this->storiesData[$key];
		return $this->singleNew;
	}

	function getTitle(){return $this->singleNew['title'];}

	function getShareUrl(){return $this->singleNew['share_url'];}

	function getGaOrefix(){return $this->singleNew['ga_prefix'];}

	function getImages(){return $this->singleNew['images'][0];}

	function getType(){return $this->singleNew['type'];}

	function getID(){return $this->singleNew['id'];}

}

//ini
$im=infoMgr::getInstance();
$tm=new detailMgr($im);
$gb=new getBase();

//print today
$jsonData=$tm->getJSON($gb);
$jsonArray=$tm->getContext(new decodeJSON($jsonData));

//print_r($jsonArray);
$dataParser=new DataObj($jsonArray);
//echo $dataParser->getDate();

$stories=$dataParser->getStories();

foreach ($stories as $key => $value) {
	$singleNew=$dataParser->getSingleNew($key);
	echo $dataParser->getTitle()."<br>";
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

?>