<?php
class robotsModel extends Model
{
    protected $_auto = array(
        array('last_time', 'time', 1, 'function'),
    );




	/**
     * 新增一个采集器
     */
    public function todb($item) {
        $this->create($item);
        $this->add();
    }

}