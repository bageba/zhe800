<?php
class helpAction extends BackendAction
{
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('help');
        $this->_cate_mod = D('article_cate');
    }

    public function _before_index() {
        $p = $this->_get('p','intval',1);
        $this->assign('p',$p);

        //默认排序
        $this->sort = 'id';
        $this->order = 'ASC';
    }


	/**
     * 编辑
     */
    public function edit() {
        $help_mod = D('help');
        if (IS_POST) {
            if (false === $data = $help_mod->create()) {
                $this->error($help_mod->getError());
            }
            if (!$help_mod->where(array('id'=>$data['id']))->count()) {
                $help_mod->add($data);
            } else {
				$data['last_time'] = time();
                $help_mod->save($data);
            }
            $this->success(L('operation_success'), U('help/index'));
        } else {
            $id = $this->_get('id','intval');
            $info = $help_mod->where(array('id'=>$id))->find();
            $this->assign('info', $info);
            $this->display();
        }
    }


}