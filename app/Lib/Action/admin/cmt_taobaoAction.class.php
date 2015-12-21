<?php
class cmt_taobaoAction extends BackendAction {

    public function index() {
		$big_menu = array(
            'title' => '单件宝贝采集',
            'iframe' => U('cmt_taobao/item'),
            'id' => 'item',
            'width' => '550',
            'height' => '40'
        );
        $this->assign('big_menu', $big_menu);
    	$this->display();
    }

	public function item() {
			$resp = $this->fetch('item');
			$this->ajaxReturn(1, '', $resp);
			$this->display();
    }

	public function item_add(){
		if(IS_POST){
			$url		= $this->_post('url', 'trim');
			!$this->get_id($url) && $this->error(L('please_input') . L('correct_itemurl'),U('cmt_taobao/index'));
			$iid=$this->get_id($url);
			$items = M('items')->where(array('num_iid' => $iid))->find();
			!$items && $this->error('该商品未采集，请添加后采集评论！，谢谢',U('cmt_taobao/index'));
			$item_mod = M('items');

			$id = $items['id'];
			$rate_list = $this->_collect_ones($iid , $id);
			//exit(print_r($items));
			F('cmt_taobao_item', array(
						'iid' => $iid,
						'id' => $id,
			));
            $item_mod->where(array('id'=>$id))->save(array('last_rate_time'=>time(),'is_collect_comments'=>'1'));

			
			
			if($rate_list['rateList']){
				$rates = $rate_list['rateList'];
				$pager = new Page($rate_list['paginator']['items'], 20);
			}else{
				$rates = $rate_list['comments'];
				$pager = new Page($rate_list['maxPage']*20, 20);
			}
			$page = $pager->show();
			$this->assign("page", $page);
			$this->assign('rate_list', $rates);
			$this->display();

		}else{
			$cmt_taobao_item=F('cmt_taobao_item');
			$id		= $cmt_taobao_item['id'];
			$iid	= $cmt_taobao_item['iid'];
			$p		= $this->_get('p',   'trim');
			$rate_list = $this->_collect_ones($iid , $id ,$p);
			
			if($rate_list['rateList']){
				$rates = $rate_list['rateList'];
				$pager = new Page($rate_list['paginator']['items'], 20);
			}else{
				$rates = $rate_list['comments'];
				$pager = new Page($rate_list['maxPage']*20, 20);
			}
			$page = $pager->show();
			$this->assign("page", $page);
			$this->assign('rate_list', $rates);
			$this->display();

		}
	}

    /**
     * 准备采集
     */
    public function setting() {
        $cate_id	= $this->_post('cate_id', 'intval'); //分类
        $orders		= $this->_post('orders', 'trim'); //优先级
		$collect	= $this->_post('collect', 'trim'); //优先级
        $pagesize	= '1';

        $rate_type = $this->_post('rate_type', 'intval'); //淘宝评论类型
        $sort_type = $this->_post('sort_type', 'trim'); //淘宝评论排序
        if($cate_id){
			$id_arr = D('items_cate')->get_child_ids($cate_id, true);
			$map['cate_id'] = array('IN', $id_arr);
		}
        $map['pass'] = 1;

		if($collect){
			$map['is_collect_comments']	=	0;
		}
		//exit(print_r($map));
        $count = M('items')->where($map)->count('id'); //商品总数
        $page_total = ceil($count/$pagesize); //总页数

        //把采集信息写入缓存
        F('cmt_taobao_setting', array(
            'cate_id' => $cate_id,
            'map' => $map,
            'order' => $orders.' DESC',
            'count' => $count,
            'pagesize' => $pagesize,
            'page_total' => $page_total,
            'rate_type' => $rate_type,
            'sort_type' => $sort_type,
        ));
        $this->assign('page_total', $page_total);
        $this->assign('count', $count);
        $this->assign('p', 0);
        $resp = $this->fetch('collect');
        $this->ajaxReturn(1, '评论采集', $resp); //设置完成开始采集
    }

    /**
     * 开始采集
     */
    public function collect() {
        if (false === $setting = F('cmt_taobao_setting')) {
            $this->ajaxReturn(0, L('illegal_parameters'));
        }
        $p = $this->_get('p', 'intval', 1);
        $start = ($p - 1) * $setting['pagesize'];
        $item_mod = M('items');
		//exit(print_r($setting));
        $item_list = $item_mod->field('id,num_iid')->where($setting['map'])->order($setting['order'])->limit($start.','.$setting['pagesize'])->select();
        foreach ($item_list as $val) {
            $iid = $val['num_iid'];
            $this->_collect_ones($iid, $val['id']);
            $item_mod->where(array('id'=>$val['id']))->save(array('last_rate_time'=>time(),'is_collect_comments'=>'1'));
        }
        $p >= $setting['page_total'] && $this->ajaxReturn(2, '采集完成！'); //采集完成
        $this->assign('page_total', $setting['page_total']);
        $this->assign('count', $setting['count']);
        $this->assign('p', $p);
        $resp = $this->fetch('collect');
        $this->ajaxReturn(1, '', $resp);
    }

