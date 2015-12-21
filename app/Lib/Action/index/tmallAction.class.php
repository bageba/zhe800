<?php
class tmallAction extends FirstendAction {
	public function _initialize() {
        parent::_initialize();

		$api_config = M('items_site')->where(array('code' => 'ftxia'))->getField('config');
        $this->_tbconfig = unserialize($api_config);
    }

    public function index() {
		$p = I('p',1, 'intval');
		$sort = I('sort','new','trim');

		$top = $this->_get_top();
        $req = $top->load_api('TmallItemsGetRequest');
        $req->setFields('num_iid,title,pic_url,price,volume');
        $req->setPageNo($p);
		$req->setSort($sort);
		$req->setTime(date("Y-m-d H"));
		$resp = $top->execute($req);
        $count = $resp->totals;
        $items = object_to_array($resp->items);

		$this->assign('items',$items);
        $pager = $this->_pager($count, '60');
        $this->assign('page', $pager->fshow());

		$this->assign('sort', $sort);
        $this->assign('nav_curr', 'tmall');
        $this->_config_seo(array(
			'title' => ' 商城优选,精选最值得买的爆款商品	-	' . C('ftx_site_name'),
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