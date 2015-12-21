<?php
/**
 * 访问者
 *
 * @author andery
 */
class user_visitor {

    public $is_login = false; //登陆状态
    public $info = null;

    public function __construct() {
        if (session('user_info')) {
            //已经登陆
            $this->info = session('user_info');
            $this->is_login = true;
        } elseif ($user_info = (array)cookie('user_info')) {
            $user_info = M('user')->field('id,username,sign_time,score')->where(array('id'=>$user_info['id'], 'password'=>$user_info['password']))->find();
			//查询是否签到
			$sign_mod = D('sign');
			$sign_date=$sign_mod->where(array('uid' => $uid))->find();
			$data['last_date']=strtotime(date('Y-m-d'));
			if($sign_date['last_date']==$data['last_date']){
				$user_info['sign'] = 'sign_in';
			}else{
				$user_info['sign'] = 'sign_out';
			}

            if ($user_info) {
                //记住登陆状态
                $this->assign_info($user_info);
                $this->is_login = true;
            }
        } else {
            $this->is_login = false;
        }
    }

    /**
     * 登陆会话
     */
    public function assign_info($user_info) {
        session('user_info', $user_info);
        $this->info = $user_info;
    }

    /**
     * 记住密码
     */
    public function remember($user_info, $remember = null) {
        if ($remember) {
            $time = 3600 * 24 * 14; //两周
            cookie('user_info', array('id'=>$user_info['id'], 'password'=>$user_info['password']), $time);
		}
    }

    /**
     * 获取用户信息
     */
    public function get($key = null) {
        $info = null;
        if (is_null($key) && $this->info['id']) {
            $info = M('user')->find($this->info['id']);
        } else {
            if (isset($this->info[$key])) {
                return $this->info[$key];
            } else {
                //获取用户表字段
                $fields = M('user')->getDbFields();
                if (!is_null(array_search($key, $fields))) {
                    $info = M('user')->where(array('id' => $this->info['id']))->getField($key);
                }
            }
        }
        return $info;
    }

    /**
     * 登陆
     */
    public function login($uid, $remember = null) {
        $user_mod = M('user');
        //更新用户信息
        $user_mod->where(array('id' => $uid))->save(array('last_time' => time(), 'last_ip' => get_client_ip()));
		$user_mod->where(array('id' => $uid))->setInc('login_count', 1);
        $user_info = $user_mod->field('id,username,password,sign_time,score,status')->find($uid);

		//查询是否签到
		$sign_mod = D('sign');
		$sign_date=$sign_mod->where(array('uid' => $uid))->find();
		$data['last_date']=strtotime(date('Y-m-d'));
		if($sign_date['last_date']==$data['last_date']){
			$user_info['sign'] = 'sign_in';
		}else{
			$user_info['sign'] = 'sign_out';
		}
		//if ($this->isOnline($user_info['id'])) {
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
		//}

        //保持状态
        $this->assign_info($user_info);
        $this->remember($user_info, $remember);
    }

    /**
     * 退出
     */
    public function logout() {
		$user_info =  session('user_info');
		$online = M('Online');
		$map['uid'] = $user_info['id'];
        $online->where($map)->delete();
        session('user_info', null);
        cookie('user_info', null);
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