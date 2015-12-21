<?php

class forgetAction extends FirstendAction {

    /**
    * 填写信息
    */
    public function index() {
        if (IS_POST) {
			$submit = I('submits','','trim');
			if('找回密码' == $submit){
				$captcha = I('captcha','', 'trim');
				$email = I('email','','trim');
				session('captcha') != md5($captcha) && $this->error(L('captcha_failed'));
				$tpl_data = array();
				$tpl_data['email'] = $email;
				//验证
				$user = M('user')->where(array('email'=>$email))->find();
				!$user && $this->error(L('user_not_exists'));
				//生成随机码
				$time = time();
				$activation = md5($user['reg_time'] . substr($user['password'], 10) . $time);
				$url_args = array('username'=>$user['username'], 'activation'=>$activation, 't'=>$time);
				$tpl_data['reset_url'] = U('forget/reset', $url_args, '', '', true);
				$tpl_data['username'] = $user['username'];
				//解析邮件模板
				$mail_body = D('message_tpl')->get_mail_info('findpwd', $tpl_data);
				//发送邮件
				$this->_mail_queue($user['email'], C('ftx_site_name').'帐号'.$user['username'].L('forget'), $mail_body);
				$this->success(L('forget_mail_sended'), U('user/login'));
			}
			else if('查询邮箱' == $submit){
				$captcha = I('captcha','', 'trim');
				session('captcha') != md5($captcha) && $this->error(L('captcha_failed'));
				$tpl_data = array();
				$tpl_data['username'] = $username = I('username','','trim');
				//验证
				$user = M('user')->where(array('username'=>$username))->find();
				!$user && $this->error(L('user_not_exists'));
				if($user['email']){
					$email = $this->str_mid_replace($user['email']);
					$msg = '您注册时使用的邮箱是：'.$email;
				}else{
					$msg = '您未设置邮箱！';
				}

				$this->assign('msg',$msg);
				$this->_config_seo(array(
					'title' => ' 找回密码	-	' . C('ftx_site_name'),
				));
				$this->display();
			}
        } else {
            $this->_config_seo(array(
				'title' => ' 找回密码	-	' . C('ftx_site_name'),
			));
            $this->display();
        }
    }

    /**
     * 重置密码
     */
    public function reset() {
        //检测链接合法性
        $username	= I('username','', 'trim');
        $activation = I('activation','', 'trim');
        $t			= I('t','', 'intval');
        if (!$username || !$activation || !$t) {
            $this->redirect('index/index');
        }
        //判断是否已经过期
        $time = time();
        ($time - $t) > 86400 && $this->error(L('forget_link_expired'), U('forget/index'));
        //验证用户
        $user = M('user')->field('id,reg_time,password')->where(array('username'=>$username))->find();
        !$user && $this->error(L('username').L('not_exist'), U('index/index'));
        if ($activation != md5($user['reg_time'] . substr($user['password'], 10) . $t)) {
            $this->error(L('forget_link_error'), U('index/index'));
        }
        if (IS_POST) {
            $captcha = I('captcha','', 'trim');
            session('captcha') != md5($captcha) && $this->error(L('captcha_failed'));
            
            $password   = I('password','','trim');
            $repassword = I('repassword','','trim');
            !$password && $this->error(L('no_new_password'));
            $password != $repassword && $this->error(L('inconsistent_password'));
            $passlen = strlen($password);
            if ($passlen < 6 || $passlen > 20) {
                $this->error('password_length_error');
            }
            //连接用户中心
            $passport = $this->_user_server();
            $result = $passport->edit($user['id'], '', array('password'=>$password), true);
            if (!$result) {
                $this->error($passport->get_error());
            }
            $this->success(L('reset_password_success'), U('user/login'));
        } else {
            $this->_config_seo(array(
					'title' => ' 重置密码	-	' . C('ftx_site_name'),
				));
            $this->display();
        }
    }
}