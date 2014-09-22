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

	function decode(){return json_decode($this->contents);}
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

	function getContextUrl($id=NULL){
		if($id==NULL){
			$this->context="http://news-at.zhihu.com/api/3/news/latest";
		}else{
			$this->context="http://news-at.zhihu.com/api/3/news/$id";
		}
		return $this->context;
	}

	function getBeforeUrl($date=NULL){
		if($date==NULL){
			$this->before="http://news-at.zhihu.com/api/3/news/latest";
		}else{
			$this->before="http://news.at.zhihu.com/api/3/news/before/$date";
		}
		return $this->before;
	}
}

//ini
$im=infoMgr::getInstance();
$tm=new detailMgr($im);
$gb=new getBase();

//print today
$jsonData=$tm->getJSON($gb);
$jsonArray=$tm->getContext(new decodeJSON($jsonData));

print_r($jsonArray);

//print before
$jsonData=$tm->getJSON($gb,"http://news.at.zhihu.com/api/3/news/before/20140920");
$jsonArray=$tm->getContext(new decodeJSON($jsonData));

print_r($jsonArray);

//print id
$jsonData=$tm->getJSON($gb,"http://news-at.zhihu.com/api/3/news/4170735");
$jsonArray=$tm->getContext(new decodeJSON($jsonData));

print_r($jsonArray);

?>