<?php
class indexAction extends FirstendAction {
	public function _initialize() {
        parent::_initialize();
        $this->_mod = D('items');
        $this->_cate_mod = D('items_cate');
    }
    /**
	 ** 首页（全部）
	 **/
    public function index() {
		$p		= I('p',1 ,'intval'); //页码
		$sort	= I('sort', 'default', 'trim'); //排序
		$status = I('status', 'all', 'trim'); //排序
 
		$today_str = mktime(0,0,0,date("m"),date("d"),date("Y"));
		$tomorr_str = mktime(0,0,0,date("m"),date("d")+1,date("Y"));
		$today_wh['coupon_start_time'] = array(array('egt',$today_str),array('elt',$tomorr_str)) ;
		$today_wh['pass'] = '1';
		$today_wh['isshow'] = '1';
		$today_item = $this->_mod->where($today_wh)->count();
		$this->assign('today_item', $today_item);

		$order = 'ordid asc';
		
		switch ($sort){
    	case 'new':
        $order.= ', coupon_start_time DESC';
        break;
      case 'price':
        $order.= ', price DESC';
        break;
      case 'rate':
        $order.= ', coupon_rate ASC';
        break;
			case 'hot':
        $order.= ', volume DESC';
        break;
			case 'default':
				$order.= ', '.C('ftx_index_sort');
    }

		switch ($status){
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

		if(C('ftx_index_not_text')){
			$not_arr = explode(",",C('ftx_index_not_text'));
			$arrs =array();
			foreach($not_arr as $key =>$value){
				$arrs[] = '%'.$value.'%';
			}
			$where['title'] =array('notlike',$arrs,'AND');
		}

		if(C('ftx_index_cids')){
			$where['cate_id'] =  array('in',C('ftx_index_cids'));

			
		}


		if(C('ftx_wait_time') == '1'){
			$where['coupon_start_time'] = array('egt',time());
		}elseif(C('ftx_wait_time') =='2'){
			$where['coupon_start_time'] = array('elt',time());
		}

		if(C('ftx_end_time') == '1'){
			$where['coupon_end_time'] = array('egt',time());
		}
		if(C('ftx_index_ems') == '1'){
			$where['ems'] = '1';
		}
		
		if(C('ftx_index_shop_type')){$where['shop_type'] = C('ftx_index_shop_type');}

		if(C('ftx_index_mix_price')>0){$where['coupon_price'] = array('egt',C('ftx_index_mix_price'));}
		if(C('ftx_index_max_price')>0){$where['coupon_price'] = array('elt',C('ftx_index_max_price'));}
		if(C('ftx_index_mix_price')>0 && C('ftx_index_max_price')>0){$where['coupon_price'] = array(array('egt',C('ftx_index_mix_price')),array('elt',C('ftx_index_max_price')),'and');}

		if(C('ftx_index_mix_volume')>0){$where['volume'] = array('egt',C('ftx_index_mix_volume'));}
		if(C('ftx_index_max_volume')>0){$where['volume'] = array('elt',C('ftx_index_max_volume'));}
		if(C('ftx_index_mix_volume')>0 && C('ftx_index_max_volume')>0){$where['volume'] = array(array('egt',C('ftx_index_mix_volume')),array('elt',C('ftx_index_max_volume')),'and');}

		$where['pass'] = '1';
		$where['isshow'] = '1';
		$index_info['sort']=$sort;
		$index_info['status']=$status;
		$page_size = C('ftx_index_page_size');
		$index_info['p']=$p;

        $start = $page_size * ($p - 1) ;

		if(false === $cate_list = F('cate_list')) {
			$cate_list = D('items_cate')->cate_cache();
		}
		$this->assign('cate_list', $cate_list); //分类
		
		if(IS_AJAX){
            $ajaxpage = I('ajaxpage','2','intval'); 
			if($ajaxpage>5){$this->ajaxReturn(0, '加载完成');}
			$page_er = 24;
			$start = $start + 24*($ajaxpage-1);
        }else{
			$page_er = 24;
        }
		$items_list = $this->_mod->where($where)->order($order)->limit($start . ',' . $page_er)->select();
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
		if(IS_AJAX){
			if(!$items){$this->ajaxReturn(0, '加载完成');}
			$this->assign('items_list', $items);
			$resp = $this->fetch('ajax');
            $this->ajaxReturn(1, '', $resp);
		}
		$tag_list = D('items')->get_tags_by_title($val['title']);
		$tags = implode(',', $tag_list);
		
			foreach($tag_list as $nkeys){
			   if(strpos($com['info'],$nkeys) ){ 
				   $url = U('tag/'.$nkeys);
					 $com['info'] =str_replace($nkeys,"<a href=".$url." target=_blank ><b>".$nkeys."</b></a>",$com['info']);      
			   } 
			} 
			

		$this->assign('tags', $tag_list);
		$this->assign('pagecount', $pagecount);
		F('items_list', $items);
		$this->assign('items_list', $items);
		$this->assign('index_info',$index_info);
		$count = $this->_mod->where($where)->count();
		$pager = $this->_pager($count, $page_size);
//        $this->assign('page', $pager->kshow());
        $this->assign('page', $pager->zshow());
        $this->assign('total_item',$count);

        $this->assign('pager','index');
		$this->assign('ajaxurl',U('index/index',array('p'=>$index_info['p'],'sort'=>$index_info['sort'])));
    	$this->assign('nav_curr', 'index');
    	$this->_config_seo(C('ftx_seo_config.index'));
		$this->display();
    }

	/**
	 ** 搜索
	 **/
	public function so() {
		$sort	= I('sort', 'new', 'trim'); //排序
		$status = I('status', 'all', 'trim'); //排序
		$cid	= I('cid','','intval');
		$k		= I('k');
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

        $items_list = $this->_mod->where($where)->order($order)->limit($start . ',' . $page_size)->select();
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
		$count = $this->_mod->where($where)->count();
		$pager = $this->_pager($count, $page_size);
//		$this->assign('page', $pager->kshow());
        $this->assign('page', $pager->zshow());
		$this->assign('total_item',$count);
 
		$this->assign('nav_curr', 'index');
			$page_seo=array(
			'title' => '搜索"'.$k.'"的宝贝结果页 - '.C('ftx_site_name'),
		);
		$this->assign('page_seo', $page_seo);
		$this->assign('pager','so');
		$this->display('index');
    }

	public function shortcut(){
		$Shortcut = "[InternetShortcut] 
		URL=".C('ftx_site_url')." 
		IDList= 
		[{000214A0-0000-0000-C000-000000000046}] 
		Prop3=19,2 
		"; 
		Header("Content-type: application/octet-stream"); 
		header("Content-Disposition: attachment; filename=".C('ftx_site_name').".url;"); 
		echo $Shortcut; 
	}

  /**
	  * 分类
	  */
	public function cate(){
		$cid	=	I('cid','', 'intval');
		$sort	=	I('sort', 'default', 'trim'); //排序
		$status =	I('status', 'all', 'trim'); //排序
		$order	=	'ordid asc ';
		$cinfo = $this->_cate_mod->where(array('id'=>$cid))->find();
		switch ($sort) {
			case 'new':
                $order.= ', coupon_start_time DESC';
                break;
			case 'price':
                $order.= ', price DESC';
                break;
			case 'hot':
                $order.= ', volume DESC';
                break;
			case 'rate':
                $order.= ', coupon_rate ASC';
                break;
			case 'default':
				$order.= ', '.$cinfo['sort'];
		}

		switch ($status) {
			case 'all':
                $map['status']="underway";
                break;
			case 'underway':
                $map['status']="underway";
                break;
			case 'sellout':
				$map['status']="sellout";
				break;
		}
		if($cinfo['shop_type']){$map['shop_type'] = $cinfo['shop_type'];}
		if($cinfo['mix_price']>0){$map['coupon_price'] = array('egt',$cinfo['mix_price']);}
		if($cinfo['max_price']>0){$map['coupon_price'] = array('elt',$cinfo['max_price']);}
		if($cinfo['max_price']>0 && $cinfo['mix_price']>0){$map['coupon_price'] = array(array('egt',$cinfo['mix_price']),array('elt',$cinfo['max_price']),'and');}
		if($cinfo['mix_volume']>0){$map['volume'] = array('egt',$cinfo['mix_volume']);}
		if($cinfo['max_volume']>0){$map['volume'] = array('elt',$cinfo['max_volume']);}
		if($cinfo['max_volume']>0 && $cinfo['mix_volume']>0){$map['volume'] = array(array('egt',$cinfo['mix_volume']),array('elt',$cinfo['max_volume']),'and');}

		if($cinfo['thiscid']==0){
    		$id_arr = $this->_cate_mod->get_child_ids($cid, true);
    		$map['cate_id'] = array('IN', $id_arr);
			$today_wh['cate_id'] = array('IN', $id_arr);
		}

		$today_str = mktime(0,0,0,date("m"),date("d"),date("Y"));
		$tomorr_str = mktime(0,0,0,date("m"),date("d")+1,date("Y"));
		$today_wh['coupon_start_time'] = array(array('egt',$today_str),array('elt',$tomorr_str)) ;
		$today_wh['pass'] = '1';
		$today_wh['isshow'] = '1';
		$today_item = $this->_mod->where($today_wh)->count();
		$this->assign('today_item', $today_item);

		$this->assign('cid',$cid);
		$this->assign('pager','cate');
		$this->assign('cinfo',$cinfo);
		if($cinfo['wait_time'] == '1'){
			$map['coupon_start_time'] = array('egt',time());
		}elseif($cinfo['wait_time'] =='2'){
			$map['coupon_start_time'] = array('elt',time());
		}
		if($cinfo['end_time'] == '1'){
			$map['coupon_end_time'] = array('egt',time());
		}
		if($cinfo['ems'] == '1'){
			$map['ems'] = '1';
		}
		$map['pass']="1";
		$map['isshow'] = '1';
		$index_info['sort']=$sort;
		$index_info['status']=$status;
		$index_info['cid']=$cid;
		$page_size = C('ftx_index_page_size');
		$p = I('p',1,'intval'); //页码
		$index_info['p']=$p;
		$start = $page_size * ($p - 1) ;

		if (false === $cate_list = F('cate_list')) {
			$cate_list = D('items_cate')->cate_cache();
		}
	
		$this->assign('cate_list', $cate_list); //分类

		if(IS_AJAX){
            $ajaxpage = I('ajaxpage','2','intval'); 
			if($ajaxpage>5){$this->ajaxReturn(0, '加载完成');}
			$page_er = 24;
			$start = $start + 24*($ajaxpage-1);
        }else{
			$page_er = 24;
        }
		$items_list = $this->_mod->where($map)->order($order)->limit($start . ',' . $page_er)->select();

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

		if(IS_AJAX){
			if(!$items){$this->ajaxReturn(0, '加载完成');}
			$this->assign('items_list', $items);
			$resp = $this->fetch('ajax');
            $this->ajaxReturn(1, '', $resp);
		}
		$this->assign('pagecount', $pagecount);
		F('items_list', $items);
		$this->assign('items_list', $items);
		$this->assign('index_info',$index_info);
		$count = $this->_mod->where($map)->count();
		$pager = $this->_pager($count, $page_size);
//		$this->assign('page', $pager->kshow());
        $this->assign('page', $pager->zshow());
		$this->assign('total_item',$count);
		$this->assign('ajaxurl',U('index/cate',array('cid'=>$cid,'p'=>$index_info['p'],'sort'=>$index_info['sort'])));

		 
        $this->assign('nav_curr', 'index');
        $this->_config_seo(C('ftx_seo_config.cate'), array(
            'cate_name' => $cinfo['name'],
            'seo_title' => $cinfo['seo_title'],
			'seo_keywords' => $cinfo['seo_keys'],
			'seo_description' => $cinfo['seo_desc'],
        ));
		$this->display('index');
	}
}