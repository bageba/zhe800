<?php

class giftAction extends FirstendAction {

    public function _initialize() {
        parent::_initialize();
        $this->assign('nav_curr', 'gift');
    }

    /**
     * 积分兑换首页
     */
    public function index() {
        $cid = I('cid','', 'intval');
        $sort = I('sort', 'hot', 'trim');
        switch ($sort) {
            case 'hot':
                $sort_order = 'ordid ASC ,buy_num DESC,id DESC';
                break;
            case 'new':
                $sort_order = 'ordid ASC ,id DESC';
                break;
        }

		if (false === $cate_list = F('score_item_cate_list')) {
            $cate_list = D('score_item_cate')->cate_cache();
        }
		$this->assign('cate_list', $cate_list);
        $cname = D('score_item_cate')->get_name($cid);
        $where = array('status'=>'1');
        $cid && $where['cate_id'] = $cid;

        $score_item = M('score_item');
        $count = $score_item->where($where)->count('id');
        $pager = $this->_pager($count, 20);
        $item_list = $score_item->where($where)->order($sort_order)->limit($pager->firstRow.','.$pager->listRows)->select();
        $this->assign('item_list', $item_list);
        $this->assign('page_bar', $pager->fshow());
        $this->assign('cid', $cid);
        $this->assign('sort', $sort);
        $this->assign('cname', $cname);
        $this->_config_seo(C('ftx_seo_config.gift'), array(
            'cate_name' => $cname,
        ));
        $this->display();
    }

    /**
     * 积分商品详细页
     */
    public function detail() {
        $id = I('id','', 'intval');
        !$id && $this->_404();
        $item_mod = M('score_item');
        $item = $item_mod->field('id,title,img,score,stock,user_num,price,coupon_price,num_iid,start_time,end_time,buy_num,desc')->find($id);
        $this->assign('item', $item);
        $this->_config_seo(C('ftx_seo_config.gift_item'), array(
            'title' => $item['title'],
        ));
		if (false === $cate_list = F('score_item_cate_list')) {
            $cate_list = D('score_item_cate')->cate_cache();
        }
		$this->assign('cate_list', $cate_list);
        $cname = D('score_item_cate')->get_name($cid);
        $where = array('status'=>'1');
        $cid && $where['cate_id'] = $cid;
        $this->display();
    }

