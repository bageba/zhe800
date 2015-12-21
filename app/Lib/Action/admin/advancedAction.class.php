<?php
class advancedAction extends BackendAction {
	public function _initialize() {
        parent::_initialize();
        $this->_mod = D('tejia_cate');
    }

	public function index(){
		$this->display();
	}

	public function setting(){
		if(IS_POST){
			$cate_id		= $this->_post('cate_id', 'trim');
			$tejia_cate_id	= $this->_post('tejia_cate_id', 'trim');

			if(!$tejia_cate_id){
				$this->ajaxReturn(0, '采集分类必须选择！');
			}
			if(!$cate_id){
				$this->ajaxReturn(0, '入库分类必须选择！');
			}
			$map = array('id'=>$tejia_cate_id);
			$return = $this->_mod->field('cid,name,pid')->where($map)->find();
			//把采集信息写入缓存
			F('advanced_setting', array(
				'cate_id' => $cate_id,
				'cid' => $return['cid'],
				'pid' => $return['pid'],
			));
			$this->collect();
		}
	}

    public function collect() {
		$source = '';
		if (false === $setting = F('advanced_setting')) {
            $this->ajaxReturn(0, L('illegal_parameters'));
        }
		$p		= $this->_get('p', 'intval', 1);
		if(!$setting['pid']){
			$url = 'http://tejia.taobao.com/tomorrow_item_list.htm?&promotionId='.$setting['cid'].'&p='.$p.'#J_More';
		}else{
			$url = 'http://tejia.taobao.com/tomorrow_item_list.htm?&promotionId='.$setting['pid'].'&cid='.$setting['cid'].'&p='.$p.'#J_More';
		}
		
		if($p==1){
			$totalcoll = 0;
		}else{
			$totalcoll = F('totalcoll');
		}

		$coll=0;
		$source = Http::fsockopenDownload($url);
		if(!$source){
			$source = file_get_contents($url);
		}
		$source = rtrim(ltrim(trim($source), '('), ')'); 
		$source = iconv('GBK', 'UTF-8//IGNORE', $source);
		if(strpos($source,'no-pro-box')){
			$this->ajaxReturn(0, '该类目暂时没有特价商品');
		}
		$sources = get_word($source,'<div class="filter-list-detail"">','<!--page start-->');
		if(preg_match_all('/<li(.*?)<\/li>/s', $sources, $matchitem)) { 
			for($i=0;$i<count($matchitem[1]);$i++){
				$item=$matchitem[1][$i];
				$titlehtml = get_word($item,'<dd class="title">','<\/dd>');
				$title	=get_word($titlehtml,'tejiaforenotice" target="_blank">','<\/a>');
				$img	=get_word($item,'src="','_210x210.jpg');
				$iid	=get_word($item,'id=','&');
				$price	=get_word($item,'<dd><del>','<\/del>');
				$zkprice=get_word($item,'<dd><strong>','<\/strong>');
				$volume	=0;
				$nick	='';
				$ems	='1';
				$zekou	=round($zkprice/$price,4);
				if(date("G")<9){
					$coupon_start_time = strtotime(date("Y-m-d H:i:s",mktime(10,0,0,date("m"),date("d"),date("Y"))));
					$coupon_end_time = strtotime(date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d"),date("Y"))));
				}else{
					$coupon_start_time = strtotime(date("Y-m-d H:i:s",mktime(10,0,0,date("m"),date("d")+1,date("Y"))));
					$coupon_end_time = strtotime(date("Y-m-d H:i:s",mktime(8,59,59,date("m"),date("d")+2,date("Y"))));
				}
				$itemarray['shop_type']='C';
				$itemarray['title']=$title;
				$itemarray['pic_url']=$img;
				$itemarray['num_iid']=$iid;
				$itemarray['price']=$price;
				$itemarray['coupon_price']=$zkprice;
				$itemarray['volume']=$volume;
				$itemarray['nick']=$nick;
				$itemarray['ems']=$ems;
				$itemarray['cate_id']=$setting['cate_id'];
				$itemarray['coupon_rate']=$zekou*10000;
				$itemarray['coupon_start_time'] = $coupon_start_time;
				$itemarray['coupon_end_time'] = $coupon_end_time;		
				if($title && $img && $iid ){
					$result['item_list'][]=$itemarray;
				}
			}
		}
		foreach ($result['item_list'] as $key => $val) {
			$res= $this->_ajax_tb_publish_insert($val);
			if($res>0){
				$coll++;
			}
			$totalcoll++;
		}
		if(strpos($source,'<span class="page-next"')){
			$this->ajaxReturn(0, '已经采集完成'.$p.'页,本次采集到'.$totalcoll.'件商品！请返回，谢谢');
		}
		F('totalcoll',$totalcoll);
		$this->assign('p',$p);
		$this->assign('coll', $coll); 
		$this->assign('totalnum', $totalnum);
		$this->assign('totalcoll', $totalcoll);
		$resp = $this->fetch('collect');
		$this->ajaxReturn(1, '', $resp);
    }

	private function _ajax_tb_publish_insert($item) {
        $item['title'] = strip_tags($item['title']);
        $result = D('items')->ajax_yg_publish($item);
        return $result;
    }
}