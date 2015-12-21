<?php

class userModel extends Model
{
    protected $_validate = array(
        array('username', 'require', '{%username_require}'), //不能为空
        array('repassword', 'password', '{%inconsistent_password}', 0, 'confirm'), //确认密码
        array('email', 'email', '{%email_error}'), //邮箱格式
        array('username', '1,20', '{%username_length_error}', 0, 'length', 1), //用户名长度
        array('password', '6,20', '{%password_length_error}', 0, 'length', 1), //密码长度
        array('username', '', '{%username_exists}', 0, 'unique', 1), //新增的时候检测重复
    );

	protected $_map = array(
        'sex' =>'gender', // 把表单中name映射到数据表的username字段
        'mail'  =>'email', // 把表单中的mail映射到数据表的email字段
    );

    protected $_auto = array(
        array('password','md5',1,'function'), //密码加密
        array('reg_time','time',1,'function'), //注册时间
        array('reg_ip','get_client_ip',1,'function'), //注册IP
    );

    /**
     * 修改用户名
     */
    public function rename($map, $newname) {
        if ($this->where(array('username'=>$newname))->count('id')) {
            return false;
        }
        $this->where($map)->save(array('username'=>$newname));
        $uid = $this->where(array('username'=>$newname))->getField('id');
        //修改商品表中的用户名
        M('items')->where(array('uid'=>$uid))->save(array('uname'=>$newname));
        return true;
    }

	

    public function name_exists($name, $id = 0) {
        $where = "username='" . $name . "' AND id<>'" . $id . "'";
        $result = $this->where($where)->count('id');
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function email_exists($email, $id = 0) {
        $where = "email='" . $email . "' AND id<>'" . $id . "'";
        $result = $this->where($where)->count('id');
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

	/**
     * 邀请注册奖励
     */
    public function union_reg($date) {
		$ip = get_client_ip();
        if (D('union')->where(array('ip'=>$ip))->find()) {
			$union_user				= D('union')->where(array('ip'=>$ip))->find();
			if(!$union_user['ouid']){
				$union_date['score']	= C('ftx_score_rule.union_reg');
				$union_date['ouid']		= $date['uid'];
				$union_date['ousername']= $date['username'];
				D('union')->where(array('ip'=>$ip))->save($union_date);

				//积分增加
				$this->where(array('id'=>$union_user['uid']))->setInc('score',C('ftx_score_rule.union_reg'));
				//添加增加积分记录
				$score_log_date['uid']		= $union_user['uid'];
				$score_log_date['uname']	= $union_user['username'];
				$score_log_date['action']	= 'union_reg';
				$score_log_date['score']	= C('ftx_score_rule.union_reg');
				D('score_log')->create($score_log_date);
				D('score_log')->add();

				$union_date['ouid']		= $date['uid'];
				$union_date['ousername']= $date['username'];
				D('union')->where(array('ip'=>$ip))->save($union_date);
			}

        }
    }
}