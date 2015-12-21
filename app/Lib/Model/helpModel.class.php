<?php
class helpModel extends RelationModel{
    //自动完成
    protected $_auto = array(
        array('last_time', 'time', 1, 'function'),
    );
    //自动验证
    protected $_validate = array(
        array('title', 'require', '{%article_title_empty}'),
    );
}