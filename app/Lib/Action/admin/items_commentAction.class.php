<?php
class items_commentAction extends BackendAction
{
    public function _initialize() {
        parent::_initialize();
        $this->_mod = M('items_comment');
    }

    public function index() {
        $prefix = C(DB_PREFIX);

        if ($this->_request("sort", 'trim')) {
            $sort = $this->_request("sort", 'trim');
        } else {
            $sort = $prefix.'items_comment.add_time';
        }
        if ($this->_request("order", 'trim')) {
            $order = $this->_request("order", 'trim');
        } else {
            $order = 'DESC';
        }

        $p = $this->_get('p','intval',1);
        $this->assign('p',$p);
        
        $where = '1=1';
        $keyword = $this->_request('keyword','trim','');
        $keyword && $where .= " AND ".$prefix."items_comment.info LIKE '%".$keyword."%' ";
        $search = array();
        $keyword && $search['keyword'] = $keyword;
        $this->assign('search',$search);

        $count = $this->_mod->join($prefix.'items ON '.$prefix.'items.id='.$prefix.'items_comment.item_id')->where($where)->count($prefix.'items_comment.id');
        $pager = new Page($count,20);
        $list  = $this->_mod->field($prefix.'items_comment.add_time,'.$prefix.'items_comment.info,'.$prefix.'items_comment.uname,'.$prefix.'items.title as item_name,'.$prefix.'items_comment.status,'.$prefix.'items_comment.id,'.$prefix.'items_comment.info')->join($prefix.'items ON '.$prefix.'items.id='.$prefix.'items_comment.item_id')->where($where)->order($sort . ' ' . $order)->limit($pager->firstRow.','.$pager->listRows)->select();
        $this->assign('list',$list);
        $this->assign('page',$pager->show());

        $this->assign('list_table', true);

        $this->display();
    }
    
    /**
     * 删除
     */
    public function delete()
    {
        $ids = trim($this->_request('id'), ',');
        if ($ids) {
            if (false !== $this->_mod->delete($ids)) {
                IS_AJAX && $this->ajaxReturn(1, L('operation_success'));
            } else {
                IS_AJAX && $this->ajaxReturn(0, L('operation_failure'));
            }
        } else {
            IS_AJAX && $this->ajaxReturn(0, L('illegal_parameters'));
        }
    }
}