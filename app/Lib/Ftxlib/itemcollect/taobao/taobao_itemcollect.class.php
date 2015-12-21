<?php

/**
 * 淘宝商品获取
 */
class taobao_itemcollect {

    private $_code = 'taobao';

	public function fetch_tb($url) {
        $id = $this->get_id($url);
        if (!$id) {
            return false;
        }
        $item_site = M('items_site')->where(array('code' => $this->_code))->find();
        $api_config = unserialize($item_site['config']);

        //使用淘宝开放平台API
        vendor('Taobaotop.TopClient');
        vendor('Taobaotop.RequestCheckUtil');
        vendor('Taobaotop.Logger');
        $tb_top = new TopClient;
        $tb_top->appkey = $api_config['app_key'];
        $tb_top->secretKey = $api_config['app_secret'];

		//淘客信息
        $req = $tb_top->load_api('TaobaokeItemsDetailGetRequest');
        $req->setFields("num_iid,title,nick,pic_url,click_url,price,detail_url,auction_point,commission");
        $req->setNumIids($id);
        $resp = $tb_top->execute($req);
        if (isset($resp->taobaoke_item_details)) {
            $taoke = (array) $resp->taobaoke_item_details->taobaoke_item_detail;
            if ($taoke['click_url']) {
                $taoke['click_url'] = Url::replace($taoke['click_url'], array('spm' => '2014.' . $api_config['app_key'] . '.1.0'));
            }
			return $taoke;
        }
         return false;
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

    public function get_key($url) {
        $id = $this->get_id($url);
        return 'taobao_' . $id;
    }

}