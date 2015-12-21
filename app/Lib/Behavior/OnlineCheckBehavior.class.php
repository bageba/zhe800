<?php
defined('THINK_PATH') or exit();
/*
 * 定义行为: 在线更新
 */

class OnlineCheckBehavior extends Behavior {

    //行为参数
    protected $options = array(
        'ONLINE_CHECK' => true,			// 默认进行在线
        'ONLINE_CHECK_TIME' => 1440,	// 默认24小时未活动，说明已下线
    );

    public function run(&$params) {
        if (C('ONLINE_CHECK')) {
            // 更新session
            if ((session('?user_info')) && (time() - session('access_time') > 60)) {
                session('access_time', time());
            }
            // 在线更新
            $ip = get_client_ip();
            $online = M('Online');
            $map['lasttime'] = array('lt', time() - C('ONLINE_CHECK_TIME') * 60);
            $icount = $online->where($map)->delete();
            if (session('?user_info')) { // 如果是登录用户
				$user_info = session('user_info');
                $map = array();
                $map['uid'] = $user_info['id'];
                //$map['ip'] = $ip;
                $ids = $online->where($map)->getField('id');
                if (empty($ids)) { // 不存在在线记录，则清空session
                    session('user_info', null);
					cookie('user_info', null);
                } else {
                    $map = array();
                    $map['id'] = array('eq', $id);
                    $data['lasttime'] = time();
                    $data['ip'] = $ip;
                    $online->where($map)->save($data);
                }
            } else { // 不是登录用户  游客
                unset($map);
                $map['ip'] = array('eq', $ip);
                $ids = $online->where($map)->getField('id');
                //dump($id);
                if (empty($ids)) { // 不存在在线记录， 则添加
                    $data = array();
                    $data['uid'] = 0;
                    $data['account'] = 'Guest';
                    $data['username'] = '游客';
                    $data['lasttime'] = time();
                    $data['ip'] = $ip;
                    $online->add($data);
                } else {
                    $map = array();
                    $map['id'] = array('eq', $id);
                    $data['lasttime'] = time();
                    $data['ip'] = $ip;
                    $online->where($map)->save($data);
                }
            }
        }
    }
}

