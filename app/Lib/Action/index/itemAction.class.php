<?php

class itemAction extends FirstendAction {

    public function _initialize() {
        parent::_initialize();
		$this->_mod = D('items');
        $this->_cate_mod = D('items_cate');
		$this->assign('nav_curr', 'index');
        //访问者控制
        if (!$this->visitor->is_login && in_array(ACTION_NAME, array('delete', 'comment','delcol','mycol'))) {
            IS_AJAX && $this->ajaxReturn(0, L('login_please'));
            $this->redirect('user/login');
        }
		
    }

    /**
     * 商品详细页
     */
    public function index() {
        $id = I('id', '','trim');
        !$id && $this->_404();
		$item = $this->_mod->where(array('id' => $id))->find();
		//!$item && $this->_404();
		$item['class'] = $this->_mod->status($item['status'],$item['coupon_start_time'],$item['coupon_end_time']);
        $item['zk']    = round(($item['coupon_price']/$item['price'])*10, 2); 
		$this->assign('item', $item);


		$hits_data = array('hits'=>array('exp','hits+1'));
		$this->_mod->where(array('id'=>$id))->setField($hits_data);



		$tag_list = D('items')->get_tags_by_title($item['title']);
		$tags = implode(',', $tag_list);

		$this->_config_seo(C('ftx_seo_config.item'), array(
            'title' => $item['title'],
            'intro' => $item['intro'],
			'price' => $item['price'],
			'coupon_price' => $item['coupon_price'],
			'tags' => $tags,
            'seo_title' => $item['seo_title'],
            'seo_keywords' => $item['seo_keys'],
            'seo_description' => $item['seo_desc'],
        ));

		$item_comment_mod = M('items_comment');
        $pagesize = 8;
		$pager->listRows = 8;
        $map = array('item_id' => $id,'status' => '1');
        $count = $item_comment_mod->where($map)->count('id');
        $pager = $this->_pager($count, $pagesize);
        $pager->path = 'comment_list';
        $pager_bar = $pager->fshow();
        $comment_list = $item_comment_mod->where($map)->order('add_time DESC')->limit($pager->firstRow . ',' . $pager->listRows)->select();
		$this->assign('comment_list', $comment_list);
		$this->assign('page_bar', $pager_bar);
		foreach($comment_list as $com){
			foreach($tag_list as $nkeys){
			   if(strpos($com['info'],$nkeys) ){ 
				   $url = U('tag/'.$nkeys);
					 $com['info'] =str_replace($nkeys,"<a href=".$url." target=_blank ><b>".$nkeys."</b></a>",$com['info']);      
			   } 
			} 
			$comm[] = $com;
		}

		$this->assign('tags', $tag_list);
		$this->assign('comment_list', $comm);
		$this->assign('page_bar', $pager_bar);
		if(false === $cate_list = F('cate_list')) {
			$cate_list = D('items_cate')->cate_cache();
		}
		$this->assign('cate_list', $cate_list); //分类
		$this->display();
    }


    /**
     * AJAX获取评论列表
     */
    public function comment_list() {
        $id = I('id','', 'intval');
        !$id && $this->ajaxReturn(0, L('invalid_item'));

        $item = $this->_mod->where(array('id' => $id, 'pass' => '1'))->count('id');
        !$item && $this->ajaxReturn(0, L('invalid_item'));
        $item_comment_mod = M('items_comment');
        $pagesize = 8;
        $map = array('item_id' => $id,'status' => '1');
        $count = $item_comment_mod->where($map)->count('id');
        $pager = $this->_pager($count, $pagesize);
		$pager->listRows = 8;
        $cmt_list = $item_comment_mod->where($map)->order('add_time DESC')->limit($pager->firstRow . ',' . $pager->listRows)->select();
        $this->assign('cmt_list', $cmt_list);
        $data = array();
        $data['list'] = $this->fetch('comment_list');
        $data['page'] = $pager->fshow();
        $this->ajaxReturn(1, '', $data);
    }

    /**
     * 评论一个商品
     */
    public function comment() {
        foreach ($_POST as $key=>$val) {
            $_POST[$key] = Input::deleteHtmlTags($val);
        }
        $data = array();
        $data['item_id'] = I('id', '','intval');
        !$data['item_id'] && $this->ajaxReturn(0, L('invalid_item'));
        $data['info'] = I('content','', 'trim');
        !$data['info'] && $this->ajaxReturn(0, L('please_input') . L('comment_content'));

		$data['status'] = 1;
        $data['uid'] = $this->visitor->info['id'];
        $data['uname'] = $this->visitor->info['username'];

        //验证商品
        $item = $this->_mod->field('id,uid,nick')->where(array('id' => $data['item_id'], 'pass' => '1'))->find();
        !$item && $this->ajaxReturn(0, L('invalid_item'));
        //写入评论
        $item_comment_mod = D('items_comment');
        if (false === $item_comment_mod->create($data)) {
            $this->ajaxReturn(0, $item_comment_mod->getError());
        }
        $comment_id = $item_comment_mod->add();
        if ($comment_id) {
            $this->assign('cmt_list', array(
                array(
                    'uid' => $data['uid'],
                    'uname' => $data['uname'],
                    'info' => $data['info'],
                    'add_time' => time(),
                )
            ));
            $resp = $this->fetch('comment_list');
            $this->ajaxReturn(1, L('comment_success'), $resp);
        } else {
            $this->ajaxReturn(0, L('comment_failed'));
        }
    }

	public function noshow(){
		$id = I('id');
		$uid = $this->visitor->info['id'];
		if($uid != 1){
			$this->ajaxReturn(0,'越权！');
		}
		$data['isshow'] = 0;
		if(M('items')->where(array('id'=>$id))->save($data)){
			$this->ajaxReturn(1, '取消成功！');
		}else{
    			$this->ajaxReturn(0, $this->_mod->getError());
    		}
	}

    /**
     * 删除商品
     */
    public function delete() {
        $id = I('id','', 'intval');
        !$id && $this->ajaxReturn(0, L('invalid_item'));
        $uid = $this->visitor->info['id'];
        $uname = $this->visitor->info['username'];
        $result = D('items')->where(array('id' => $id))->delete();
        if ($result) {
             $this->ajaxReturn(1, L('del_item_success'));
        } else {
             $this->ajaxReturn(0, L('del_item_failed'));
        }
    }


	/**
     * 获取紧接着的下一级分类ID
     */
    public function ajax_getchilds() {
        $id = I('id','', 'intval');
        $map = array('pid'=>$id,'status'=>'1');
        $return = M('items_cate')->field('id,name')->where($map)->select();
        if ($return) {
            $this->ajaxReturn(1, L('operation_success'), $return);
        } else {
            $this->ajaxReturn(0, L('operation_failure'));
        }
    }

	private function _get_ftx_top() {
        vendor('Ftxia.TopClient');
        vendor('Ftxia.RequestCheckUtil');
        vendor('Ftxia.Logger');
        $tb_top = new TopClient;
        $tb_top->appkey = $this->_ftxconfig['app_key'];
        $tb_top->secretKey = $this->_ftxconfig['app_secret'];
        return $tb_top;
    }

}