<?php

class UsersAction extends FirstendAction {
    public function _initialize(){
        parent::_initialize();
        //访问者控制
        if (!$this->visitor->is_login && !in_array(ACTION_NAME, array('login', 'register', 'binding', 'ajax_check'))) {
            IS_AJAX && $this->ajaxReturn(0, L('login_please'));
            $this->redirect('user/login');
        }
        $this->_curr_menu(ACTION_NAME);
    }

    protected function _curr_menu($menu = 'index') {
        $menu_list = $this->_get_menu();
        $this->assign('user_menu_list', $menu_list);
        $this->assign('user_menu_curr', $menu);
    }

    private function _get_menu() {
        $menu = array();
        $menu = array(
            'setting' => array(
                'text' => '帐号设置',
                'submenu' => array(
                    'index' => array('text'=>'基本设置', 'url'=>U('user/index')),
                    'password' => array('text'=>'修改密码', 'url'=>U('user/password')),
										'union' => array('text'=>'邀请好友', 'url'=>U('user/union')),
                    'bind' => array('text'=>'帐号绑定', 'url'=>U('user/bind')),
                    
                )
            ),
            'score' => array(
                'text' => '积分中心',
                'submenu' => array(
                    'order' => array('text'=>'我的礼品', 'url'=>U('score/index')),
                    'logs' => array('text'=>'我的积分', 'url'=>U('score/logs')),
                )
            )
        );
        return $menu;
    }
}