<?php
/**
 * TOP API: taobao.ump.promotion.get request
 */
class UmpPromotionGetRequest
{
	/** 
	 * 需返回的字段列表。可选值
	 **/
	private $fields;
	
	/** 
	 * 商品ID
	 **/
	private $item_id;
	
	private $apiParas = array();
	
	public function setFields($fields)
	{
		$this->fields = $fields;
		$this->apiParas["fields"] = $fields;
	}

	public function getFields()
	{
		return $this->fields;
	}

	public function setItem_id($item_id)
	{
		$this->item_id = $item_id;
		$this->apiParas["item_id"] = $item_id;
	}

	public function getItem_id()
	{
		return $this->item_id;
	}

	public function getApiMethodName()
	{
		return "taobao.ump.promotion.get";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->fields,"fields");
		RequestCheckUtil::checkNotNull($this->item_id,"item_id");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