    /**
     * 采集单元
     */
    private function _collect_one($iid, $item_id) {
        $seller = $this->_get_seller_id($iid);
        if (!$seller['id']) return false;
        $item_mod = M('items');
        $item_comment_mod = D('items_comment');
		$time;
		
        if ($seller['type'] == 'tmall') {
            $rate_tmall_api = 'http://rate.tmall.com/list_detail_rate.htm?itemId='.$iid.'&spuId=&sellerId='.$seller_id.'&order=0&forShop=1&append=0&currentPage=1';
            $source = Http::fsockopenDownload($rate_tmall_api);
            $source = rtrim(ltrim(trim($source), '('), ')');
            $source = iconv('GBK', 'UTF-8//IGNORE', $source);
            $source = str_replace('"rateDetail":', '', $source);
            $rate_resp = json_decode($source, true);
            $rate_list = $rate_resp['rateList'];
			//exit(print_r($rate_list));
			$cmt_num=count($rate_list);
            for ($i = 0; $i < $cmt_num; $i++) {
                $time = strtotime($rate_list[$i]['rateDate']);
                $last_rate_time = $item_mod->where(array('id'=>$item['id']))->getField('last_rate_time');
                if ($time <= $last_rate_time) {
                    return false;
                }
				$item_cache = F('item_cache');
				if(!strpos($rate_list[$i]['displayUserNick'],'*') && $item_cache['info'] !=$rate_list[$i]['rateContent']){

					if (false === $data = $item_comment_mod->create(array(
						'item_id' => $item_id,
						'rateid' => $rate_list[$i]['id'],
						'uid' => $rate_list[$i]['id'],
						'uname' => $rate_list[$i]['displayUserNick'],
						'info' => $rate_list[$i]['rateContent'],
						'add_time' => $time,
					))) {
						$this->error($item_comment_mod->getError());
					}
					if (!$item_comment_mod->where(array('rateid'=>$rate_list[$i]['id']))->count()) {
						$item_comment_mod->add();
					} 

					F('item_cache', array(
						'item_id' => $item_id,
						'rateid' => $rate_list[$i]['id'],
						'uid' => $rate_list[$i]['id'],
						'uname' => $rate_list[$i]['displayUserNick'],
						'info' => $rate_list[$i]['rateContent'],
						'add_time' => $time,
					));
				}
            }
        } else {
            $rate_taobao_api = 'http://rate.taobao.com/feedRateList.htm?userNumId='.$seller['id'].'&auctionNumId='.$iid.'&currentPageNum=1';
            $source = Http::fsockopenDownload($rate_taobao_api);
            $source = rtrim(ltrim(trim($source), '('), ')');
            $source = iconv('GBK', 'UTF-8//IGNORE', $source);
            $rate_resp = json_decode($source, true);
            $rate_list = $rate_resp['comments'];
			$cmt_num=count($rate_list);
            for ($i = 0; $i < $cmt_num; $i++) {
				//exit(print_r($rate_list));
				if(strpos($rate_list[$i]['date'],'.')){
					$date = explode('.', $rate_list[$i]['date']);
					$time = mktime(0,0,0,$date[1],$date[2],$date[0]);
				}else{
					$date=str_replace("年","-",$rate_list[$i]['date']);
					$date=str_replace("月","-",$date);
					$date=str_replace("日","",$date); 
					$time = strtotime(date($date));
				}
                $last_rate_time = $item_mod->where(array('id'=>$item['id']))->getField('last_rate_time');
                if ($time <= $last_rate_time) {
                    return false;
                }
				$item_cache = F('item_cache');

				if(!strpos($rate_list[$i]['user']['nick'],'*') && $item_cache['info'] !=$rate_list[$i]['content']){

					if (false === $data = $item_comment_mod->create(array(
						'item_id' => $item_id,
						'rateid' => $rate_list[$i]['rateId'],
						'uid' => $rate_list[$i]['user']['userId'],
						'uname' => $rate_list[$i]['user']['nick'],
						'info' => $rate_list[$i]['content'],
						'add_time' => $time,
					))) {
						$this->error($item_comment_mod->getError());
					}
					if (!$item_comment_mod->where(array('rateid'=>$rate_list[$i]['rateId']))->count()) {
						$item_comment_mod->add();
					} 

					F('item_cache', array(
						'item_id' => $item_id,
						'rateid' => $rate_list[$i]['rateId'],
						'uid' => $rate_list[$i]['user']['userId'],
						'uname' => $rate_list[$i]['user']['nick'],
						'info' => $rate_list[$i]['content'],
						'add_time' => $time,
					));
				}
            }
        }
    }






