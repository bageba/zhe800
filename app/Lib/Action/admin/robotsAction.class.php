<?php

class robotsAction extends BackendAction {

	private $_tbconfig = null;
	public function _initialize() {
        parent::_initialize();
        $this->_mod = D('robots');
        $this->_cate_mod = D('items_cate');
		$api_config = M('items_site')->where(array('code' => 'ftxia'))->getField('config');
        $this->_tbconfig = unserialize($api_config);
    }
 
	 public function _before_index() {
        $res = $this->_cate_mod->field('id,name')->select();
        $cate_list = array();
        foreach ($res as $val) {
            $cate_list[$val['id']] = $val['name'];
        }
        $this->assign('cate_list', $cate_list);
        $this->sort = 'ordid ASC,';
        $this->order ='last_time DESC';
    }

	public function add(){
		if (IS_POST) {
			$name					= $this->_post('name', 'trim');
			$cid					= $this->_post('cid', 'trim');
			$recid					= $this->_post('recid', 'trim');
			$cate_id				= $this->_post('cate_id', 'trim');
			$keyword				= $this->_post('keyword', 'trim');
			$page					= $this->_post('page', 'trim');
			$sort					= $this->_post('sort', 'trim');
			$start_commissionRate	= "1000";//$this->_post('start_commissionRate', 'trim');
			$end_commissionRate		= "10000";//$this->_post('end_commissionRate', 'trim');
			$start_coupon_rate		= $this->_post('start_coupon_rate', 'trim');
			$end_coupon_rate		= $this->_post('end_coupon_rate', 'trim');
			$start_volume			= $this->_post('start_volume', 'trim');
			$end_volume				= $this->_post('end_volume', 'trim');
			$start_price			= $this->_post('start_price', 'trim');
			$end_price				= $this->_post('end_price', 'trim');
			$start_credit			= $this->_post('start_credit', 'trim');
			$end_credit				= $this->_post('end_credit', 'trim');
			$shop_type				= $this->_post('shop_type', 'trim','all');
			$ems					= $this->_post('ems', 'trim');
			$tb_cid					= $this->_post('tb_cid', 'trim');
			$http_mode				= $this->_post('http_mode', 'trim');
			if( !$name||!trim($name) ){
				$this->error('请填写采集器名称');
			}
			if( !$cate_id||!trim($cate_id) ){
				$this->error('请选择商品分类');
			}

			if($http_mode == 1){
				//淘宝网采集
				if(!$keyword && !$tb_cid) {
					 $this->error('请填写关键词或者填写淘宝网分类ID');
				}
				if($start_price > $end_price){
					$this->error('最低价格不能高于最高价格');
				}
			}else{
				//API采集
				if (!$keyword && !$cid) {
					$this->error('请填写关键词或选择API分类');
				}
				if($start_commissionRate > $end_commissionRate){
					$this->error('起始佣金不能高于最高佣金');
				}
			}
			
			if($start_coupon_rate > $end_coupon_rate){
				$this->error('最低折扣不能高于最高折扣');
			}
			if($start_volume > $end_volume){
				$this->error('最低销量不能高于最高销量');
			}

			$data['name'] = $name;
			$data['cid'] = $cid;
			$data['recid'] = $recid;
			$data['cate_id'] = $cate_id;
			$data['keyword'] = $keyword;
			$data['page'] = $page;
			$data['sort'] = $sort;
			$data['start_commissionRate'] = $start_commissionRate;
			$data['end_commissionRate'] = $end_commissionRate;
			$data['start_coupon_rate'] = $start_coupon_rate;
			$data['end_coupon_rate'] = $end_coupon_rate;
			$data['start_volume'] = $start_volume;
			$data['end_volume'] = $end_volume;
			$data['start_price'] = $start_price;
			$data['end_price'] = $end_price;
			$data['start_credit'] = $start_credit;
			$data['end_credit'] = $end_credit;
			$data['shop_type'] = $shop_type;
			$data['ems'] = $ems;
			$data['tb_cid'] = $tb_cid;
			$data['http_mode'] = $http_mode;

			$this->_mod->create($data);
			$item_id = $this->_mod->add();
			$this->success('添加成功！');
		}
	}

