<?php

class signAction extends UsersAction {

	public function _initialize(){
        parent::_initialize();
        //访问者控制
        if (!$this->visitor->is_login) {
            IS_AJAX && $this->ajaxReturn(0, L('login_please'));
            $this->redirect('user/login');
        }
		$this->_sign_mod = D('sign');
		$this->_user_mod = D('user');
		$this->_sign_log_mod = D('sign_log');
		$this->_score_log_mod = D('score_log');
    }



	public function ajax_sign(){		
		$data = array(
                'uid' => $this->visitor->info['id'],
                'username' => $this->visitor->info['username'],
            );

		$point_score = $this->point();
        if (!$this->_sign_mod->where($data)->find()) {
			//如果从未签到过

			/*积分奖励算法*/
			//$this->_user_mod->where($data)->setInc('score',$this->point());
			//$this->_user_mod->where($data)->setField('sign_time',strtotime(date('Ymd')));

			$score_data = array('score'=>array('exp','score+'.$point_score), 'sign_time'=>strtotime(date('Ymd')));
            $this->_user_mod->where(array('id'=>$data['uid']))->setField($score_data); //改变用户积分

			//更新为已经签到
			$this->_sign_mod->create($data);
            $this->_sign_mod->add();
			//添加一条签到记录
			$data['score'] = $point_score;
			$this->_sign_log_mod->create($data);
			$this->_sign_log_mod->add();
			//添加一条积分记录
			$score_log_data['uid']		=	$this->visitor->info['id'];
			$score_log_data['uname']	=	$this->visitor->info['username'];
			$score_log_data['action']	=	'sign';
			$score_log_data['score']	=	$point_score;
			$this->_score_log_mod->create($score_log_data);
			$this->_score_log_mod->add();
			
		}else{
			//如果签到过
			$sign_date=$this->_sign_mod->where($data)->find();
			$last_date=strtotime(date('Y-m-d'));
			if($sign_date['last_date']==$last_date){
				//今天已经签到
				$cl = new calender();
				$data['table']=$cl->sign_calender();
				$data['point']=$point_score;
				$data['tmr_point']=$point_score ;
				$this->ajaxReturn(1, L('sign_system'), $data);

			}else if($sign_date['last_date']+86400==$last_date){
				//今天未签到昨天签到过
				/*积分奖励算法*/
				//$this->_user_mod->where($data)->setInc('score',$point_score);
				//$this->_user_mod->where($data)->setField('sign_time',strtotime(date('Ymd')));
				$score_data = array('score'=>array('exp','score+'.$point_score), 'sign_time'=>strtotime(date('Ymd')));
				$this->_user_mod->where(array('id'=>$data['uid']))->setField($score_data); //改变用户积分

				//更新为已经签到
				$this->_sign_mod->where($data)->setInc('sign_count');
				$this->_sign_mod->where($data)->setField('last_date',strtotime(date('Ymd')));
				
				//添加一条签到记录
				$data['score'] = $point_score;
				$this->_sign_log_mod->create($data);
				$this->_sign_log_mod->add();
				//添加一条积分记录
				$score_log_data['uid']		=	$this->visitor->info['id'];
				$score_log_data['uname']	=	$this->visitor->info['username'];
				$score_log_data['action']	=	'sign';
				$score_log_data['score']	=	$point_score;
				$this->_score_log_mod->create($score_log_data);
				$this->_score_log_mod->add();
				
			}else{
				//无连续签到  从头算起

				/*积分奖励算法*/
				//$this->_user_mod->where($data)->setInc('score',$point_score);
				//$this->_user_mod->where($data)->setField('sign_time',strtotime(date('Ymd')));
				$score_data = array('score'=>array('exp','score+'.$point_score), 'sign_time'=>strtotime(date('Ymd')));
				$this->_user_mod->where(array('id'=>$data['uid']))->setField($score_data); //改变用户积分

				//更新为已经签到
				$sign_data=$data;
				$sign_data['sign_count'] = '1';
				$sign_data['last_date'] = strtotime(date('Ymd'));
				$this->_sign_mod->where($data)->save($sign_data);
				//添加一条签到记录
				$data['score'] = $point_score;
				$this->_sign_log_mod->create($data);
				$this->_sign_log_mod->add();
				//添加一条积分记录
				$score_log_data['uid']		=	$this->visitor->info['id'];
				$score_log_data['uname']	=	$this->visitor->info['username'];
				$score_log_data['action']	=	'sign';
				$score_log_data['score']	=	$point_score;
				$this->_score_log_mod->create($score_log_data);
				$this->_score_log_mod->add();

			}
		}
		$cl = new calender();
		$data['table']=$cl->sign_calender();
		$data['point']=$point_score;
		if($sign_info = $this->_sign_mod->field('id,username,last_date,sign_count')->where(array('uid'=>$this->visitor->info['id']))->find()){
			if($sign_info['sign_count']>= C('ftx_score_rule.sign_day')){
				$data['tmr_point']=$point_score;
			}else{
				$data['tmr_point']=$point_score + C('ftx_score_rule.sign_add');
			}
		}
		$this->ajaxReturn(1, L('sign_system'), $data);
	}

