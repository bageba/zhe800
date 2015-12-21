<?php
class goodsAction extends FirstendAction {

    public function _initialize() {
        parent::_initialize();
        if (!$this->visitor->is_login) {
            IS_AJAX && $this->ajaxReturn(0, L('login_please'));
            $this->redirect('user/login');
        }
		$this->_mod = D('items');
		$this->cid = $_SERVER['HTTP_HOST'];
        $this->_cate_mod = D('items_cate');
		$this->assign('nav_curr', 'index');
    }

	public function goods_edit(){
		if(IS_POST){
		}else{

			$id = I('id','', 'trim');
			!$id && $this->_404();
			$item = $this->_mod->where(array('id' => $id))->find();
			!$item && $this->_404();

			$orig_list = M('items_orig')->where(array('pass'=>1))->select();
			$this->assign('orig_list',$orig_list);
			$this->assign('item',$item);
			$this->_config_seo(array(
				'title' => '宝贝修改	-	' . C('ftx_site_name'),
			));
			$this->display();
		}

	}

	public function goods_add() {
		$orig_list = M('items_orig')->where(array('pass'=>1))->select();
		$this->assign('orig_list',$orig_list);
		$this->_config_seo(array(
			'title' => L('goods_add') . '	-	' . C('ftx_site_name'),
		));
		$this->display();
	}

	public function mygoods() {
		$item_mod = M('items');
		$cate_mod = M('items_cate');
		$page_size = 20;
        $p = I('p',1, 'intval'); //页码
        $start = $page_size * ($p - 1) ;

        $res = $cate_mod->field('id,name')->select();
        $cate_list = array();
        foreach ($res as $val) {
            $cate_list[$val['id']] = $val['name'];
        }
        $this->assign('cate_list', $cate_list);
		$type = I('type', 'all', 'trim'); //排序
		$order = 'ordid asc ';
		$map['uid'] = $this->visitor->info['id'];
		switch ($type) {
            case 'all':
                break;
            case 'pass':
                $map['pass'] = 1;   
                break;
			case 'wait':
				$map['pass'] = 0;
				$map['status'] = 'underway';
                break;
			case 'fail':
                $map['pass'] = 0; 
				$map['status'] = 'fail';
                break;
        }
		$goods_list = $item_mod->where($map)->order('add_time desc')->limit($start . ',' . $page_size)->select();
		$this->assign('goods_list', $goods_list);
		$count = $item_mod->where($map)->count('id');
		$pager = $this->_pager($count, $page_size);
        $this->assign('page_bar', $pager->fshow());

		$this->assign('type', $type);
		$this->_config_seo(array(
            'title' => L('my_goods') . '	-	' . C('ftx_site_name'),
        ));
		$this->display();
	}

	public function view(){
		$id = I('id','','trim');
        !$id && $this->_404();
		$item = $this->_mod->where(array('id' => $id))->find();
		!$item && $this->_404();
		if($item['uname'] != $this->visitor->info['username']){
			 $this->redirect('goods/mygoods');
		}

		$this->assign('item', $item);
		$this->_config_seo(array(
            'title' => '报名管理	-	' . C('ftx_site_name'),
        ));
		$this->display();
	}