	 public function edit() {
        if (IS_POST) {
			$id			= $this->_post('id', 'trim');
			$name		= $this->_post('name', 'trim');
			$cid		= $this->_post('cid', 'trim');
			$recid		= $this->_post('recid', 'trim');
			$cate_id		= $this->_post('cate_id', 'trim');
			$keyword		= $this->_post('keyword', 'trim');
			$page		= $this->_post('page', 'trim');
			$sort		= $this->_post('sort', 'trim');
			$start_commissionRate		= "1000";//$this->_post('start_commissionRate', 'trim');
			$end_commissionRate		= "10000";//$this->_post('end_commissionRate', 'trim');
			$start_coupon_rate		= $this->_post('start_coupon_rate', 'trim');
			$end_coupon_rate		= $this->_post('end_coupon_rate', 'trim');
			$start_volume			= $this->_post('start_volume', 'trim');
			$end_volume				= $this->_post('end_volume', 'trim');
			$start_price			= $this->_post('start_price', 'trim');
			$end_price				= $this->_post('end_price', 'trim');
			$start_credit			= $this->_post('start_credit', 'trim');
			$end_credit				= $this->_post('end_credit', 'trim');
			$shop_type				= $this->_post('shop_type', 'trim','all');
			$ems					= $this->_post('ems', 'trim');
			$tb_cid					= $this->_post('tb_cid', 'trim');
			$http_mode				= $this->_post('http_mode', 'trim');

			if( !$name||!trim($name) ){
				$this->error('请填写采集器名称');
			}
			if( !$cate_id||!trim($cate_id) ){
				$this->error('请选择商品分类');
			}

			if($http_mode == 1){
				//淘宝网采集
				if(!$keyword && !$tb_cid) {
					 $this->error('请填写关键词或者填写淘宝网分类ID');
				}
				if($start_price > $end_price){
					$this->error('最低价格不能高于最高价格');
				}
			}else{
				//API采集
				if (!$keyword && !$cid) {
					//$this->error('请填写关键词或选择API分类');
				}
				if($start_commissionRate > $end_commissionRate){
					$this->error('起始佣金不能高于最高佣金');
				}
			}
			
			if($start_coupon_rate > $end_coupon_rate){
				$this->error('最低折扣不能高于最高折扣');
			}
			if($start_volume > $end_volume){
				$this->error('最低销量不能高于最高销量');
			}

			$data['name'] = $name;
			$data['cid'] = $cid;
			$data['recid'] = $recid;
			$data['cate_id'] = $cate_id;
			$data['keyword'] = $keyword;
			$data['page'] = $page;
			$data['sort'] = $sort;
			$data['start_commissionRate'] = $start_commissionRate;
			$data['end_commissionRate'] = $end_commissionRate;
			$data['start_coupon_rate'] = $start_coupon_rate;
			$data['end_coupon_rate'] = $end_coupon_rate;
			$data['start_volume'] = $start_volume;
			$data['end_volume'] = $end_volume;
			$data['start_price'] = $start_price;
			$data['end_price'] = $end_price;
			$data['start_credit'] = $start_credit;
			$data['end_credit'] = $end_credit;
			$data['shop_type'] = $shop_type;
			$data['ems'] = $ems;
			$data['tb_cid'] = $tb_cid;
			$data['http_mode'] = $http_mode;
 
            $this->_mod->where(array('id'=>$id))->save($data);
            $this->success(L('operation_success'));
        } else {
            $id = $this->_get('id','intval');
            $item = $this->_mod->where(array('id'=>$id))->find();
            $spid = $this->_cate_mod->where(array('id'=>$item['cate_id']))->getField('spid');
            if( $spid==0 ){
                $spid = $item['cate_id'];
            }else{
                $spid .= $item['cate_id'];
            }
            $this->assign('selected_ids',$spid); //分类选中
            $this->assign('info', $item);
            //来源
            $orig_list = M('items_orig')->select();
            $this->assign('orig_list', $orig_list);
			if (!function_exists("curl_getinfo")) {
				$this->error(L('curl_not_open'));
			}
			//获取淘宝商品分类
			$items_cate = $this->_get_tbcats();
			$this->assign('items_cate', $items_cate);
            $this->display();
        }
    }


