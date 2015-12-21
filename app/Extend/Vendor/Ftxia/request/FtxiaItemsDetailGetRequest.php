<?php
/**
 * TOP API: ftxia.itemsdetail.get request
 * @author Ftxia 8mob.COM
 * 2013-12-07 12:39:25
 */
class FtxiaItemsDetailGetRequest{

	private $numiids;
	private $apiParas = array();

	public function setNumiids($numiids)
	{
		$this->numiids = $numiids;
		$this->apiParas["num_iids"] = $numiids;
	}

	public function getNumiids()
	{
		return $this->numiids;
	} 
	

	public function getApiMethodName()
	{
		return "ftxia.itemsdetail.get";
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
