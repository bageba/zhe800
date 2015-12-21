<?php
class shuangAction extends FirstendAction {
	public function _initialize() {
        parent::_initialize();

		$api_config = M('items_site')->where(array('code' => 'ftxia'))->getField('config');
        $this->_tbconfig = unserialize($api_config);
    }

    public function index() {

		$p		= I('p',1);
		$sort	= I('sort','new');
		$cid	= I('cid');

		$top = $this->_get_top();
        $req = $top->load_api('FtxiaShuangItemcatsGetRequest');
        $req->setFields('id,name');
		$resp = $top->execute($req);
        $cats = object_to_array($resp->itemcats);
		$this->assign('cats',$cats);


		$top = $this->_get_top();
        $req = $top->load_api('FtxiaShuangItemsGetRequest');
        $req->setFields('num_iid,title,pic_url,price,volume');
        $req->setPageNo($p);
		$req->setCid($cid);
		$req->setSort($sort);
		$req->setTime(date("Y-m-d H"));
		$resp = $top->execute($req);
        $count = $resp->totals;
        $items = object_to_array($resp->items);

		$this->assign('items',$items);
        $pager = $this->_pager($count, '60');
        $this->assign('page', $pager->kshow());

		$this->assign('total',$count);
		$this->assign('cid',$cid);
		$this->assign('sort', $sort);
        $this->assign('nav_curr', 'shuang');
        $this->_config_seo(array(
			'title' => ' 淘宝双十二秒杀专区,1212最值得买的爆款商品	-	' . C('ftx_site_name'),
		));
        $this->display();
    }


	private function _get_top() {
        vendor('Ftxia.TopClient');
        vendor('Ftxia.RequestCheckUtil');
        vendor('Ftxia.Logger');
        $top = new TopClient;
        $top->appkey = $this->_tbconfig['app_key'];
        $top->secretKey = $this->_tbconfig['app_secret'];
        return $top;
    }
 
}