	public function add_do() {
		//判断CURL
        if (!function_exists("curl_getinfo")) {
            $this->error(L('curl_not_open'));
        }
        //获取淘宝商品分类
        $items_cate = $this->_get_tbcats();
        $this->assign('items_cate', $items_cate);
        $this->display();
	}

	public function collect(){
		$id	= I('id','','intval');
		$auto	= I('auto',0,'intval');
		$p		= I('p',1,'intval');
		if($auto){
			
			$rid	= I('rid',0,'intval');
			if(false === F('robots_time')){
				F('robots_time', time());
			}
			if(!$rid){
				$where['status'] = 1;
				$where['http_mod'] = 0;
				$robots = M('robots')->where($where)->order('ordid asc')->select();
				F('robots', $robots);
				$rid = 0;
				
			}
			$robots = F('robots');
			$date = $robots[$rid];
			if(!$date){
				F('totalcoll', NULL);
				F('robots_time', NULL);
				$this->ajaxReturn(0, '一键全自动已经采集完成！请返回，谢谢');
			}
			
			if ($p > $date['page']) {
				$p = 1;
				$rid = $rid+1;
				$date = $robots[$rid];
				if(!$date){
					F('totalcoll', NULL);
					F('robots_time', NULL);
					$this->ajaxReturn(0, '一键全自动已经采集完成！请返回，谢谢');
				}
			}
			$np = $p+1;
			$result_data = $this->taobao_collect($date,$p);
			$this->assign('result_data', $result_data);
			$msg['title'] = '一键全自动采集';
			$msg['np'] = $np;
			$msg['rid'] = $rid;
			$this->assign('date',$date);
			$this->assign('robots_count',count($robots));
			$this->assign('rids',$rid+1);
			$resp = $this->fetch('auto_collect');
			$this->ajaxReturn(1,$msg,$resp);
		}else{
			$date = M('robots')->where(array('id'=>$id))->find();
			F('robot_setting', $date);
		}
		
		if($date){
			if($date['http_mode'] == '0'){
				if(!$this->_tbconfig['app_key']){$this->ajaxReturn(0, '请设置appkey');}
				if ($p > $date['page']) {
					F('totalcoll', NULL);
					$this->ajaxReturn(0, '已经采集完成'.$date['page'].'页！请返回，谢谢');
				}
				$result_data = $this->api_collect($date,$p);
				$this->assign('result_data', $result_data);
				$resp = $this->fetch('collect');
				$this->ajaxReturn(1, '', $resp);
			}else{
				if ($p > $date['page']) {
					F('totalcoll', NULL);
					$this->ajaxReturn(0, '已经采集完成'.$date['page'].'页！请返回，谢谢');
				}
				$result_data = $this->taobao_collect($date,$p);
				$this->assign('result_data', $result_data);
				$resp = $this->fetch('collect');
				$this->ajaxReturn(1, '', $resp);
			}
		}else{
			$this->ajaxReturn(0, 'error');
		}
	}

