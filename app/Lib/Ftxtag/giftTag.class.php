<?php
/**
 * 礼物标签
 *
 * @author Ftxia
 */
class giftTag {    

    public function show() {
		$score_item_mod = D('score_item');
		$data = $score_item_mod->order('ordid desc')->limit('0,4')->select();
        return $data;
    }
}