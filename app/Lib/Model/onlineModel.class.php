<?php

class onlineModel extends Model
{
    protected $_auto = array (
		array('lasttime', 'time', 1, 'function'),
		array('ip','get_client_ip',1,'function'),
    );


	 

}