	public function taobao_collect($date,$p){
		M('robots')->where(array('id'=>$date['id']))->save(array('last_page'=>$p,'last_time'=>time()));
		$s=($p-1)*44;
		$q='';
		$tp= F('tp');
		if($p>1){
			if ($p > $tp) {
				$this->ajaxReturn(0, '已经采集完成'.$tp.'页！请返回，谢谢');
			}
		}
		if($p==1){
			$totalcoll = 0;
		}else{
			$totalcoll = F('totalcoll');
		}
				 
		if($date['tb_cid']){$q.='&cat='.$date['tb_cid'];}
		if($date['keyword']){$q.='&q='.$date['keyword'];}
		if($date['shop_type'] == 'B'){
				$q.='&tab=mall';
		}else{
				$q.='&tab=all';
		}
		if($date['start_coupon_rate'] && $date['end_coupon_rate'] && $date['start_coupon_rate']< $date['end_coupon_rate'] ){
				$q.='&zk_rate=%5B'.$date['start_coupon_rate'].'%2C'.$date['end_coupon_rate'].'%5D';
		}
		if($date['start_price'] && $date['end_price'] && $date['start_price']< $date['end_price'] ){
				//$q.='&start_price='.$date['start_price'].'&end_price='.$date['end_price'];
				$q.='&filter=reserve_price%5B'.$date['start_price'].'%2C'.$date['end_price'].'%5D';
		}
		if($date['sort']){
			$sorts=explode("|",$date['sort']);
			if($sorts[0]=='volume'){
				$sorts[0]='sale';
			}
			$q.='&sort='.$sorts[0].'-'.$sorts[1];
		}
		$url='http://s.taobao.com/search?commend=all'.$q.'&style=grid&atype=b&limitPromotion=true&filterFineness=2&fs=1&discount_index=1&s='.$s.'&zk_type=0#J_FilterTabBar';
		$ftxia_https = new ftxia_https();
		$ftxia_https->fetch($url);
		$content = $ftxia_https->results;
		if(!$content){
			$content = file_get_contents($url);
		}
		$content = Newiconv("GBK", "UTF-8", $content); 
		$ck	 = get_word($content,'<li class="home"><a href="http:\/\/www.taobao.com\/">','<\/a><\/li>');
		$pre = L('pre');
		$totalnum=get_word($content,L($pre.'total_start'),L($pre.'total_end'));
		if(strpos($totalnum,L($pre.'wang'))){
			$totalnum=get_word($content,L($pre.'total_start'),L($pre.'total_ends'));
			$totalnum=$totalnum*10000;
		}
		$tp = intval($totalnum/40)+1;
		F('tp',$tp);	
		if(preg_match_all(L($pre.'listitem'), $content, $matchitem)) {
			for($i=0;$i<count($matchitem[0]);$i++){
				$pi=($p-1)*40+$i;
				$msg='折扣不满足。不采集';
				$item=$matchitem[1][$i];
				$titlebar	=get_word($item,L($pre.'titlebar_start'),L($pre.'titlebar_end'));
				$title	=get_word($titlebar,L($pre.'title_start'),L($pre.'title_end'));
				$img	=get_word($item,L($pre.'img_start'),L($pre.'img_end'));
				if(!$img){
					$img=get_word($item,L($pre.'imgs_start'),L($pre.'imgs_end'));
					if(!$img){
						$img=get_word($item,L($pre.'imgss_start'),L($pre.'imgss_end'));
					}
				}
				$iid	=get_word($item,L($pre.'iid_start'),L($pre.'iid_end'));
				$price	=get_word($item,L($pre.'price_start'),L($pre.'price_end'));
				if(!$price){
					$pricebar=get_word($item,L($pre.'pricesssbar_start'),L($pre.'pricesssbar_end'));
					$price=get_word($pricebar,L($pre.'pricesss_start'),L($pre.'pricesss_end'));
				}
				$zkprice=get_word($item,L($pre.'zkprice_start'),L($pre.'zkprice_end'));
				if(!$zkprice){
					$zkprice=get_word($item,L($pre.'zkprices_start'),L($pre.'zkprices_end'));
				}
				if(!$price){
					$price=$zkprice;
				}
				$volume		=get_word($item,L($pre.'volume_start'),L($pre.'volume_end'));
				$nickbar	=get_word($item,L($pre.'nickbar_start'),L($pre.'nickbar_end'));
				$nick		=get_word($nickbar,L($pre.'nick_start'),L($pre.'nick_end'));
				$ems		=get_word($item,L($pre.'ems_start'),L($pre.'ems_end'));
				if(!$ems){
					$ems	=get_word($item,L($pre.'emss_start'),L($pre.'emss_end'));
				} 
				if($ems){
					if(strpos($ems,L($pre.'emsor'))){$ems='1';}else{$ems='0';}
				}else{
					$ems=L('def_ems');
				}
				if(!$volume){$volume=0;}
				$zekou	=get_word($item,L($pre.'zekou_start'),L($pre.'zekou_end'));
				if(!$zekou){
					$zekou	=get_word($item,L($pre.'zekous_start'),L($pre.'zekous_end'));
					if(!$zekou){$zekou=L('def_coupon_rate');}
				}
				$coupon_add_time = C('ftx_coupon_add_time');
				if($coupon_add_time){
					$times	=	(int)(time()+$coupon_add_time*3600);
				}else{
					$times	=	(int)(time()+72*86400);
				}
				
				if(strpos($item,'tmall.com')){
					$itemarray['shop_type']='B';
				}else{
					$itemarray['shop_type']='C';
				}
				$itemarray['title']=$title;
				$itemarray['pic_url']=$img;
				$itemarray['num_iid']=$iid;
				$itemarray['price']=$price;
				$itemarray['coupon_price']=$zkprice;
				$itemarray['volume']=$volume;
				$itemarray['nick']=$nick;
				$itemarray['ems']=$ems;
				$itemarray['cate_id']=$date['cate_id'];
				$itemarray['coupon_rate']=$zekou*1000;
				$itemarray['coupon_end_time']=$times;
				$itemarray['coupon_start_time']=time();
				if($title && $img && $iid && $nick){$result['item_list'][]=$itemarray;}			
			}
		}else{
			$result['msg']='采集结束！请返回2';
		}
		$taobaoke_item_list = $result['item_list'];
		$taobaoke_item_list && F('taobaoke_item_list', $taobaoke_item_list);
		$coll=0;
		$thiscount=0;
		foreach ($taobaoke_item_list as $key => $val) {
			$res= $this->_ajax_tb_publish_insert($val);
			if($res>0){
				$coll++;
				$totalcoll++;
			} 
			$thiscount++;
		}
		F('totalcoll',$totalcoll);
		$result_data['p']			= $p;
		$result_data['msg']			= $msg;
		$result_data['coll']		= $coll;
		$result_data['totalcoll']	= $totalcoll;
		$result_data['totalnum']	= $totalnum;
		$result_data['thiscount']	= $thiscount;
		return $result_data;
	}

