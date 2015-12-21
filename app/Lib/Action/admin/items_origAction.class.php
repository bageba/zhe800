<?php

class items_origAction extends BackendAction{

    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('items_orig');
    }

    public function _before_index() {
        $big_menu = array(
            'title' => '添加商品来源',
            'iframe' => U('items_orig/add'),
            'id' => 'add',
            'width' => '500',
            'height' => '180'
        );
        $this->assign('big_menu', $big_menu);
        //默认排序
        $this->sort = 'ordid';
        $this->order = 'ASC';
    }

    public function ajax_upload_img() {
        //上传图片
        if (!empty($_FILES['img']['name'])) {
            $result = $this->_upload($_FILES['img'], 'item_orig', array('width'=>'16', 'height'=>'16'));
            if ($result['error']) {
                $this->ajaxReturn(0, $result['info']);
            } else {
                $ext = array_pop(explode('.', $result['info'][0]['savename']));
                $data['img'] = str_replace('.' . $ext, '_thumb.' . $ext, $result['info'][0]['savename']);
                $this->ajaxReturn(1, L('operation_success'), $data['img']);
            }
        } else {
            $this->ajaxReturn(0, L('illegal_parameters'));
        }
    }

    public function ajax_check_name() {
        $name = $this->_get('name', 'trim');
        $id = $this->_get('id', 'intval');
        if ($this->_mod->name_exists($name, $id)) {
            $this->ajaxReturn(0, '该商品来源已存在！');
        } else {
            $this->ajaxReturn(1);
        }
    }
}