<?php
class ju_reportsAction extends FirstendAction {
	public function _initialize() {
        parent::_initialize();
        $this->item_mod		= D('items');
        $this->report_mod = D('report');
    }

    public function index() {
    	$id = $this->_get('id', 'trim');
    	!$id && $this->_404();
    	$item = $this->item_mod->where(array('id' => $id))->find();
    	!$item && $this->_404();
    	
    	
    	$this->_config_seo(array(
            'title' =>  '举报纠错	-	' . C('ftx_site_name'),
        ));
    	$this->assign('item', $item);
      $this->display('index');
    }
    
    public function add(){
    	$item_id = I('report_deal_id');
    	$comment = I('report_comment');
    	$email = I('report_user_email');
    	$reason = I('report_reason');
    	if(!is_email($email) || !$email){
    		exit('2');
    	}
    	$data['item_id'] = $item_id;
    	$data['comment'] = $comment;
    	$data['email'] = $email;
    	$data['uid'] = $this->visitor->info['id'];
    	$data['uname'] = $this->visitor->info['username'];
    	$data['reason'] = $reason;
    	$data['addtime'] = time();
    	if (false === $this->report_mod->create($data)) {
    		$this->ajaxReturn(0, $this->report_mod->getError());
    	}
    	$id = $this->report_mod->add();
    	if ($id) {
    		exit('1');
    	}else{
    		exit('0');
    	}
    }


}