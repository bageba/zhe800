<?php

class ajaxAction extends FirstendAction {

    public function _initialize() {
        parent::_initialize();
		$this->_mod = D('items');
        //访问者控制
        if (!$this->visitor->is_login) {
            IS_AJAX && $this->ajaxReturn(0, L('login_please'));
            $this->redirect('user/login');
        }
    }

    public function index() {
		if(IS_POST){
			$check = I('check','', 'trim');
			$bb_url = I('bb_url','', 'trim');
			$msg=array();
			$msg['msg']='请输入宝贝地址！';
			if($check=='wangwangcheck'){
				if($bb_url==''){
					$msg['msg']='请输入宝贝地址！';
				}else{
					$iid=$this->get_id($bb_url);
					if($iid){
						$item = $this->_mod->where(array('key_id' => 'taobao_'.$iid))->find();
						if($item){
							if($item['pass']){
								$msg['msg']='该商品已经通过审核！感谢您的支持！';
							}else{
								$msg['msg']='该商品正在审核中，请耐心等待！';
							}
						}else{
							$msg['msg']='该商品还没有报名哦！';
						}
					}else{
						$msg['msg']='请确认宝贝地址！';
					}
					$msg['bb_url']=$bb_url;
				}
			}
		}
		$this->assign('msg', $msg);
        $this->display();
    }


	//验证码
	public function captcha() {
        Image::buildImageVerify(4, 1, 'gif', '50', '24', 'captcha');
    }

	//查询弹窗
    public function check_item() {
        $resp = $this->fetch('dialog:chaxun');
        $this->ajaxReturn(1, '', $resp);
    }

	//举报
	public function report() {
		$id = I('znid');
		$item = $this->_mod->where(array(id=>$id))->find();
		$this->assign('item',$item);
        $resp = $this->fetch('dialog:report');
        $this->ajaxReturn(1, '', $resp);
    }

	//获取商品信息
    public function fetch_item() {
        $url = I('url','', 'trim');
        $url == '' && $this->ajaxReturn(0, L('please_input') . L('correct_itemurl'));
		!$this->get_id($url) && $this->ajaxReturn(0, L('please_input') . L('correct_itemurl'));
		$iid=$this->get_id($url);
		if($iid){
			$item = $this->_mod->where(array('num_iid' => $iid))->find();
			if($item){
				if($item['pass']){
					$msg['msg']='该商品已经通过审核！感谢您的支持！';
				}else{
					$msg['msg']='该商品正在审核中，请耐心等待！';
				}
			}else{
				$msg['msg']='该商品还没有报名哦！';
			}
			$this->assign('item', $item);
		}else{
			$msg['msg']='请确认宝贝地址！';
		}
        $this->assign('msg', $msg['msg']);
        $data = array();
        $data['html'] = $this->fetch('dialog:chaxun_result');
        $this->ajaxReturn(1, L('fetch_item_success'), $data);
    }

    //获取iid
	public function get_id($url) {
        $id = 0;
        $parse = parse_url($url);
        if (isset($parse['query'])) {
            parse_str($parse['query'], $params);
            if (isset($params['id'])) {
                $id = $params['id'];
            } elseif (isset($params['item_id'])) {
                $id = $params['item_id'];
            } elseif (isset($params['default_item_id'])) {
                $id = $params['default_item_id'];
            }
        }
        return $id;
    }

	public function like(){
		$like_mod = M('items_like');
    	$id = I('pid');
    	$uid = $this->visitor->info['id'];
    	$data['item_id'] = $id;
    	$data['uid'] = $uid;
    	if($like_mod->where($data)->select()){
    		//$this->ajaxReturn(2,'已喜欢！');
			$result = $like_mod->where($data)->delete();
			if($result){
				$like_data = array('likes'=>array('exp','likes-1'));
				$this->_mod->where(array('id'=>$id))->setField($like_data);
				$this->ajaxReturn(1, '取消喜欢成功！');
			}else{
				$this->ajaxReturn(0, $like_mod->getError());
			}

    	}
    	if (false === $like_mod->create($data)) {
    		$this->ajaxReturn(0, $like_mod->getError());
      }
      $lid = $like_mod->add();
    	if($lid){
			$like_data = array('likes'=>array('exp','likes+1'));
			$this->_mod->where(array('id'=>$id))->setField($like_data);
    		$this->ajaxReturn(1, '登录喜欢成功！');
    	}else{ 	
    		$this->ajaxReturn(0,'登录喜欢失败，请稍后重试！');
    	}
	}
   
}