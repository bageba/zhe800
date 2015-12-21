<?php

class sign_logModel extends Model
{
    protected $_auto = array (
		array('sign_date','today_time',1,'callback'),
    );


	public function today_time() {
        return strtotime(date('Ymd'));
    }

}