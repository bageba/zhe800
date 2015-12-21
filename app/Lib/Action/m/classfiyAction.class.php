<?php
class classfiyAction extends FirstendAction {
	public function _initialize() {
		parent::_initialize();
		$this->_mod = D("items");
		$this->_cate_mod = D('items_cate');
		if (false === $cate_list = F('cate_list')) {
			$cate_list = $this->_cate_mod->cate_cache();
		}
		$this->assign('cate_list', $cate_list['p']); //·ÖÀà
	}
	
	public function _empty(){
		$this->index();
	}

	public function index() {
		$this->display('classfiy');
	}
}