	public function api_collect($date,$p){
		M('robots')->where(array('id'=>$date['id']))->save(array('last_page'=>$p,'last_time'=>time()));
		if (false === $totalcoll = F('totalcoll')) {
			$totalcoll = 0;
		}
		if (false === $robots_time = F('robots_time')) {
			$robots_time = time();
			F('robots_time', time());
		}
		$map['keyword']		= $date['keyword'];									//关键词
		$map['cid']			= $date['cid'];										//api分类ID
		$map['cate_id']		= $date['cate_id'];									//入库分类ID
		if($date['start_commissionRate']<100){
			$map['start_commissionRate']	= ($date['start_commissionRate']*100);//佣金比率下限
		}else{
			$map['start_commissionRate']	= $date['start_commissionRate'];	//佣金比率下限
		}
		if($date['end_commissionRate']<100){
			$map['end_commissionRate']		= ($date['end_commissionRate']*100);	//佣金比率上限
		}else{
			$map['end_commissionRate']		= $date['end_commissionRate'];		//佣金比率上限
		}
		if($date['start_coupon_rate']<100){
			$map['start_coupon_rate']		= ($date['start_coupon_rate']*100);	//折扣最低比率
		}else{
			$map['start_coupon_rate']		= $date['start_coupon_rate'];		//折扣最低比率
		}
		if($date['end_coupon_rate']<100){
			$map['end_coupon_rate']			= ($date['end_coupon_rate']*100);		//折扣最高比率
		}else{
			$map['end_coupon_rate']			= $date['end_coupon_rate'];			//折扣最高比率
		}
		$map['start_volume']			= $date['start_volume'];			//销量下限
		$map['end_volume']				= $date['end_volume'];				//销量上限
		$map['start_price']				= $date['start_price'];				//价格下限
		$map['end_price']				= $date['end_price'];				//价格上限
		$map['start_credit']			= $date['start_credit'];			//卖家信用下限
		$map['end_credit']				= $date['end_credit'];				//卖家信用上限
		$map['shop_type']				= $date['shop_type'];				//是否天猫商品
		$map['recid']					= $date['recid'];					//是否更新分类
		if($date['sort']){
			$sorts=explode("|",$date['sort']);
			$map['sort']=$sorts[0].'_'.$sorts[1];							//排序方法
		}					
		$result							= $this->_get_list($map, $p);
		$taobaoke_item_list				= $result['item_list'];
		$totalnum						= $result['count'];
		$taobaoke_item_list && F('taobaoke_item_list', $taobaoke_item_list);
		$coll=0;
		$thiscount=4;
		if(is_array($taobaoke_item_list)){
			$msg = '成功！';
		}else{
			$msg = '失败！';
		}
		foreach ($taobaoke_item_list as $key => $val) {
			if($map['start_volume'] <= $val['volume'] &&  $val['volume'] <= $map['end_volume'] &&  $map['start_price'] <= $val['coupon_price'] &&  $val['coupon_price'] <= $map['end_price'] && $map['start_coupon_rate'] <=  $val['coupon_rate'] && $val['coupon_rate'] <= $map['end_coupon_rate']){
				if($map['shop_type'] == 'B'){
					if($map['shop_type'] == $val['shop_type']){
						/*入库操作START*/
						$coupon_add_time = C('ftx_coupon_add_time');
						if($coupon_add_time){
							$times	=	(int)(time()+$coupon_add_time*3600);
						}else{
							$times	=	(int)(time()+72*86400);
						}
						$val['coupon_start_time'] = time();
						$val['coupon_end_time'] = $times;
						$val['recid'] = $map['recid'];
								
						$res= $this->_ajax_ftx_publish_insert($val);
						if($res>0){
							$coll++;
							$totalcoll++;
						}
						/*入库操作END*/
					}
				}else{
					/*入库操作START*/
					$coupon_add_time = C('ftx_coupon_add_time');
					if($coupon_add_time){
						$times	=	(int)(time()+$coupon_add_time*3600);
					}else{
						$times	=	(int)(time()+72*86400);
					}
					$val['coupon_start_time'] = time();
					$val['coupon_end_time'] = $times;
					$val['recid'] = $map['recid'];
					$res= $this->_ajax_ftx_publish_insert($val);
					if($res>0){
						$coll++;
						$totalcoll++;
					}
					/*入库操作END*/
				}
			}
			$thiscount++;	  
		}
		F('totalcoll',$totalcoll);
		$result_data['p']			= $p;
		$result_data['msg']			= $msg;
		$result_data['coll']		= $coll;
		$result_data['totalcoll']	= $totalcoll;
		$result_data['totalnum']	= $totalnum;
		$result_data['thiscount']	= $thiscount;
		$result_data['times']		= lefttime(time()-$robots_time);
		return $result_data;
	}

