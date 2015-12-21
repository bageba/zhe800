<?php
class reportAction extends BackendAction {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('report');
        $this->item_mod = D('items');
    }

    public function _before_index() {
    	$reason['1'] = "商品已卖光";
    	$reason['2'] = "抢购提前开始";
    	$reason['3'] = "商品链接不正确";
    	$reason['4'] = "商品分类不正确";
    	$reason['5'] = "价格与网站不一致(VIP折扣登录淘宝后才能看到)";
    	$reason['6'] = "商品描述有误";
    	$reason['7'] = "其他原因";
    	$this->assign('reason', $reason);   	
			
    }
    
  
}