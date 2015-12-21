<?php

class unionModel extends Model{

    protected $_auto = array(
        array('add_time','time',1,'function'),		//邀请时间
        array('ip','get_client_ip',1,'function'),	//邀请IP
    );

}