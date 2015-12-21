<?php
/**
 * 导航标签
 */
class navTag {    
    /**
     * 导航列表
     * @param array $options 
     */
    public function lists($options) {

		$options['field'] = isset($options['field']) ? trim($options['field']) : '*';
        $options['style'] = isset($options['style']) ? trim($options['style']) : 'main';
        $options['num'] = isset($options['num']) ? intval($options['num']) : 0;
        $options['order'] = isset($options['order']) ? trim($options['order']) : 'ordid';

        if ( $options['field'] != '*' || $options['order'] != 'ordid') {
            $nav_mod = M('nav');
            $select = $nav_mod->field($field);
            $map = array('status'=>'1');
            $options['style'] && $map = array('type' => $options['style']); //导航类型
            $select->where($map);
            $options['num'] && $select->limit($options['num']); //数量
            $select->order($options['order']); //排序
            $data = $select->select();
        } else {
            if (false === $nav_list = F('nav_list')) {
                $nav_list = D('nav')->nav_cache();
            }
            $nav_list = $nav_list[$options['style']];
            $options['num'] && $nav_list = array_slice($nav_list, 0, $options['num']);
            $data = $nav_list;
        }
        foreach ($data as $key=>$val) {
            switch ($val['alias']) {
				case 'index':
                    $data[$key]['link'] = U('index/index');
                    break;
				case 'cate':
                    $data[$key]['link'] = U('index/cate');
                    break;
        case 'gift':
                    $data[$key]['link'] = U('gift/index');
                    break;
				case 'article':
                    $data[$key]['link'] = U('article/index');
                    break;
				case 'help':
                    $data[$key]['link'] = U('help/index');
                    break;
        case 'paipai':
                    $data[$key]['link'] = U('paipai/index');
                    break;
            }
        }
        return $data;
    }
}