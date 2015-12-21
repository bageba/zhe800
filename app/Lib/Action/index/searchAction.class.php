<?php
class searchAction extends FirstendAction {
	public function _initialize() {
        parent::_initialize();
        $this->_mod = D('items');
        $this->_cate_mod = D('items_cate');
    } 

	public function _empty(){
		$this->index();
	}
	public function etao(){
		$k		= I('key');
		header("Location: http://s.etao.com/search?q=".$k);
		exit();

	}

	public function index() {
		$sort	= I('sort', 'new', 'trim'); //排序
		$status = I('status', 'all', 'trim'); //排序
		$cid	= I('cid','','intval');
		$k		= I('key');
		$order	= 'ordid asc ,id desc';
		switch ($sort) {
            case 'new':
                $order.= ', coupon_start_time DESC';
                break;
            case 'price':
                $order.= ', price DESC';
                break;
        }
		switch ($status) {
            case 'all':
                $where['status']="underway";
                break;
            case 'underway':
                $where['status']="underway";
                break;
			case 'sellout':
				$where['status']="sellout";
				break;
        }
		if($k){
			$where['title'] = array('like', '%' . $k . '%');
			$this->assign('k',$k);
		}


		$today_str = mktime(0,0,0,date("m"),date("d"),date("Y"));
		$tomorr_str = mktime(0,0,0,date("m"),date("d")+1,date("Y"));
		$today_wh['coupon_start_time'] = array(array('egt',$today_str),array('elt',$tomorr_str)) ;
		$today_wh['pass'] = '1';
		$today_item = $this->_mod->where($today_wh)->count();
		$this->assign('today_item', $today_item);


		if ($cid) {
            $id_arr = $this->_cate_mod->get_child_ids($cid, true);
            $map['cate_id'] = array('IN', $id_arr);
            $spid = $this->_cate_mod->where(array('id'=>$cate_id))->getField('spid');
            if( $spid==0 ){
                $spid = $cid;
            }else{
                $spid .= $cid;
            }
			$this->assign('cid',$cid);
        }
		$where['pass'] = '1';
		$index_info['sort']=$sort;
		$index_info['status']=$status;
		$page_size = C('ftx_index_page_size');
        $p = I('p',1, 'intval'); //页码
		$index_info['p']=$p;

        $start = $page_size * ($p - 1) ;

		if (false === $cate_list = F('cate_list')) {
            $cate_list = D('items_cate')->cate_cache();
        }
		$this->assign('cate_list', $cate_list); //分类


        $item_mod = M('items');
        $items_list = $item_mod->where($where)->order($order)->limit($start . ',' . $page_size)->select();
		$items = array();
		$pagecount = 0;
		foreach($items_list as $key=>$val){
			$items[$key]			= $val;
			$items[$key]['class']	= $this->_mod->status($val['status'],$val['coupon_start_time'],$val['coupon_end_time']);
			$items[$key]['zk']		= round(($val['coupon_price']/$val['price'])*10, 1); 
			if(!$val['click_url']){
				$items[$key]['click_url']	=U('jump/index',array('id'=>$val['id']));
			}
			if($val['coupon_start_time']>time()){
				$items[$key]['click_url']	=U('item/index',array('id'=>$val['id']));
				$items[$key]['timeleft'] = $val['coupon_start_time']-time();
			}else{
				$items[$key]['timeleft'] = $val['coupon_end_time']-time();
			}
			$items[$key]['cate_name']		=$cate_list['p'][$val['cate_id']]['name'];
			$url = C('ftx_site_url').U('item/index',array('id'=>$val['id']));
			$items[$key]['url'] = urlencode($url);
			$items[$key]['urltitle'] = urlencode($val['title']);
			$items[$key]['price'] = number_format($val['price'],1);
			$items[$key]['coupon_price'] = number_format($val['coupon_price'],1);
			$pagecount++;
		}
		$this->assign('pagecount', $pagecount);
		F('items_list', $items);
		$this->assign('items_list', $items);
		$this->assign('index_info',$index_info);
		$count = $item_mod->where($where)->count();
		$pager = $this->_pager($count, $page_size);
		$this->assign('page', $pager->kshow());
		$this->assign('total_item',$count);

		$item_comment_mod = M('items_comment');
		$comment_list = $item_comment_mod->order('id DESC')->limit('0,10')->select();
		$this->assign('comment_list', $comment_list);
		$this->assign('nav_curr', 'index');
		$page_seo=array(
			'title' => '搜索"'.$k.'"的宝贝结果页 - '.C('ftx_site_name'),
		);
		$this->assign('page_seo', $page_seo);
		$this->assign('pager','so');
		$this->display('index');
    }
 
}