	public function robot(){
		$id		= $this->_get('id', 'trim');
		$date = M('robots')->where(array('id'=>$id))->find();
		if($date){
			$map['keyword'] = $date['keyword'];									//关键词
            $map['cid'] = $date['cid'];											//api分类ID
			$map['cate_id'] = $date['cate_id'];									//入库分类ID
            $p = $this->_get('p', 'intval', 1);
            if ($p > 100) {
                $this->redirect('robots/index');
            }
            $map['start_volume']			= $date['start_volume'];			//销量下限
            $map['end_volume']				= $date['end_volume'];				//销量上限
			$map['start_price']				= $date['start_price'];				//价格下限
            $map['end_price']				= $date['end_price'];				//价格上限
            $map['start_coupon_rate']		= $date['start_coupon_rate'];		//折扣最低比率
            $map['end_coupon_rate']			= $date['end_coupon_rate'];			//折扣最高比率
            $map['start_credit']			= $date['start_credit'];			//卖家信用下限
            $map['end_credit']				= $date['end_credit'];				//卖家信用上限
            $map['shop_type']				= $date['shop_type'];				//是否天猫商品
            $map['sort']					= $date['sort'];					//排序方法
			$result							= $this->_get_list($map, $p);
            $pager = new Page($result['count'], 40);
            $page = $pager->show();
            $this->assign("page", $page);
			$this->assign("p", $p);
			$this->assign("count", $result['count']);
            $taobaoke_item_list = $result['item_list'];
			$taobaoke_item_list && F('taobaoke_item_list', $taobaoke_item_list);
			$returnlist=array();
			foreach ($taobaoke_item_list as $key => $val) {
					  $returnlist[] = $this->_publish_insert($val);
			}
			$this->assign('list', $returnlist);                               
			$this->assign('list_table', true);
			$lv =$result['count']/40;
			if($lv>$p){
				$nexturl = U('robots/robot',array('id'=>$id,'p'=>$p));
				$this->assign('nexturl', $nexturl);
			}
			$this->display();
		}
	}

