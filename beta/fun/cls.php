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

	function __construct(){
		
	}

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

class todayMgr extends infoMgr{
	protected $infomgr;
	
	function __construct(infoMgr $infoMgr){$this->infomgr=$infoMgr;}

	function getJSON(getBase $getBase){return $getBase->get();}

	function getContext(decodeJSON $DJSON){
		$jsonData=$DJSON->decode();
		return $jsonData;
	}
}

class beforeMgr extends infoMgr{
	function getJSON(getBase $getBase,$url){return $getBase->get($url);}

}


$im=infoMgr::getInstance();
$tm=new todayMgr($im);
//echo $tm->getJSON();
$gb=new getBase();

print_r($tm->getContext(new decodeJSON($tm->getJSON($gb))));

?>