<?php

class userAction extends UsersAction {

    /**
     * 用户登陆
     */
    public function login() {
        $this->visitor->is_login && $this->redirect('user/index');
        if (IS_POST) {
            $username = $this->_post('username', 'trim');
            $password = $this->_post('password', 'trim');
            $remember = $this->_post('remember');
            if (empty($username)) {
                IS_AJAX && $this->ajaxReturn(0, L('please_input').L('password'));
                $this->error(L('please_input').L('username'));
            }
            if (empty($password)) {
                IS_AJAX && $this->ajaxReturn(0, L('please_input').L('password'));
                $this->error(L('please_input').L('password'));
            }
            //连接用户中心
            $passport = $this->_user_server();
            $uid = $passport->auth($username, $password);
            if (!$uid) {
                IS_AJAX && $this->ajaxReturn(0, $passport->get_error());
                $this->error($passport->get_error());
            }
            //登陆
            $this->visitor->login($uid, $remember);

            //登陆完成钩子
            $tag_arg = array('uid'=>$uid, 'uname'=>$username, 'action'=>'login');
            tag('login_end', $tag_arg);
            //同步登陆
            $synlogin = $passport->synlogin($uid);
            if (IS_AJAX) {
                $this->ajaxReturn(1, L('login_successe').$synlogin);
            } else {
				$this->redirect('index/index');
                //跳转到登陆前页面（执行同步操作）
                $ret_url = $this->_post('ret_url', 'trim');
                $this->success(L('login_successe').$synlogin, $ret_url);
            }
        } else {
            /* 同步退出外部系统 */
            if (!empty($_GET['synlogout'])) {
                $passport = $this->_user_server();
                $synlogout = $passport->synlogout();
            }
            if (IS_AJAX) {
                $resp = $this->fetch('dialog:login');
                $this->ajaxReturn(1, '', $resp);
            } else {
                //来路
                $ret_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : __APP__;
                $this->assign('ret_url', $ret_url);
                $this->assign('synlogout', $synlogout);
				$this->_config_seo(array(
					'title' => C('ftx_site_name').'登录,登陆'. C('ftx_site_name').'    -    '.C('ftx_site_name'),
				));
                $this->display();
            }
        }
    }

    /**
     * 用户退出
     */
    public function logout() {
        $this->visitor->logout();
        //同步退出
        $passport = $this->_user_server();
        $synlogout = $passport->synlogout();
        //跳转到退出前页面（执行同步操作）
		redirect($_SERVER['HTTP_REFERER']);
        //$this->success(L('logout_successe').$synlogout, U('index/index'));
    }

    /**
     * 用户绑定
     */
    public function binding() {
        $user_bind_info = object_to_array(cookie('user_bind_info'));
        $this->assign('user_bind_info', $user_bind_info);
        $this->_config_seo();
        $this->display();
    }

    /**
    * 用户注册
    */
    public function register() {
        $this->visitor->is_login && $this->redirect('user/index');
        if (IS_POST) {
            //方式
            $type = $this->_post('type', 'trim', 'reg');
            if ($type == 'reg') {
                //验证
                $agreement = $this->_post('agreement');
                !$agreement && $this->error(L('agreement_failed'));

                $captcha = $this->_post('captcha', 'trim');
                if(session('captcha') != md5($captcha)){
                    $this->error(L('captcha_failed'));
                }
            }
            $username = $this->_post('username', 'trim');
            $email = $this->_post('email','trim');
            $password = $this->_post('password', 'trim');
            $repassword = $this->_post('repassword', 'trim');
            if ($password != $repassword) {
                $this->error(L('inconsistent_password')); //确认密码
            }
            $gender = $this->_post('gender','intval', '0');
            //用户禁止
            $ipban_mod = D('ipban');
            $ipban_mod->clear(); //清除过期数据
            $is_ban = $ipban_mod->where("(type='name' AND name='".$username."') OR (type='email' AND name='".$email."')")->count();
            $is_ban && $this->error(L('register_ban'));
            //连接用户中心
            $passport = $this->_user_server();
            //注册
            $uid = $passport->register($username, $password, $email, $gender);
            !$uid && $this->error($passport->get_error());
            //第三方帐号绑定
            if (cookie('user_bind_info')) {
                $user_bind_info = object_to_array(cookie('user_bind_info'));
                $oauth = new oauth($user_bind_info['type']);
                $bind_info = array(
                    'ftx_uid' => $uid,
					'ftx_username' => $username,
                    'keyid' => $user_bind_info['keyid'],
                    'bind_info' => $user_bind_info['bind_info'],
                );
                $oauth->bindByData($bind_info);
                $this->_save_avatar($uid, $user_bind_info['temp_avatar']);
                cookie('user_bind_info', NULL);
            }
            $tag_arg = array('uid'=>$uid, 'uname'=>$username, 'action'=>'register');
            tag('register_end', $tag_arg);
			//邀请注册奖励
			$union_date = array('uid'=>$uid, 'username'=>$username);
			D('user')->union_reg($union_date);
            //登陆
            $this->visitor->login($uid);
            //登陆完成
            $tag_arg = array('uid'=>$uid, 'uname'=>$username, 'action'=>'login');
            tag('login_end', $tag_arg);
            //同步登陆
            $synlogin = $passport->synlogin($uid);
            $this->success(L('register_successe').$synlogin, U('user/index'));
        } else {
            //关闭注册
            if (!C('ftx_reg_status')) {
                $this->error(C('ftx_reg_closed_reason'));
            }
            $this->_config_seo(array(
				'title' => ' 注册	-	' . C('ftx_site_name'),
			));
            $this->display();
        }
    }

