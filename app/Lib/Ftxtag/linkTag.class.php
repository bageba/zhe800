<?php
/**
 * 友情链接标签解析
 */
class linkTag {
    /**
     * 友情链接列表
     * @param array $options 
     */
    public function lists($options) {
        $map['status'] = '1' ;
		$link_mod = M('link');
        $data=M('link')->where($map)->order('ordid asc')->select();
        return $data;
    }
}