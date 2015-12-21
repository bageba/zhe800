<?php
class pjwordAction extends BackendAction{
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('badword');
    }

    public function _before_index() {
        $big_menu = array(
            'title' => '添加关键词',
            'iframe' => U('pjword/add'),
            'id' => 'add',
            'width' => '400',
            'height' => '50'
        );
        $this->assign('big_menu', $big_menu);

    }

    public function ajax_check_name() {
        $name = $this->_get('word', 'trim');
        $id = $this->_get('id', 'intval');
        if (D('pjword')->name_exists($name, $id)) {
            $this->ajaxReturn(0, '该关键字已存在');
        } else {
            $this->ajaxReturn(1);
        }
    }
}