    /**
     * AJAX获取宝贝
     */
    public function ajaxgetid( )
{
/*
$url = $this->_get( "url","trim");
if ( $url == "")
{
$this->ajaxReturn( 0,l( "please_input").l( "correct_itemurl") );
}
if ( !$this->get_id( $url ) )
{
$this->ajaxReturn( 0,l( "please_input").l( "correct_itemurl") );
}
$iid = $this->get_id( $url );
$items = m( "items")->where( array(
"num_iid"=>$iid
) )->find( );
if ( $items )
{
$this->ajaxReturn( 1005,l( "item_exist") );
}
$itemcollect = new itemcollect( );
$itemcollect->url_parse( $url );
if ( $item = $itemcollect->fetch_tb( ) )
{
$this->ajaxReturn( 1,"",$item );
}
$this->ajaxReturn( 0,l( "item_not_excute") );
*/
}
public function ajaxGetItem()
{
	/*
if(!isset($_REQUEST['link']))
$this->ajaxReturn (0,'未传入商品链接');
$iid=getItemIid($_REQUEST['link']);
if(!$iid)
$this->ajaxReturn (0,'未匹配到商品ID');
$top=$this->_get_tb_top();
$res=$top->load_api('ItemGetRequest');
$res->setNumIid($iid);
$res->setFields('title,pic_url,nick,price,auction_point,coupon_rate');
$resp=$top->execute($res);
if(!isset($resp->item->title))
$this->ajaxReturn (0,'商品信息获取失败');
$items_orig=$this->__getItemsOrig();
$info=array();
$info['num_iid']=$iid;
$info['title']=(string)$resp->item->title;
$info['nick']=(string)$resp->item->nick;
$info['pic_url']=(string)$resp->item->pic_url;
$info['price']=(float)$resp->item->price;
$info['coupon_rate']=(float)$resp->item->coupon_rate;
$info['volume']=  getItemVolume($iid);
if((int)$resp->item->auction_point>0)
{
$info['shop_type']='B';
$info['orig_id']=$items_orig['B'];
}else
{
$info['shop_type']='C';
$info['orig_id']=$items_orig['C'];
}
	 */
	if(!isset($_REQUEST['url']))
	$this->ajaxReturn (0,'未传入商品链接');
	$info = getInfo($_REQUEST['url']);
	$info['shop_type']='B';
	$info['orig_id']='';
	$info['coupon_rate'] = intval(($info['price'] / $info['coupon_price'])) * 1000;
$this->ajaxReturn(1,'',$info);
}
	/**
     * AJAX提交
     */
	public function ajaxadd( )
{
if ( IS_POST )
{
$items_mod = m( "items");
$num_iid = $this->_post( "iid","trim");
$cate_id = $this->_post( "cate_id","trim");
$title = $this->_post( "title","trim");
$nick = $this->_post( "nick","trim");
$price = $this->_post( "price","trim");
$coupon_price = $this->_post( "good_price","trim");
$inventory = $this->_post( "good_inventory","trim");
$pic_url = $this->_post( "pic_url","trim");
$coupon_rate = $this->_post( "coupon_rate","trim");
$shop_type = $this->_post( "shop_type","trim");
$intro = $this->_post( "intro","trim");
$items = $items_mod->where( array(
"num_iid"=>$num_iid
) )->find( );
if ( $items )
{
$this->ajaxReturn( 1005,l( "item_exist") );
}
$data['num_iid'] = $num_iid;
$data['cate_id'] = $cate_id;
$data['title'] = $title;
$data['nick'] = $nick;
$data['price'] = $price;
$data['coupon_price'] = $coupon_price;
$data['coupon_rate'] = $coupon_rate;
$data['inventory'] = $inventory;
$data['volume'] = $inventory;
$data['pic_url'] = $pic_url;
$data['intro'] = $intro;
$data['shop_type'] = $shop_type;
$data['add_time'] = time( );
$data['pass'] = 0;
$data['uid'] = $this->visitor->info['id'];
$data['uname'] = $this->visitor->info['username'];
$items_mod->create( $data );
$items_mod->add( );
$resp = $this->fetch( "dialog:goods_add_success");
$this->ajaxReturn( 1,"",$resp );
}
}


	/**
     * AJAX提交
     */
	public function ajaxedit(){
		if(IS_POST){
			$items_mod		= M('items');
			$num_iid		= I('iid','', 'trim');
			if($num_iid == ''){
				$this->ajaxReturn(1005, '商品IID不能为空，请输入宝贝地址获取');
			}
			$id		= I('id','', 'trim');
			if($id == ''){
				$this->ajaxReturn(1005, 'ID不能为空，请返回正常渠道提交！');
			}
			$cate_id		= I('cate_id','', 'trim');
			$title			= I('title','', 'trim');
			!$title && $this->ajaxReturn(1005, '商品名称不能为空');
			$nick				= I('nick','', 'trim');
			!$nick && $this->ajaxReturn(1005, '掌柜名称不能为空');
			$price			= I('price','', 'trim');
			$coupon_price	= I('good_price','', 'trim');
			$inventory		= I('good_inventory','', 'trim');
			$ems		= I('ems','', 'trim');
			$volume		= I('volume','', 'trim');
			$pic_url		= I('pic_url','', 'trim');
			$shop_type		= I('shop_type','', 'trim');
			$coupon_start_time		= I('coupon_start_time','', 'trim');
			$coupon_end_time			= I('coupon_end_time','', 'trim');
			$intro			= I('intro','', 'trim');

			$map['num_iid'] = $num_iid;
			$map['id']		= $id;
			$map['uname']	= $this->visitor->info['username'];

			$items = $items_mod->where($map)->find();
			!$items && $this->ajaxReturn(1005, L('item_not_exist'));

 
			$data['cate_id']		= $cate_id;
			$data['title']			= $title;
			$data['price']			= $price;
			$data['coupon_price']	= $coupon_price;
			$data['inventory']		= $inventory;
			$data['pic_url']		= $pic_url;
			$data['ems']			= $ems;
			$data['volume']			= $volume;
			$data['intro']			= $intro;
			$data['coupon_start_time']			= strtotime($coupon_start_time);
			$data['coupon_end_time']			= strtotime($coupon_end_time);
			$data['shop_type']		= $shop_type;
			$data['add_time']		= time();
			$data['pass']			= 0;
			$data['status']			= 'underway';
			 if (false == $this->_mod->create($data)) {
                $this->error($this->_mod->getError());
            }
			if($this->_mod->where(array('id'=>$id))->save($data)){
				$resp = $this->fetch('dialog:goods_add_success');
				$this->ajaxReturn(1, '', $resp);
			}else{
				$this->ajaxReturn(0, '数据错误，请检查！');
			}
		}
	}

