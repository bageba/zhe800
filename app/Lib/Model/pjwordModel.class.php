<?php

class pjwordModel extends Model
{

    protected $_auto = array (array('add_time','time',1,'function'));

    /**
     * 检测
     */
    public function check($content) {
        $result = array('code'=>0, 'content'=>$content);
        if (!$content) {
            return $result;
        }
        //先分词再检测
        $words = D('items')->get_tags_by_title($content, 500);
        !$words && $words = $content;
        $badwords = $this->field('word')->where(array('badword'=>array('IN', $words)))->select();
        //合法就直接返回
        if (!$badwords) {
			$result['code'] = 1;
            return $result;
        }else{
            $result['code'] = 0;
            return $result;
        }
        return $result;
    }
    /**
     * 是否存在
     */
    public function name_exists($name, $id=0)
    {
        $pk = $this->getPk();
        $where = "word='" . $name . "'  AND ". $pk ."<>'" . $id . "'";
        $result = $this->where($where)->count($pk);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}