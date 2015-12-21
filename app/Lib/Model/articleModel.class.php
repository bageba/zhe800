<?php
class articleModel extends RelationModel
{
    //自动完成
    protected $_auto = array(
        array('add_time', 'time', 1, 'function'),
    );
    //自动验证
    protected $_validate = array(
        array('title', 'require', '{%article_title_empty}'),
    );

    public function addtime()
    {
        return date("Y-m-d H:i:s",time());
    }
	public function hits($id){
		$this->where(array('id'=>$id))->setInc('hits',1);
	}
}