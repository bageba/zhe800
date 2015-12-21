<?php

class items_commentModel extends Model{
     protected $_auto = array (
    	array('last_time','time',1,'function'),
    	array('add_time','time',1,'function'),
    );
}