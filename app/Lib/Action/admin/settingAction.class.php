<?php

class settingAction extends BackendAction {

    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('setting');
		$cate_data = D('items_cate')->cate_data_cache();
        $this->assign('cate_data', $cate_data);
    }

    public function index() {
        $type = $this->_get('type', 'trim', 'index');
		$index_cids = C('ftx_index_cids');
		$this->assign('index_cids', $index_cids);
        $this->display($type);
    }
    
    public function user() {
        $this->display();
    }

    public function edit() {
        $setting = $this->_post('setting', ',');
        foreach ($setting as $key => $val) {
            $val = is_array($val) ? serialize($val) : $val;
            $this->_mod->where(array('name' => $key))->save(array('data' => $val));
        }
        $type = $this->_post('type', 'trim', 'index');
        $this->success(L('operation_success'));
    }

    public function ajax_mail_test() {
        $email = $this->_get('email', 'trim');
        !$email && $this->ajaxReturn(0);
        //发送
        $mailer = mailer::get_instance();
        if ($mailer->send($email, '这是一封测试邮件', '这是一封飞天侠秒杀程序自动发送的测试邮件')) {
            $this->ajaxReturn(1);
        } else {
            $this->ajaxReturn(0);
        }
    }

}