<?php
class commentAction extends FirstendAction {
	public function _initialize() {
        parent::_initialize();
        $this->_mod = D('items_comment');
    }

    
    public function index() {
		$p = I('p',1,'intval');
		$sort = 'add_time';
		$order = ' DESC ';
		$where = '1=1';
		$count = $this->_mod->join(C('DB_PREFIX').'items ON '.C('DB_PREFIX').'items.id='.C('DB_PREFIX').'items_comment.item_id')->where($where)->count(C('DB_PREFIX').'items_comment.id');
		$pager = $this->_pager($count, 30);
        $list  = $this->_mod->field(C('DB_PREFIX').'items_comment.add_time,'.C('DB_PREFIX').'items_comment.info,'.C('DB_PREFIX').'items_comment.uname,'.C('DB_PREFIX').'items.title,'.C('DB_PREFIX').'items.pic_url,'.C('DB_PREFIX').'items.price,'.C('DB_PREFIX').'items.coupon_price,'.C('DB_PREFIX').'items.coupon_start_time,'.C('DB_PREFIX').'items.num_iid,'.C('DB_PREFIX').'items_comment.status,'.C('DB_PREFIX').'items.id')->join(C('DB_PREFIX').'items ON '.C('DB_PREFIX').'items.id='.C('DB_PREFIX').'items_comment.item_id')->where($where)->order($sort . ' ' . $order)->limit($pager->firstRow.','.$pager->listRows)->select();
		 
        $this->assign('page_bar', $pager->fshow());
        $this->assign('list',$list);
		$this->_config_seo(C('ftx_seo_config.index'));
        $this->display();
    }

}