    /**
     * 第三方头像保存
     */
    private function _save_avatar($uid, $img) {
        //获取后台头像规格设置
        $avatar_size = explode(',', C('ftx_avatar_size'));
        //会员头像保存文件夹
        $avatar_dir = C('ftx_attach_path') . 'avatar/' . avatar_dir($uid);
        !is_dir($avatar_dir) && mkdir($avatar_dir,0777,true);
        //生成缩略图
        $img = C('ftx_attach_path') . 'avatar/temp/' . $img;
        foreach ($avatar_size as $size) {
            Image::thumb($img, $avatar_dir.md5($uid).'_'.$size.'.jpg', '', $size, $size, true);
        }
        @unlink($img);
    }
    
    /**
     * 用户消息提示 
     */
    public function msgtip() {
        $result = D('user_msgtip')->get_list($this->visitor->info['id']);
        $this->ajaxReturn(1, '', $result);
    }

    /**
    * 基本信息修改
    */
    public function index() {
        if( IS_POST ){
            foreach ($_POST as $key=>$val) {
                $_POST[$key] = Input::deleteHtmlTags($val);
            }
            $data['gender'] = $this->_post('gender', 'intval');
            $data['province'] = $this->_post('province', 'trim');
            $data['city'] = $this->_post('city', 'trim');
            $data['intro'] = $this->_post('intro', 'trim');
            $data['truename'] = $this->_post('truename', 'trim');
            $data['mobile'] = $this->_post('mobile', 'intval');
            $data['telephone'] = $this->_post('telephone', 'trim');
            $data['address'] = $this->_post('address', 'trim');
            $data['qq'] = $this->_post('qq', 'intval');
            $data['wangwang'] = $this->_post('wangwang', 'trim');
            $birthday = $this->_post('birthday', 'trim');
            $birthday = explode('-', $birthday);
            $data['byear'] = $birthday[0];
            $data['bmonth'] = $birthday[1];
            $data['bday'] = $birthday[2];
            if (false !== M('user')->where(array('id'=>$this->visitor->info['id']))->save($data)) {
                $msg = array('status'=>1, 'info'=>L('edit_success'));
            }else{
                $msg = array('status'=>0, 'info'=>L('edit_failed'));
            }
            $this->assign('msg', $msg);
        }
        $info = $this->visitor->get();
        $this->assign('info', $info);
        $this->_config_seo(array(
            'title' => L('base_setting') . '	-	' . C('ftx_site_name'),
        ));
        $this->display();
    }

    /**
     * 修改头像
     */
    public function upload_avatar() {
        if (!empty($_FILES['avatar']['name'])) {
            //会员头像规格
            $avatar_size = explode(',', C('ftx_avatar_size'));
            //回去会员头像保存文件夹
            $uid = abs(intval($this->visitor->info['id']));
            $suid = sprintf("%09d", $uid);
            $dir1 = substr($suid, 0, 3);
            $dir2 = substr($suid, 3, 2);
            $dir3 = substr($suid, 5, 2);
            $avatar_dir = $dir1.'/'.$dir2.'/'.$dir3.'/';
            //上传头像
            $suffix = '';
            foreach ($avatar_size as $size) {
                $suffix .= '_'.$size.',';
            }
            $result = $this->_upload($_FILES['avatar'], 'avatar/'.$avatar_dir, array(
                'width'=>C('ftx_avatar_size'), 
                'height'=>C('ftx_avatar_size'),
                'remove_origin'=>true, 
                'suffix'=>trim($suffix, ','),
                'ext' => 'jpg',
            ), md5($uid));
            if ($result['error']) {
                $this->ajaxReturn(0, $result['info']);
            } else {
                $data = __ROOT__.'/data/upload/avatar/'.$avatar_dir.md5($uid).'_'.$size.'.jpg?'.time();
                $this->ajaxReturn(1, L('upload_success'), $data);
            }
        } else {
            $this->ajaxReturn(0, L('illegal_parameters'));
        }
    }