	public function get_id( $url )
{
$id = 0;
$parse = parse_url( $url );
if ( isset( $parse['query'] ) )
{
parse_str( $parse['query'],&$params );
if ( isset( $params['id'] ) )
{
$id = $params['id'];
return $id;
}
if ( isset( $params['item_id'] ) )
{
$id = $params['item_id'];
return $id;
}
if ( isset( $params['default_item_id'] ) )
{
$id = $params['default_item_id'];
}
}
return $id;
}
public function ajaxgetdesc(){
		$url = $this->_get("url","trim");
		if ($url == "") {
		$this->ajaxReturn(0,l("please_input") .l("correct_itemurl"));
		}
		if (!$this->get_id($url)) {
		$this->ajaxReturn(0,l("please_input") .l("correct_itemurl"));
		}
		
		$itemcollect = new itemcollect( );
		$itemcollect->url_parse($url);
		if ($item = $itemcollect->get_desc()) {
		$this->ajaxReturn(1,"",$item);
		}
		$this->ajaxReturn(0,l("item_not_excute"));	
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
	function getInfo($url){
		$u = parse_url($url);
		//解析get参数
		$param = convertUrlQuery($u['query']);
		//var_dump($param);exit;
		if(!stripos('taobao.com',$u['host'])){
			$shopUrl = "http://a.m.taobao.com/i".$param['id'].".htm";
		}else{
			$shopUrl = "http://a.m.tmall.com/i".$param['id'].".htm";
		}
	
		//echo $shopUrl;exit;
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $shopUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);
		curl_setopt($ch, CURLOPT_MAXREDIRS,2);
		$file_contents = curl_exec($ch);
		//echo $file_contents;die;
		//echo curl_error($ch);
		//echo curl_getinfo($ch,CURLINFO_HTTP_CODE);
		curl_close($ch);
		if(stripos('taobao.com',$u['host'] === false)){
			$data = getTaobaoShopInfo($file_contents);
		}else{
			$data = getTmallShopInfo($file_contents);
		}
		$data['num_iid'] = $param['id'];
		return $data;
	}
	function getTaobaoShopInfo($content){
		//标题正则
	//	echo '<h1>Taobao</h1>';
		$info = array();
		preg_match_all('/<title >(.*?) - 手机淘宝网 <\/title>/i',$content,$arr);
		$info['title'] = $arr[1][0];
		preg_match_all('/<strong class="oran">(.*?)<\/strong>/i',$content,$arr);
		$info['coupon_price'] = $arr[1][0];
		if(substr_count($info['coupon_price'],' - ')){
			$tmp1 = explode(' - ',$info['coupon_price']);
			$info['coupon_price'] = min($tmp1[0],$tmp1[1]);
		}
		preg_match_all('/<del class="gray">(.*?)<\/del>/i',$content,$arr);
		$info['price'] = $arr[1][0];
		if(substr_count($info['price'],' - ')){
			$tmp = explode(" - ",$info['price']);
			$info['price'] = min($tmp[0],$tmp[1]);
		}
		preg_match_all('/月&nbsp; 销&nbsp; 量：(.*?)(.*?)<\/p>/si',$content,$arr);
		$info['volume'] = trim($arr[2][0]);
		preg_match_all('/<img alt="(.*?)" src="(.*?)" \/>/',$content,$arr);
		$info['pic_url'] = str_replace("_320x320.jpg","",$info['pic_url']);
		return $info;
	}
	function getTmallShopInfo($content){
	//	echo '<h1>Tmall</h1>';
		//标题正则

		$info = array();
		preg_match_all('/<title >(.*?) - 手机淘宝网 <\/title>/i',$content,$arr);
		$info['title'] = $arr[1][0];
		preg_match_all('/<strong class="oran">(.*?)<\/strong>/i',$content,$arr);
		$info['coupon_price'] = $arr[1][0];
		if(substr_count($info['coupon_price'],' - ')){
			$tmp1 = explode(' - ',$info['coupon_price']);
			$info['coupon_price'] = min($tmp1[0],$tmp1[1]);
		}
		preg_match_all('/<del class="gray">(.*?)<\/del>/i',$content,$arr);
		$info['price'] = $arr[1][0];
		if(substr_count($info['price'],' - ')){
			$tmp = explode(" - ",$info['price']);
			$info['price'] = min($tmp[0],$tmp[1]);
		}
		preg_match_all('/月&nbsp; 销&nbsp; 量：(.*?)<\/p>/si',$content,$arr);
		$info['volume'] = trim($arr[1][0]);
		preg_match_all('/<img alt=".*?" src="(.*?)" \/>/',$content,$arr);
		$info['pic_url'] = str_replace('170','320',$arr[1][0]);
		$info['pic_url'] = str_replace("_320x320.jpg","",$info['pic_url']);
		return $info;
	}
	function convertUrlQuery($query)
	{
			$queryParts = explode('&', $query);
			$params = array();
			foreach ($queryParts as $param)
			{
				$item = explode('=', $param);
				$params[$item[0]] = $item[1];
			}
			return $params;
	}

	
?>