	public function point(){
		$jinbi = C('ftx_score_rule.sign');
		$sign_mod = M('sign');
		$sign_info = $sign_mod->field('id,username,last_date,sign_count')->where(array('uid'=>$this->visitor->info['id']))->find();
		$last_date=strtotime(date('Y-m-d'));
		if($sign_info){
			if($sign_info['last_date']==$last_date){
				//今天已经签到
				if($sign_info['sign_count']>=1){
					$jinbi = C('ftx_score_rule.sign') + ($sign_info['sign_count'])*C('ftx_score_rule.sign_add'); 
					if($sign_info['sign_count']>= C('ftx_score_rule.sign_day')){
						$jinbi = C('ftx_score_rule.sign') +  (C('ftx_score_rule.sign_day')-1)*C('ftx_score_rule.sign_add'); 
					}
				}else{
					$jinbi = C('ftx_score_rule.sign');
				}
				//$jinbi = C('ftx_score_rule.sign') + ($sign_info['sign_count'])*C('ftx_score_rule.sign_add');
				//$jinbi=0;		
			}else if($sign_info['last_date']+86400==$last_date){
				//今天未签到昨天签到过
				if($sign_info['sign_count']>=1){
					$jinbi = C('ftx_score_rule.sign') + ($sign_info['sign_count'])*C('ftx_score_rule.sign_add'); 
					if($sign_info['sign_count']>= C('ftx_score_rule.sign_day')){
						$jinbi = C('ftx_score_rule.sign') +  (C('ftx_score_rule.sign_day')-1)*C('ftx_score_rule.sign_add'); 
					}
				}else{
					$jinbi = C('ftx_score_rule.sign');
				}
			}else{
				$jinbi = C('ftx_score_rule.sign');
			}
		}
		return $jinbi;
	}

	public function signstatus(){		
		$sign_mod=M('sign');
		$sign_info = $sign_mod->field('id,username,last_date,sign_count')->where(array('uid'=>$this->visitor->info['id']))->find();
		$last_date=strtotime(date('Y-m-d'));
		if($sign_info['last_date']==$last_date){
			if($sign_info['sign_count']>1){
				$jinbi = C('ftx_score_rule.sign') + ($sign_info['sign_count']-1)*C('ftx_score_rule.sign_add'); 
				if($sign_info['sign_count']> C('ftx_score_rule.sign_day')){
					$jinbi = C('ftx_score_rule.sign') +  (C('ftx_score_rule.sign_day')-1)*C('ftx_score_rule.sign_add'); 
				}
			}else{
				$jinbi = C('ftx_score_rule.sign');
			}
			$user_mod=M('user');
			$user_info = $user_mod->field('id,username,score')->where(array('id'=>$this->visitor->info['id']))->find();


			$result['today'] = '已签到 + '.$jinbi;
			$result['score'] = $user_info['score'];
			$this->ajaxReturn(1, 'ok', $result);		
		}else{
			$this->ajaxReturn(0, 'sign_out');	
		
		}
	}


}