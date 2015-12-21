<?php

/**
 * 天猫活动商品获取
 */
class ftxia_itemcollect {

    private $_code = 'ftxia';

	public function fetch_tmall($page) {
        $item_site = M('items_site')->where(array('code' => $this->_code))->find();
        $api_config = unserialize($item_site['config']);

        //使用飞天侠开放平台API
        vendor('Ftxia.TopClient');
        vendor('Ftxia.RequestCheckUtil');
        vendor('Ftxia.Logger');
        $top = new TopClient;
        $top->appkey = $api_config['app_key'];
        $top->secretKey = $api_config['app_secret'];

		//淘客信息
        $req = $top->load_api('TaobaokeItemsDetailGetRequest');
        $req->setPage($page);
        $resp = $top->execute($req);
        if (isset($resp->tmall_items)) {
            $items = (array) $resp->tmall_items;
			return $taoke;
        }
         return false;
    }


    public function get_key($url) {
        $id = $this->get_id($url);
        return 'taobao_' . $id;
    }

}