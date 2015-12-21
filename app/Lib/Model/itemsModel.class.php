<?php
class itemsModel extends Model
{
    protected $_auto = array(
        array('add_time', 'time', 1, 'function'),
		array('pass',1),
    );

    /**
     * 发布一个商品
     * $item 商品信息
     */
    public function publish($item) {
		$result['title']			= $item['title'];
		$result['pic_url']			= $item['pic_url'];
		$result['nick']				= $item['nick'];
		$result['coupon_price']		= $item['coupon_price'];
		$result['commission_rate']	= $item['commission_rate'];
		$result['price']			= $item['price'];
		$result['volume']			= $item['volume'];
		$result['commission']		= $item['commission'];
		$result['commission_num']	= $item['commission_num'];
		$result['commission_volume']= $item['commission_volume'];
		$result['click_url']		= $item['click_url'];
		$result['shop_click_url']	= $item['shop_click_url'];
        //已经存在？
		if(!$item['coupon_rate']){
			$item['coupon_rate']	= round(($item['coupon_price']/$item['price'])*10000, 0); 
		}
        if ($this->where(array('num_iid'=>$item['num_iid']))->count()) {
		   $result['msg']	= '已经添加!';
           return $result;
        }
   
		$item['pass'] = 1; 
		$item['coupon_start_time']	= strtotime($item['coupon_start_time']);
		$item['coupon_end_time']	= strtotime($item['coupon_end_time']);
        $this->create($item);
        $item_id = $this->add();
        if ($item_id) {
            $result['msg'] = '采集成功!';
            return $result;
        } else {
            $result['msg'] = '添加失败!';
            return $result;
        }
    }



	/**
     * ajax发布商品-飞天侠开放平台
     */
    public function ajax_ftx_publish($item) {
        //是否存在
		//p($item);
        if ($this->where(array('num_iid'=>$item['num_iid']))->count()) {
			if($item['recid']==1){
				unset($item['recid']);
			}else{
				unset($item['recid']);
				unset($item['cate_id']);
			}
			$item['status'] ='underway';
			$item_id = $this->where(array('num_iid'=>$item['num_iid']))->save($item);
			if ($item_id) {
				return 1;
			} else {
				return 0;
			}
        }
		unset($item['recid']);
		$item['pass'] = 1; 
		$item['status'] ='underway';
        $this->create($item);
        $item_id = $this->add();
        if ($item_id) {
            return 1;
        } else {
            return 0;
        }
    }




	/**
     * ajax发布商品
     */
    public function ajax_publish($item) {
		$result['title']			= $item['title'];
		$result['pic_url']			= $item['pic_url'];
		$result['nick']				= $item['nick'];
		$result['coupon_price']		= $item['coupon_price'];
		$result['commission_rate']	= $item['commission_rate'];
		$result['price']			= $item['price'];
		$result['volume']			= $item['volume'];
		$result['commission']		= $item['commission'];
		$result['commission_num']	= $item['commission_num'];
		$result['commission_volume']= $item['commission_volume'];
		$result['click_url']		= $item['click_url'];
		$result['shop_click_url']	= $item['shop_click_url'];

        //已经存在？
        if ($this->where(array('num_iid'=>$item['num_iid']))->count()) {
           return 0;
        }
		if(!$item['coupon_rate']){
			$item['coupon_rate']	= round(($item['coupon_price']/$item['price'])*10000, 0); 
		}
       
		$item['pass'] = 1; 
		$item['coupon_start_time']	= strtotime($item['coupon_start_time']);
		$item['coupon_end_time']	= strtotime($item['coupon_end_time']);
        $this->create($item);
        $item_id = $this->add();
        if ($item_id) {
            return 1;
        } else {
            return 0;
        }
    }


	/**
     * ajax发布商品
     */
    public function ajax_tb_publish($item) {
		//echo(print_r($item));
		$result['title']			= $item['title'];
		$result['pic_url']			= $item['pic_url'];
		$result['nick']				= $item['nick'];
		$result['coupon_price']		= $item['coupon_price'];
		$result['price']			= $item['price'];
		$result['ems']				= $item['ems'];
		$result['volume']			= $item['volume'];
		$result['coupon_rate']		= $item['coupon_rate'];
		$result['coupon_end_time']	= $item['coupon_end_time'];
		$result['coupon_start_time']= $item['coupon_start_time'];
		$robot_setting = F('robot_setting');
		if($robot_setting['ems'] >$item['ems']){
			return 0;
		}
        //已经存在？
        if ($this->where(array('num_iid'=>$item['num_iid']))->count()) {
           return 0;
        }
		if(!$item['coupon_rate']){
			$item['coupon_rate']	= round(($item['coupon_price']/$item['price'])*10000, 0); 
		}
       
		$item['pass'] = 1; 
        $this->create($item);
        $item_id = $this->add();
        if ($item_id) {
            return 1;
        } else {
            return 0;
        }
    }


	/**
     * ajax发布商品
     */
    public function ajax_yg_publish($item) {
		$result['title']			= $item['title'];
		$result['pic_url']			= $item['pic_url'];
		$result['nick']				= $item['nick'];
		$result['coupon_price']		= $item['coupon_price'];
		$result['price']			= $item['price'];
		$result['ems']				= $item['ems'];
		$result['volume']			= $item['volume'];
		$result['coupon_rate']		= $item['coupon_rate'];
		$result['coupon_end_time']	= $item['coupon_end_time'];
		$result['coupon_start_time']= $item['coupon_start_time'];
        //已经存在？
        if ($this->where(array('num_iid'=>$item['num_iid']))->count()) {
           return 0;
        }
		if(!$item['coupon_rate']){
			$item['coupon_rate']	= round(($item['coupon_price']/$item['price'])*10000, 0); 
		}
       
		$item['pass'] = 1; 
        $this->create($item);
        $item_id = $this->add();
        if ($item_id) {
            return 1;
        } else {
            return 0;
        }
    }


	public function get_tags_by_title($title, $num=10){
        vendor('pscws4.pscws4', '', '.class.php');
        $pscws = new PSCWS4();
        $pscws->set_dict(FTX_DATA_PATH . 'scws/dict.utf8.xdb');
        $pscws->set_rule(FTX_DATA_PATH . 'scws/rules.utf8.ini');
        $pscws->set_ignore(true);
        $pscws->send_text($title);
        $words = $pscws->get_tops($num);
        $pscws->close();
        $tags = array();
        foreach ($words as $val) {
            $tags[] = $val['word'];
        }
        return $tags;
    }

    /**
     *返回出售状态
     */
    public function status($status, $stime ,$etime) {
		if('underway'!=$status){ 
			return 'tag_sellout';
		}elseif($stime>time()){
			return 'tag_wait';
		}elseif($etime<time()){
			return 'tag_end';
		}else{
			return 'tag_buy';
		}
	}

	public function click_url($url,$num_iid) {
        if ($url && $num_iid) {
			 $this->where(array('num_iid'=>$num_iid))->save(array('click_url'=>$url));
            return true;
        }
    }

}