	/**
     * 采集单元
     */
    private function _collect_ones($iid, $item_id,$p =1) {
        $seller = $this->_get_seller_id($iid);
        if (!$seller['id']) return false;
        $item_mod = M('items');
        $item_comment_mod = D('items_comment');
		$time;
		//exit(print_r($seller));
		
        if ($seller['type'] == 'tmall') {
            $rate_tmall_api = 'http://rate.tmall.com/list_detail_rate.htm?itemId='.$iid.'&spuId=&sellerId='.$seller_id.'&order=0&forShop=1&append=0&currentPage='.$p;
            $source = Http::fsockopenDownload($rate_tmall_api);
			if(!$source){
				$source=file_get_contents($rate_tmall_api);
			}
            $source = rtrim(ltrim(trim($source), '('), ')');
            $source = iconv('GBK', 'UTF-8//IGNORE', $source);
            $source = str_replace('"rateDetail":', '', $source);
            $rate_resp = json_decode($source, true);
            $rate_list = $rate_resp['rateList'];
			//exit(print_r($rate_list));
			$cmt_num=count($rate_list);
            for ($i = 0; $i < $cmt_num; $i++) {
                $time = strtotime($rate_list[$i]['rateDate']);
                $last_rate_time = $item_mod->where(array('id'=>$item['id']))->getField('last_rate_time');
                if ($time <= $last_rate_time) {
                    return false;
                }
				$item_cache = F('item_cache');
				if(!strpos($rate_list[$i]['displayUserNick'],'*') && $item_cache['info'] !=$rate_list[$i]['rateContent']){

					if (false === $data = $item_comment_mod->create(array(
						'item_id' => $item_id,
						'rateid' => $rate_list[$i]['id'],
						'uid' => $rate_list[$i]['id'],
						'uname' => $rate_list[$i]['displayUserNick'],
						'info' => $rate_list[$i]['rateContent'],
						'add_time' => $time,
					))) {
						$this->error($item_comment_mod->getError());
					}
					if (!$item_comment_mod->where(array('rateid'=>$rate_list[$i]['id']))->count()) {
						$item_comment_mod->add();
					} 

					F('item_cache', array(
						'item_id' => $item_id,
						'rateid' => $rate_list[$i]['id'],
						'uid' => $rate_list[$i]['id'],
						'uname' => $rate_list[$i]['displayUserNick'],
						'info' => $rate_list[$i]['rateContent'],
						'add_time' => $time,
					));
				}
            }
			return $rate_resp;
        } else {
            $rate_taobao_api = 'http://rate.taobao.com/feedRateList.htm?userNumId='.$seller['id'].'&auctionNumId='.$iid.'&currentPageNum='.$p;
            $source = Http::fsockopenDownload($rate_taobao_api);
			if(!$source){
				$source=file_get_contents($rate_taobao_api);
			}
            $source = rtrim(ltrim(trim($source), '('), ')');
            $source = iconv('GBK', 'UTF-8//IGNORE', $source);
            $rate_resp = json_decode($source, true);
            $rate_list = $rate_resp['comments'];
			$cmt_num=count($rate_list);
            for ($i = 0; $i < $cmt_num; $i++) {
				if(strpos($rate_list[$i]['date'],'.')){
					$date = explode('.', $rate_list[$i]['date']);
					$time = mktime(0,0,0,$date[1],$date[2],$date[0]);
				}else{
					$date=str_replace("年","-",$rate_list[$i]['date']);
					$date=str_replace("月","-",$date);
					$date=str_replace("日","",$date); 
					$time = strtotime(date($date));
				}
                $last_rate_time = $item_mod->where(array('id'=>$item['id']))->getField('last_rate_time');
                if ($time <= $last_rate_time) {
                    return false;
                }
				$item_cache = F('item_cache');

				if(!strpos($rate_list[$i]['user']['nick'],'*') && $item_cache['info'] !=$rate_list[$i]['content']){

					if (false === $data = $item_comment_mod->create(array(
						'item_id' => $item_id,
						'rateid' => $rate_list[$i]['rateId'],
						'uid' => $rate_list[$i]['user']['userId'],
						'uname' => $rate_list[$i]['user']['nick'],
						'info' => $rate_list[$i]['content'],
						'add_time' => $time,
					))) {
						$this->error($item_comment_mod->getError());
					}
					if (!$item_comment_mod->where(array('rateid'=>$rate_list[$i]['rateId']))->count()) {
						$item_comment_mod->add();
					} 

					F('item_cache', array(
						'item_id' => $item_id,
						'rateid' => $rate_list[$i]['rateId'],
						'uid' => $rate_list[$i]['user']['userId'],
						'uname' => $rate_list[$i]['user']['nick'],
						'info' => $rate_list[$i]['content'],
						'add_time' => $time,
					));
				}
            }
			return $rate_resp;
        }
    }


    /**
     * 获取商品卖家ID
     */
    private function _get_seller_id($iid) {
        $result = array('type'=>'taobao', 'id'=>0);
        $page_content = Http::fsockopenDownload('http://item.taobao.com/item.htm?id='.$iid);
        if (!$page_content) {
            //$page_content = Http::fsockopenDownload('http://detail.tmall.com/item.htm?id='.$iid);
            $page_content = file_get_contents('http://detail.tmall.com/item.htm?id='.$iid);
            $result['type'] = 'tmall';
        }
        preg_match('|; userid=(\d+);|', $page_content, $out);
        $result['id'] = $out[1];
        return $result;
    }

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
}