    /**
     * 兑换
     */
    public function ec() {
        !$this->visitor->is_login && $this->ajaxReturn(0, L('login_please'));
        $id = I('id','', 'intval');
        $num = I('num',1, 'intval');
        if (!$id || !$num) $this->ajaxReturn(0, L('invalid_item'));
        $item_mod = M('score_item');
        $user_mod = M('user');
        $order_mod = D('score_order');
        $uid = $this->visitor->info['id'];
        $uname = $this->visitor->info['username'];
        $item = $item_mod->find($id);
        !$item && $this->ajaxReturn(0, L('invalid_item'));
        !$item['stock'] && $this->ajaxReturn(0, L('no_stock'));
		//时间判断
		if($item['start_time'] > time()){
			$this->ajaxReturn(0, L('no_start'));
		}
		if($item['end_time'] < time()){
			$this->ajaxReturn(0, L('ending'));
		}
        //积分够不？
        $user_score = $user_mod->where(array('id'=>$uid))->getField('score');
        if($user_score < $item['score']){
			$this->ajaxReturn(0, L('no_score'));
		}
        //限额
        $eced_num = $order_mod->where(array('uid'=>$uid, 'item_id'=>$item['id']))->sum('item_num');
        if ($item['user_num'] && $eced_num + $num > $item['user_num']) {
            $this->ajaxReturn(0, sprintf(L('ec_user_maxnum'), $item['user_num']));
        }
        $this->assign('id', $item['id']);
        $resp = $this->fetch('dialog:daifu');
        $this->ajaxReturn(2, L('dialog_daifu'), $resp);

		/*
        $order_score = $num * $item['score'];
        $data = array(
            'uid' => $uid,
            'uname' => $uname,
            'item_id' => $item['id'],
            'item_name' => $item['title'],
            'item_num' => $num,
            'order_score' => $order_score,
        );
        if (false === $order_mod->create($data)) {
            $this->ajaxReturn(0, L('ec_failed'));
        }
        $order_id = $order_mod->add();
        //扣除用户积分并记录日志
        $user_mod->where(array('id'=>$uid))->setDec('score', $order_score);
        $score_log_mod = D('score_log');
        $score_log_mod->create(array(
            'uid' => $uid,
            'uname' => $uname,
            'action' => 'gift',
            'score' => $order_score*-1,
        ));
        $score_log_mod->add();

        //减少库存和增加兑换数量
        $item_mod->save(array(
            'id' => $item['id'],
            'stock' => $item['stock'] - $num,
            'buy_num' => $item['buy_num'] + $num,
        ));
        //返回

            //如果是实物则弹窗询问收货地址
            $address_list = M('user_address')->field('id,consignee,address,zip,mobile')->where(array('uid'=>$uid))->select();
            $this->assign('address_list', $address_list);
            $this->assign('order_id', $order_id);
            $resp = $this->fetch('dialog:address');
            $this->ajaxReturn(2, L('please_input_address'), $resp);

			*/

    }
	/**
	 * 代付链接
	 */
	public function daifu() {
		!$this->visitor->is_login && $this->ajaxReturn(0, L('login_please'));
		$id = I('id','', 'intval');
        $url = I('url','', 'trim');
		$item_mod = M('score_item');
        $user_mod = M('user');
        $order_mod = D('score_order');
        $uid = $this->visitor->info['id'];
        $uname = $this->visitor->info['username'];
		if (!$url) {
            $this->ajaxReturn(0, L('daifu_message'));
        }
		$item = $item_mod->find($id);
        !$item && $this->ajaxReturn(0, L('invalid_item'));

		$order_score =  $item['score'];
        $data = array(
            'uid' => $uid,
            'uname' => $uname,
            'item_id' => $item['id'],
            'item_name' => $item['title'],
			'item_num' => '1',
            'url' => $url,
            'order_score' => $order_score,
        );
        if (false === $order_mod->create($data)) {
            $this->ajaxReturn(0, L('ec_failed'));
        }
        $order_id = $order_mod->add();
        //扣除用户积分并记录日志
        $user_mod->where(array('id'=>$uid))->setDec('score', $order_score);
        $score_log_mod = D('score_log');
        $score_log_mod->create(array(
            'uid' => $uid,
            'uname' => $uname,
            'action' => 'gift',
            'score' => $order_score*-1,
        ));
        $score_log_mod->add();
        //减少库存和增加兑换数量
        $item_mod->save(array(
            'id' => $item['id'],
            'stock' => $item['stock'] - 1,
            'buy_num' => $item['buy_num'] + 1,
        ));
		$this->ajaxReturn(1, L('ec_success'));
	}

    /**
     * 收货地址
     */
    public function address() {
        !$this->visitor->is_login && $this->ajaxReturn(0, L('login_please'));
        $order_id	= I('order_id','', 'intval');
        $address_id = I('address_id','', 'intval');
        $consignee	= I('consignee','', 'trim');
        $address	= I('address','', 'trim');
        $zip		= I('zip','','trim');
        $mobile		= I('mobile','', 'trim');
        if (!$address_id && (!$order_id || !$consignee || !$address || !$mobile)) {
            $this->ajaxReturn(0, L('please_input_address_info'));
        }
        $order_mod = M('score_order');
        if (!$order_mod->where(array('uid'=>$this->visitor->info['id'], 'id'=>$order_id))->count('id')) {
            $this->ajaxReturn(0, L('order_not_foryou'));
        }
        $user_address_mod = M('user_address');
        if ($address_id) {
            $address = $user_address_mod->field('consignee,address,zip,mobile')->find($address_id);
        } else {
            $address = array(
                'uid' => $this->visitor->info['id'],
                'consignee' => $consignee,
                'address' => $address,
                'zip' => $zip,
                'mobile' => $mobile,
            );
            //添加收货地址
            $user_address_mod->add($address);
        }
        $result = $order_mod->save(array(
            'id' => $order_id,
            'consignee' => $address['consignee'],
            'address' => $address['address'],
            'zip' => $address['zip'],
            'mobile' => $address['mobile'],
        ));
        $this->ajaxReturn(1, L('ec_success'));
    }

    /**
     * 积分规则
     */
    public function rule() {
        $info = M('article_page')->find(6);
        $this->assign('info', $info);
        $this->_config_seo();
        $this->display();
    }
}