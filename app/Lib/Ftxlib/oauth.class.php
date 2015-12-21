<?php

/**
 * 第三方登陆
 *
 * @author andery
 */
class oauth {

    private $_type = '';
    private $_setting = array();

    public function __construct($type) {
        $this->_type = $type;
        //加载登陆接口配置
        $setting = M('oauth')->where(array('code' => $type))->getField('config');
        $this->_setting = unserialize($setting);
        //导入接口文件
        include_once LIB_PATH . 'Ftxlib/oauth/' . $type . '/' . $type . '.php';
        $om_class = $type . '_oauth';
        $this->_om = new $om_class($this->_setting);
    }

    /**
     * 跳转到授权页面
     */
    public function authorize() {
        redirect($this->_om->getAuthorizeURL());
    }

    /**
     * 登陆回调
     */
    public function callbackLogin($request_args) {
        $user = $this->_om->getUserInfo($request_args);
        $bind_user = $this->_checkBind($this->_type, $user['keyid']);
        if ($bind_user) {
            //已经绑定过则更新绑定信息 自动登陆
            $this->_updateBindInfo($user);
            $user_info = M('user')->field('id,username,sign_time,score,status')->where(array('id' => $bind_user['uid']))->find();
			if ($this->isOnline($user_info['id'])) {
				if ($user_info['status']) {
					//检测是否有同一IP的记录，有更新，否则 添加
					$online = M('Online');
					$map = array();
					$map['ip'] = get_client_ip();
					$online_id = $online->where($map)->getField('id');
					if (empty($online_id)) {
						//插入在线用户表
						$data = array();
						$data['uid'] = $user_info['id'];
						$data['account'] = $user_info['username'];
						$data['username'] = $user_info['username'];
						$data['lasttime'] = time();
						$data['ip'] = get_client_ip();
						$online->add($data);
					}else{
						 //更新在线用户表
						$data = array();
						$data['uid'] = $user_info['id'];
						$data['account'] = $user_info['username'];
						$data['username'] = $user_info['username'];
						$data['lasttime'] = time();
						$online->where($map)->save($data);
					}
				}else{
					$is_error = true; //帐号已经被锁定
					session('user_info', null);
					cookie('user_info', null);
				}
			}
            //登陆
            $this->_oauth_visitor()->assign_info($user_info);
            return U('index/index');
        } else {
            //处理用户名
            if (M('user')->where(array('username' => $user['keyname']))->count()) {
                $user['ftx_user_name'] = $user['keyname'] . '_' . mt_rand(99, 9999);
            } else {
                $user['ftx_user_name'] = $user['keyname'];
            }
			$username = $user['ftx_user_name'];
            $user['ftx_user_name'] = urlencode($user['ftx_user_name']);
            $user['keyname'] = urlencode($user['keyname']);
            if ($user['keyavatar_big']) {
                //下载原始头像到本地临时储存  用日期文件夹分类  方便清理
                $user['temp_avatar'] = '';
                $avatar_temp_root = C('ftx_attach_path') . 'avatar/temp/';
                $temp_dir = date('ymd', time()) . '/';
                $file_name = date('ymdhis' . mt_rand(1000, 9999)) . '.jpg';

                mkdir($avatar_temp_root . $temp_dir);
                $image_content = Http::fsockopenDownload($user['keyavatar_big']);
                file_put_contents($avatar_temp_root . $temp_dir . $file_name, $image_content);
                $user['temp_avatar'] = $temp_dir . $file_name;
            }
            $user['type'] = $this->_type;
 
			
			 //连接用户中心
			$passport = new passport(C('ftx_integrate_code'));
           // $passport = $this->_user_server();
            //注册
			$passw = mt_rand(111111,999999);
            $uid = $passport->register($username, $passw);
            !$uid && exit($passport->get_error());

			$bind_info = array(
                    'ftx_uid' => $uid,
					'ftx_username' => $username,
                    'keyid' => $user['keyid'],
                    'bind_info' => $user['bind_info'],
                );

			$this->bindByData($bind_info);

            $tag_arg = array('uid'=>$uid, 'uname'=>$username, 'action'=>'register');
            tag('register_end', $tag_arg);
			//邀请注册奖励
			$union_date = array('uid'=>$uid, 'username'=>$username);
			D('user')->union_reg($union_date);
            //登陆
			$this->visitor = new user_visitor();
            $this->visitor->login($uid);
            //登陆完成
            $tag_arg = array('uid'=>$uid, 'uname'=>$username, 'action'=>'login');
            tag('login_end', $tag_arg);
            //同步登陆
            $synlogin = $passport->synlogin($uid);
			
			//$this->success(L('register_successe').$synlogin.'您的密码为 '.$passw.'  ', U('user/index'));
			return U('index/index');  
        }
    }

    /**
     * 绑定回调
     */
    public function callbackBind($request_args) {
        if (!session('user_info')) {
            return U('user/login');
        }
        $ftx_user = session('user_info');
        $user = $this->_om->getUserInfo($request_args);
        $bind_user = $this->_checkBind($this->_type, $user['keyid']);
        if ($bind_user['uid'] && $bind_user['uid'] != $ftx_user['id']) {
			header("Content-type: text/html; charset=utf-8"); 
            //exit('This ID is binding the name "'.$bind_user['username'].'"');
			die('<script language="javascript">alert("秒杀网提示您：\n\n该账号已经与用户 '.$bind_user['username'].' 绑定，请换其他帐号登入或绑定");this.location.href="'.U('user/bind').'";</script>');
        }
        $user['ftx_uid'] = $ftx_user['id'];
		$user['ftx_username'] = $ftx_user['username'];
        $this->bindUser($user);
        return U('user/bind');
    }

    /**
     * 更新绑定信息
     */
    private function _updateBindInfo($user) {
        $info = serialize($user['bind_info']);
        M('user_bind')->where(array('keyid' => $user['keyid']))->save(array('info' => $info));
    }

    /**
     * 绑定帐号
     */
    public function bindUser($user) {
        $bind_info = serialize($user['bind_info']);
        $bind_user = array(
            'uid' => $user['ftx_uid'],
			'username' => $user['ftx_username'],
            'type' => $this->_type,
            'keyid' => $user['keyid'],
            'info' => $bind_info
        );
        M('user_bind')->add($bind_user);
    }

    //用户完善信息之后绑定 需要手动增加qp_uid值
    public function bindByData($user) {
        $this->bindUser($user);
    }

    /**
     * 检测用户是否已经绑定过本站
     */
    private function _checkBind($type, $key_id) {
        return M('user_bind')->where(array('type' => $type, 'keyid' => $key_id))->find();
    }

    /**
     * 访问者
     */
    private function _oauth_visitor() {
        include_once (FTXIA_PATH . 'app/Lib/Ftxlib/user_visitor.class.php');
        return new user_visitor();
    }

    /**
     * 返回需要的参数
     */
    public function NeedRequest() {
        return $this->_om->NeedRequest();
    }

	
	/**
     * 检测用户是否在线
     * @access private
     * @param int $uid 用户ID
     * @return Boolean true=不在线
     */
    private function isOnline($uid) {
        $ip = get_client_ip();
        $online = M('Online');
        $map['uid'] = array('eq', $uid);
        $list = $online->where($map)->find();
        if (empty($list)) { // 不存在
            return true;
        } else { // 存在，检测IP是否一致，否则，检测是否超过5分钟
            if ($list['ip'] == $ip) {
                return true;
            } else {
                if ($list['lasttime'] < time() - C('ONLINE_CHECK_TIME') * 60) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

}