    /**
     * 修改密码
     */
    public function password() {
        if( IS_POST ){
            $oldpassword = $this->_post('oldpassword','trim');
            $password   = $this->_post('password','trim');
            $repassword = $this->_post('repassword','trim');
            !$password && $this->error(L('no_new_password'));
            $password != $repassword && $this->error(L('inconsistent_password'));
            $passlen = strlen($password);
            if ($passlen < 6 || $passlen > 20) {
                $this->error('password_length_error');
            }
            //连接用户中心
            $passport = $this->_user_server();
            $result = $passport->edit($this->visitor->info['id'], $oldpassword, array('password'=>$password));
            if ($result) {
				$this->success(L('edit_password_success'), U('user/login'));
            } else {
                $msg = array('status'=>0, 'info'=>$passport->get_error());
            }
            $this->assign('msg', $msg);
        }
        $this->_config_seo(array(
            'title' => L('edit_password') . '	-	' . C('ftx_site_name'),
        ));
        $this->display();
    }
	/**
	 * 邀请好友
	 */
	public function union() {
		$p = I('p',1);
		$page_size = 20;
		$start = $page_size * ($p - 1) ;
 
        $count = M('union')->where(array('uid'=>$this->visitor->info['id']))->count('id');
        $pager = $this->_pager($count, $page_size);


		$union_list = M('union')->field('id,uid,username,ip,score,ouid,ousername,add_time')->where(array('uid'=>$this->visitor->info['id']))->limit($start . ',' . $page_size)->order('add_time DESC')->select();
		$this->assign('union_list', $union_list);
		$this->assign('page', $pager->fshow());
		$this->_config_seo(array(
            'title' => L('user_union') . '	-	' . C('ftx_site_name'),
        ));
		$this->display();
	}
	
	 
	 public function gift() {
        $map = array();
        $map['uid'] = $this->visitor->info['id'];
        $score_order_mod = M('score_order');
		$p = I('p',1);
		$page_size = 20;
		$start = $page_size * ($p - 1) ;
 
        $count = $score_order_mod->where($map)->count('id');
        $pager = $this->_pager($count, $page_size);
        $order_list = $score_order_mod->field('id,order_sn,item_id,item_name,order_score,status,add_time')->where($map)->limit($start . ',' . $page_size)->order('id DESC')->select();
        $this->assign('order_list', $order_list);
        $this->assign('page', $pager->fshow());
        $this->_curr_menu('order');
        $this->_config_seo(array(
            'title' => L('my_gift') . '	-	' . C('ftx_site_name'),
        ));
        $this->display();
    }
	
	
	
    /**
     * 帐号绑定
     */
    public function bind() {
        //获取已经绑定列表
        $bind_list = M('user_bind')->field('type')->where(array('uid'=>$this->visitor->info['id']))->select();
        $binds = array();
        if ($bind_list) {
            foreach ($bind_list as $val) {
                $binds[] = $val['type'];
            }
        }
        
        //获取网站支持列表
        $oauth_list = $this->oauth_list;
        foreach ($oauth_list as $type => $_oauth) {
            $oauth_list[$type]['isbind'] = '0';
            if (in_array($type, $binds)) {
                $oauth_list[$type]['isbind'] = '1';
            }
        }
        $this->assign('oauth_list', $oauth_list);
        $this->_config_seo(array(
            'title' => L('user_bind') . '	-	' . C('ftx_site_name'),
        ));
        $this->display();
    }

	public function like() {
		$p = I('p',1);
		$page_size = 20;
		$start = $page_size * ($p - 1) ;
		
		$ids_list = D('items_like')->field('id,item_id')->limit($start . ',' . $page_size)->where(array('uid'=>$this->visitor->info['id']))->order('id desc')->select();
		$ids=array(); 
		if($ids_list){
			foreach($ids_list as $val){
				$ids[] = $val['item_id'];
				$item_list[$val['item_id']]=$val['item_id'];
				
			}
		}
		$where['id'] = array('in',$ids);
		$items = D('items')->field('id,volume,shop_type,ems,num_iid,title,price,coupon_price,pic_url,likes,status,coupon_start_time,coupon_end_time')->where($where)->select();
		$count = D('items_like')->where(array('uid'=>$this->visitor->info['id']))->count();
		$pager = $this->_pager($count, $page_size);
		foreach($items as $item){
			$item_list[$item['id']] = $item;
			$item_list[$item['id']]['class']	= D('items')->status($item['status'],$item['coupon_start_time'],$item['coupon_end_time']);
		}
		$this->assign('page', $pager->kshow());
		$this->assign('count', $count);
		$this->assign('items', $item_list);
		$this->_config_seo(array(
			'title' =>  '我的喜欢	-	' . C('ftx_site_name'),
    ));
	if(C('ftx_index_shop_type')){$where['shop_type'] = C('ftx_index_shop_type');}
	if(C('ftx_index_ems') == '1'){
			$where['ems'] = '1';
		}
		$this->display();
	}

 

    /**
     * 检测用户
     */
    public function ajax_check() {
        $type = I('type', 'email', 'trim');
        $user_mod = D('user');
        switch ($type) {
            case 'email':
                $email = I('J_email','', 'trim');
                $user_mod->email_exists($email) ? $this->ajaxReturn(0) : $this->ajaxReturn(1);
                break;
            
            case 'username':
                $username = I('J_username','', 'trim');
                $user_mod->name_exists($username) ? $this->ajaxReturn(0) : $this->ajaxReturn(1);
                break;
        }
    }


}