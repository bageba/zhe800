<?php
 
class FtxApi{
	public $domain;
	public $appkey;
	public $appsecret;
	public $params;
	public $format = "xml";
	public $gatewayUrl = "http://open.8mob.com/api";
	function __construct(){ 
	
	}
 
	function setDomain($domain){
		$this->domain = $domain;
	}
	function setAppKey($key){
		$this->appkey = $key;
	}
	function setAppSecret($appsecret){
		$this->appsecret = $appsecret;
	}
	function setParams($params=array()){
		if(!is_array($params)||!isset($params['method']))
		return false;
		$params=  array_merge($params,array(
			'domain'=>$this->domain,
			'appkey'=>$this->appkey
		));
		ksort($params,SORT_STRING);
		$signParams='';
		foreach($params as $key=>$val){
			if($val===null||$val===''||$val===false){
				continue;
			}
			$signParams.=$key.'='.$val.'&';
		}
		$signParams=  substr($signParams,0,-1);
		$params['sign']=  md5($signParams);
		$params['sign_method']='md5';
		$this->params=$params;
	}
	function getResult(){
		if(empty($this->params)||!is_array($this->params))
		return false;
		$query='';
		foreach($this->params as $key=>$val){
			$query.=$key.'='.$val.'&';
		}
		$ch=  curl_init();
	 
		curl_setopt($ch,CURLOPT_URL,$gatewayUrl);
		curl_setopt($ch,CURLOPT_POST,true);
		curl_setopt($ch,CURLOPT_POSTFIELDS,substr($query,0,-1));
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		$result=curl_exec($ch);
		curl_close($ch);
		$this->params=null;
		if(!empty($result))
		return json_decode (substr($result,strpos($result,'{'),strrpos($result,'}')+1));
	}


	function getFeed(){
		if(!IS_POST)
		return false;
		if($_POST['appsecret']!==$this->appsecret)
		return false;
		if(($_POST['type']==1)&&$_POST['appkey']!==$this->appkey)
		return false;
		if(!$this->_checkDomain())
		return false;
		if(!$this->_checkPostData())
		return false;
		if(!$this->_verifySign())
		return false;
		if(!isset($_POST['method'])||!in_array($_POST['method'],array('del','add')))
		return false;
		$method=$_POST['method'];
		$this->$method();
	}
	private function _checkPostData(){
		if(!is_array($_POST))
		return false;
		foreach($_POST as $key=>$v){
			if(!is_scalar($v)||strlen($v)>100){
				return false;
			}
		}
		return true;
	}


	private function _verifySign(){
		$params=$_POST;
		ksort($params,SORT_STRING);
		$signParams='';
		foreach($params as $key=>$val){
			if($key==='sign'||$key==='sign_method')
			continue;
			if($val===null||$val===''||$val===false){
				continue;
			}
			$signParams.=$key.'='.$val.'&';
		}
		$signParams=  substr($signParams,0,-1);
		if($params['sign']!==  md5($signParams))
		return false;
		return true;
	}


	private function _checkDomain(){
		if(!isset($_POST['domain']))
		return false;
		$ipList=(array)gethostbynamel($_POST['domain']);
		isset($_SERVER['LOCAL_ADDR']) &&$ipList[]=$_SERVER['LOCAL_ADDR'];
		isset($_SERVER['REMOTE_ADDR']) &&$ipList[]=$_SERVER['REMOTE_ADDR'];
		isset($_SERVER['REMOTE_HOST']) &&$ipList[]=$_SERVER['REMOTE_HOST'];
		$client_ip=  get_client_ip();
		if(!in_array($client_ip,$ipList))
		return false;
		return true;
	}
}
?>