    private function _publish_insert($item) {
        $item['title'] = strip_tags($item['title']);
        $item['click_url'] = Url::replace($item['click_url'], array('spm' => '2014.' . $this->_tbconfig['app_key'] . '.1.0'));
        $result = D('items')->publish($item);
        return $result;
    }

	private function _ajax_publish_insert($item) {
        $result = D('items')->ajax_publish($item);
        return $result;
    }

	private function _ajax_ftx_publish_insert($item) {
        $result = D('items')->ajax_ftx_publish($item);
        return $result;
    }

	private function _ajax_tb_publish_insert($item) {
        $item['title'] = strip_tags($item['title']);
        $result = D('items')->ajax_tb_publish($item);
        return $result;
    }

	 public function ajax_get_tbcats() {
        $cid = $this->_get('cid', 'intval', 0);
        $item_cate = $this->_get_tbcats($cid);
        if ($item_cate) {
            $this->ajaxReturn(1, '', $item_cate);
        } else {
            $this->ajaxReturn(0);
        }
    }

    /**
     * 获取商品列表
     * 返回商品列表和总数
     */
    private function _get_list($map, $p) {
        $tb_top = $this->_get_tb_top();
        $req = $tb_top->load_api('FtxiaItemsCouponGetRequest');
        $req->setFields('num_iid,title,nick,pic_url,price,click_url,seller_credit_score,item_location,volume,coupon_price,coupon_rate,coupon_end_time,shop_type');
        $req->setPageNo($p);
		$req->setTime(date("Y-m-d"));
        $map['keyword'] && $req->setKeyword($map['keyword']); //关键词
        $map['cid'] && $req->setCid($map['cid']); //分类
        $map['start_price'] && $req->setStartPrice($map['start_price']);
        $map['end_price'] && $req->setEndPrice($map['end_price']);
        $map['shop_type'] && $req->setShopType($map['shop_type']);
        $map['sort'] && $req->setSort($map['sort']);
        $resp = (array)$tb_top->execute($req);
        $count = $resp['total_results'];
        //列表内容
        $iids = array();
        $resp_items = objtoarr($resp['itemlists']);
        $taobaoke_item_list = array();
        foreach ($resp_items  as $val) {
            $val = (array) $val;
			$val['cate_id']=$map['cate_id'];
            $taobaoke_item_list[$val['num_iid']] = $val;
        }
        //返回
        return array(
            'count' => intval($count),
            'item_list' => $taobaoke_item_list,
        );
    }

