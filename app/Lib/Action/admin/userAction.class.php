<?php
/**
 * 用户信息管理
 */
class userAction extends BackendAction
{

    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('user');
		$this->_score_log_mod = D('score_log');
    }

    protected function _search() {
        $map = array();
        if( $keyword = $this->_request('keyword', 'trim') ){
            $map['_string'] = "username like '%".$keyword."%' OR email like '%".$keyword."%'";
        }
        $this->assign('search', array(
            'keyword' => $keyword,
        ));
        return $map;
    }

    public function _before_index() {
        $big_menu = array(
            'title' => '添加会员',
            'iframe' => U('user/add'),
            'id' => 'add',
            'width' => '500',
            'height' => '330'
        );
        $this->assign('big_menu', $big_menu);
    }

    public function _before_insert($data) {
        if( ($data['password']!='')&&(trim($data['password'])!='') ){
            $data['password'] = $data['password'];
        }else{
            unset($data['password']);
        }
        $birthday = $this->_post('birthday', 'trim');
        if ($birthday) {
            $birthday = explode('-', $birthday);
            $data['byear'] = $birthday[0];
            $data['bmonth'] = $birthday[1];
            $data['bday'] = $birthday[2];
        }
        return $data;
    }

    public function _after_insert($id) {
        $img = $this->_post('img','trim');
        $this->user_thumb($id,$img);
    }

    public function _before_update($data) {
        if( ($data['password']!='')&&(trim($data['password'])!='') ){
            $data['password'] = md5($data['password']);
        }else{
            unset($data['password']);
        }
        $birthday = $this->_post('birthday', 'trim');
        if ($birthday) {
            $birthday = explode('-', $birthday);
            $data['byear'] = $birthday[0];
            $data['bmonth'] = $birthday[1];
            $data['bday'] = $birthday[2];
        }
        return $data;
    }

    public function _after_update($id){
        $img = $this->_post('img','trim');
        if($img){
            $this->user_thumb($id,$img);
        }
    }

    public function user_thumb($id,$img){
        $img_path= avatar_dir($id);
        $avatar_size = explode(',', C('ftx_avatar_size'));
        $paths =C('ftx_attach_path');
        foreach ($avatar_size as $size) {
            if($paths.'avatar/'.$img_path.'/' . md5($id).'_'.$size.'.jpg'){
                @unlink($paths.'avatar/'.$img_path.'/' . md5($id).'_'.$size.'.jpg');
            }
            !is_dir($paths.'avatar/'.$img_path) && mkdir($paths.'avatar/'.$img_path, 0777, true);
            Image::thumb($paths.'avatar/temp/'.$img, $paths.'avatar/'.$img_path.'/' . md5($id).'_'.$size.'.jpg', '', $size, $size, true);
        }

        @unlink($paths.'avatar/temp/'.$img);
    }


    public function ajax_upload_imgs() {
        if (!empty($_FILES['img']['name'])) {
            $result = $this->_upload($_FILES['img'], 'avatar/temp/' );
            if ($result['error']) {
                $this->error($result['info']);
            }else {
                $data['img'] =  $result['info'][0]['savename'];
                $this->ajaxReturn(1, L('operation_success'), $data['img']);
            }


        } else {
            $this->ajaxReturn(0, L('illegal_parameters'));
        }
    }

	public function add_score(){
		if(IS_POST){
			$id		= $this->_post('id', 'intval');
			$jinbi	= $this->_post('jinbi', 'intval');
			$action = $this->_post('action', 'trim');
			$user = $this->_mod->where(array('id'=>$id))->find();
			$score_data = array('score'=>array('exp','`score`+'.$jinbi));
			$this->_mod->where(array('id'=>$id))->setField($score_data); 
			$score_log_data['uid']		=	$id;
			$score_log_data['uname']	=	$user['username'];
			$score_log_data['action']	=	$action;
			$score_log_data['score']	=	$jinbi;
			$this->_score_log_mod->create($score_log_data);
			$this->_score_log_mod->add();
			$this->ajaxReturn(1, '赠送成功！', 'success','add_score');
		}else{
			$id = $this->_get('id', 'intval');
			$info = $this->_mod->where(array('id'=>$id))->find();
			$this->assign('info',$info);

			$resp = $this->fetch('add_score');
			$this->ajaxReturn(1, '', $resp);
		}
 
	}

    /**
     * ajax检测会员是否存在
     */
    public function ajax_check_name() {
        $name = $this->_get('username', 'trim');
        $id = $this->_get('id', 'intval');
        if ($this->_mod->name_exists($name,  $id)) {
            $this->ajaxReturn(0, '该会员已经存在');
        } else {
            $this->ajaxReturn();
        }
    }

    /**
     * ajax检测邮箱是否存在
     */
    public function ajax_check_email() {
        $name = $this->_get('email', 'trim');
        $id = $this->_get('id', 'intval');
        if ($this->_mod->email_exists($name,  $id)) {
            $this->ajaxReturn(0, '该邮箱已经存在');
        } else {
            $this->ajaxReturn();
        }
    }

}