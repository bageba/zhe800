<?php
/**
 * TOP API: ftxia.itemdetail.get request
 * @author Ftxia 8mob.COM
 * 2013-12-07 12:39:25
 */
class FtxiaItemDetailGetRequest{

	private $num_iid;
	private $apiParas = array();

	public function setNumiid($num_iid)
	{
		$this->num_iid = $num_iid;
		$this->apiParas["num_iid"] = $num_iid;
	}

	public function getNumiid()
	{
		return $this->num_iid;
	} 
	

	public function getApiMethodName()
	{
		return "ftxia.itemdetail.get";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