    private function _get_tbcats($cid = 0) {
        $tb_top = $this->_get_tb_top();
        $req = $tb_top->load_api('ItemcatsGetRequest');
        $req->setFields("cid,parent_cid,name,is_parent");
        $req->setParentCid($cid);
		$req->setTime(date("Y-m-d"));
        $resp = $tb_top->execute($req);
        $res_cats = (array) $resp->item_cats;
        $item_cate = array();
        foreach ($res_cats['item_cat'] as $val) {
            $val = (array) $val;
            $item_cate[] = $val;
        }
        return $item_cate;
    }

    private function _get_tb_top() {
        vendor('Ftxia.TopClient');
        vendor('Ftxia.RequestCheckUtil');
        vendor('Ftxia.Logger');
        $tb_top = new TopClient;
        $tb_top->appkey = $this->_tbconfig['app_key'];
        $tb_top->secretKey = $this->_tbconfig['app_secret'];
        return $tb_top;
    }

    public function item_check(){
        $this->display();
    }

    public function item_checks(){
		$this->item_mod = m( "items" );
		$p = I('p',1,'intval');
        $where['coupon_end_time'] = array("elt",time());
		$where['pass'] = "1";
		$page_size = 10;
		if (false === $itemcheckdata = F('itemcheck')) {
			$itemcheckdata['this_good'] = 0;
			$itemcheckdata['this_bad'] = 0;
			$itemcheckdata['that_good'] = 0;
			$itemcheckdata['that_bad'] = 0;
			$itemcheckdata['total'] = 0;
			$itemcheckdata['not_total'] = 0;
			F('itemcheck', $itemcheckdata);
		}
		$itemcheckdata['this_good'] = 0;
		$itemcheckdata['this_bad'] = 0;

		$CheckItemCount = $this->item_mod->where($where)->count("id");
		$itemcheckdata['not_total'] = $CheckItemCount ;

        $order = "add_time asc ";
        $items_list = $this->item_mod->field('num_iid')->where($where)->order( $order )->limit(0,$page_size)->select();
		$arr_iids = array();
        if($items_list){
            foreach($items_list as $key =>$val){
				$arr_iids[] = $val['num_iid'];
			}
		}else{
			$this->ajaxReturn(0, '更新完成，谢谢！');
		}
		$num_iids = implode(',',$arr_iids);
		if(!$this->_tbconfig['app_key']){$this->ajaxReturn(0, '请设置飞天侠开放平台appkey');}
		$tb_top = $this->_get_tb_top();
        $req = $tb_top->load_api('FtxiaItemsDetailGetRequest');
        $req->setNumiids($num_iids);
        $resp = $tb_top->execute($req);
        $res_items = (array) $resp->itemsdetail;
        foreach ($res_items['item'] as $val) {
            $val = (array) $val; 
			$coupon_add_time = C('ftx_coupon_add_time');
			if($coupon_add_time){
				$times	=	(int)(time()+$coupon_add_time*3600);
			}else{
				$times	=	(int)(time()+72*86400);
			}

			if($val['status'] =='underway' && $val['coupon_price'] && $val['price']){
				$val['coupon_start_time'] = time();
				$val['coupon_end_time'] = $times;
				$val['add_time'] = time();
				$itemcheckdata['this_good'] = $itemcheckdata['this_good']+1;
				$itemcheckdata['that_good'] = $itemcheckdata['that_good']+1;
			}else{
				$val['pass'] = 0;
				$itemcheckdata['this_bad'] = $itemcheckdata['this_bad']+1;
				$itemcheckdata['that_bad'] = $itemcheckdata['that_bad']+1;
				F('bad_item',$val);
			}
			$itemcheckdata['total'] = $itemcheckdata['total']+1;

			$this->item_mod->where(array('num_iid'=>$val['num_iid']))->save($val);
        }
		F('itemcheck', $itemcheckdata);
		$msg['title'] = '商品检测';
		$msg['p'] = $p+1;

		$this->assign('item', $itemcheckdata);
		$resp = $this->fetch('ajax_itemcheck');
		$this->ajaxReturn(1, $msg, $resp);


    }
 
}