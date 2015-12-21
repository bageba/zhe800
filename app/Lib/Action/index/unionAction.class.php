<?php

class unionAction extends UsersAction {

	public function _initialize(){
        parent::_initialize();
        //访问者控制
        if (!$this->visitor->is_login) {
            IS_AJAX && $this->ajaxReturn(0, L('login_please'));
            $this->redirect('user/login');
        }
    }
 
 
    public function index() {
		$info					= $this->visitor->get();
		$union_url				= C('ftx_site_url').U('inval/index',array('id'=>$info['id']));
		$union['per_visit']		= C('ftx_score_rule.union_visit');
		$union['count_visit']	= C('ftx_score_rule.union_visit')* C('ftx_score_rule.union_visit_nums');
		$union['per_reg']		= C('ftx_score_rule.union_reg');
		$union['count_reg']		= C('ftx_score_rule.union_reg')*C('ftx_score_rule.union_reg_nums');
        
        $this->assign('union', $union);
		$this->assign('union_url', $union_url);
        $this->_config_seo(array(
            'title' => '邀请好友访问 -	' . C('ftx_site_name'),
        ));
        $this->display();
    }
 

}