<?php

class signModel extends Model
{
    protected $_auto = array (
		array('sign_count',1),
        array('last_date','today_time',1,'callback'),
    );

    public function today_time() {
        return strtotime(date('Ymd'));
    }



	
}