<?php
class tejia_cateAction extends BackendAction {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('tejia_cate');
    }
 

    /**
     * 获取紧接着的下一级分类ID
     */
    public function ajax_getchilds() {

        $id = $this->_get('id', 'intval');
        $map = array('pid'=>$id);
        $return = $this->_mod->field('id,name')->where($map)->select();
        if ($return) {
            $this->ajaxReturn(1, L('operation_success'), $return);
        } else {
            $this->ajaxReturn(0, L('operation_failure'));
        }
    }

}