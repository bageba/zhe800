<?php
class indexAction extends BackendAction {

    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('menu');
    }

    public function index() {
        $top_menus = $this->_mod->admin_menu(0);
        $this->assign('top_menus', $top_menus);
        //p($_SESSION);
        $my_admin = array('username'=>$_SESSION['admin']['username'], 'roleid'=>$_SESSION['admin']['role_id'], 'rolename'=>$_SESSION['admin']['role_name']);
        //p($my_admin);
        $this->assign('my_admin', $my_admin);
        $this->display();
    }

    public function panel() {
        $message = array();
        if (is_dir('./install')) {
            $message[] = array(
                'type' => 'Error',
                'content' => "您还没有删除 install 文件夹，出于安全的考虑，我们建议您删除 install 文件夹。",
            );
        }
        if (APP_DEBUG == true) {
            $message[] = array(
                'type' => 'Error',
                'content' => "您网站的 DEBUG 没有关闭，出于安全考虑，我们建议您关闭程序 DEBUG。",
            );
        }
        if (!function_exists("curl_getinfo")) {
            $message[] = array(
                'type' => 'Error',
                'content' => "系统不支持 CURL ,将无法采集商品数据。",
            );
        }
        $this->assign('message', $message);
        $system_info = array(
            'ftxia_version' => FTX_VERSION . ' RELEASE '. FTX_RELEASE .' [<a href="http://bbs.8mob.com/" class="blue" target="_blank">查看最新版本</a>]',
            'server_domain' => $_SERVER['SERVER_NAME'] . ' [ ' . gethostbyname($_SERVER['SERVER_NAME']) . ' ]',
            'server_os' => PHP_OS,
            'web_server' => $_SERVER["SERVER_SOFTWARE"],
            'php_version' => PHP_VERSION,
            'mysql_version' => mysql_get_server_info(),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'max_execution_time' => ini_get('max_execution_time') . '秒',
            'safe_mode' => (boolean) ini_get('safe_mode') ?  'onCorrect' : 'onError',
            'zlib' => function_exists('gzclose') ?  'onCorrect' : 'onError',
            'curl' => function_exists("curl_getinfo") ? 'onCorrect' : 'onError',
            'timezone' => function_exists("date_default_timezone_get") ? date_default_timezone_get() : L('no')
        );
        $this->assign('system_info', $system_info);

		$tips_pars = array(
			'action'=>'not'.'ice',
			'sitename'=>'ftxia',
			'siteurl'=>$_SERVER['SERVER_NAME'],
			'version'=>FTX_VERSION,
			'release'=>FTX_RELEASE,
			);
				
		$tips_data = str_replace('{pars}', http_build_query($tips_pars), $tips_str);
		$this->assign('tips_data', $tips_data);
		$my_admin = array('username'=>$_SESSION['admin']['username'], 'roleid'=>$_SESSION['admin']['role_id'], 'rolename'=>$_SESSION['admin']['role_name']);
 
    $this->assign('my_admin', $my_admin);
		$this->assign('time',date('Y-m-d H:i'));
		$this->assign('ip',get_client_ip());
        $this->display();
    }

    public function login() {
        if (IS_POST) {
            $username = $this->_post('username', 'trim');
            $password = $this->_post('password', 'trim');
			if(!$username || !$password ){
                $this->error(L('input_empty'));
            }
            $verify_code = $this->_post('verify_code', 'trim');
            if(session('verify') != md5($verify_code)){
                $this->error(L('verify_code_error'));
            }
            $admin = M('admin')->where(array('username'=>$username, 'status'=>1))->find();
            if (!$admin) {
                $this->error(L('admin_not_exist'));
            }
            if ($admin['password'] != md5($password)) {
                $this->error(L('password_error'));
            }
            $admin_role = M('admin_role')->where(array('id'=>$admin['role_id']))->find();
            session('admin', array(
                'id' => $admin['id'],
                'role_id' => $admin['role_id'],
                'role_name' => $admin_role['name'],
                'username' => $admin['username'],
            ));
            M('admin')->where(array('id'=>$admin['id']))->save(array('last_time'=>time(), 'last_ip'=>get_client_ip()));
            $this->success(L('login_success'), U('index/index'));
        } else {
            $this->display();
        }
    }

    public function logout() {
        session('admin', null);
        $this->success(L('logout_success'), U('index/login'));
        exit;
    }

    public function verify_code() {
        Image::buildImageVerify(4,1,'png','50','24');
    }

    public function left() {
        $menuid = $this->_request('menuid', 'intval');
        if ($menuid) {
            $left_menu = $this->_mod->admin_menu($menuid);
            foreach ($left_menu as $key=>$val) {
                $left_menu[$key]['sub'] = $this->_mod->admin_menu($val['id']);
            }
        } else {
            $left_menu[0] = array('id'=>0,'name'=>L('common_menu'));
            $left_menu[0]['sub'] = array();
            if ($r = $this->_mod->where(array('often'=>1))->select()) {
                $left_menu[0]['sub'] = $r;
            }
            array_unshift($left_menu[0]['sub'], array('id'=>0,'name'=>L('common_menu_set'),'module_name'=>'index','action_name'=>'often_menu'));
        }
        $this->assign('left_menu', $left_menu);
        $this->display();
    }

    public function often() {
        if (isset($_POST['do'])) {
            $id_arr = isset($_POST['id']) && is_array($_POST['id']) ? $_POST['id'] : '';
            $this->_mod->where(array('ofen'=>1))->save(array('often'=>0));
            $id_str = implode(',', $id_arr);
            $this->_mod->where('id IN('.$id_str.')')->save(array('often'=>1));
            $this->success(L('operation_success'));
        } else {
            $r = $this->_mod->admin_menu(0);
            $list = array();
            foreach ($r as $v) {
                $v['sub'] = $this->_mod->admin_menu($v['id']);
                foreach ($v['sub'] as $key=>$sv) {
                    $v['sub'][$key]['sub'] = $this->_mod->admin_menu($sv['id']);
                }
                $list[] = $v;
            }
            $this->assign('list', $list);
            $this->display();
        }
    }

    public function map() {
        $r = $this->_mod->admin_menu(0);
        $list = array();
        foreach ($r as $v) {
            $v['sub'] = $this->_mod->admin_menu($v['id']);
            foreach ($v['sub'] as $key=>$sv) {
                $v['sub'][$key]['sub'] = $this->_mod->admin_menu($sv['id']);
            }
            $list[] = $v;
        }
        $this->assign('list', $list);
        $this->display();
    }
}