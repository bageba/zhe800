<?php
/**
 * 函数定义
 */
class funcAction extends Action{


	/**
     * 添加邮件到队列
     */
    protected function _mail_queue($to, $subject, $body, $priority = 1) {
        $to_emails = is_array($to) ? $to : array($to);
        $mails = array();
        $time = time();
        foreach ($to_emails as $_email) {
            $mails[] = array(
                'mail_to' => $_email,
                'mail_subject' => $subject,
                'mail_body' => $body,
                'priority' => $priority,
                'add_time' => $time,
                'lock_expiry' => $time,
            );
        }
        M('mail_queue')->addAll($mails);
        //异步发送邮件
        $this->send_mail(true);
    }

    public function send_mail($is_sync = true) {
        if (!$is_sync) {
            //异步
            session('async_sendmail', true);
            return true;
        } else {
            //同步
            session('async_sendmail', null);
            return D('mail_queue')->send();
        }
    }

    protected function _upload_init($upload) {
        $allow_max = C('ftx_attr_allow_size'); //读取配置
        $allow_exts = explode(',', C('ftx_attr_allow_exts')); //读取配置
        $allow_max && $upload->maxSize = $allow_max * 1024;   //文件大小限制
        $allow_exts && $upload->allowExts = $allow_exts;  //文件类型限制
        $upload->saveRule = 'uniqid';
        return $upload;
    }

    /**
     * 上传文件
     */
    protected function _upload($file, $dir = '', $thumb = array(), $save_rule='uniqid') {
        $upload = new UploadFile();
        if ($dir) {
            $upload_path = C('ftx_attach_path') . $dir . '/';
            $upload->savePath = $upload_path;
        }
        if ($thumb) {
            $upload->thumb = true;
            $upload->thumbMaxWidth = $thumb['width'];
            $upload->thumbMaxHeight = $thumb['height'];
            $upload->thumbPrefix = '';
            $upload->thumbSuffix = isset($thumb['suffix']) ? $thumb['suffix'] : '_thumb';
            $upload->thumbExt = isset($thumb['ext']) ? $thumb['ext'] : '';
            $upload->thumbRemoveOrigin = isset($thumb['remove_origin']) ? true : false;
        }
        //自定义上传规则
        $upload = $this->_upload_init($upload);
        if( $save_rule!='uniqid' ){
            $upload->saveRule = $save_rule;
        }

        if ($result = $upload->uploadOne($file)) {
            return array('error'=>0, 'info'=>$result);
        } else {
            return array('error'=>1, 'info'=>$upload->getErrorMsg());
        }
    }

	protected function str_mid_replace($string) {
		if (! $string || !isset($string[1])) return $string;
		$len = strlen($string);
		$starNum = floor($len / 2); 
		$noStarNum = $len - $starNum;
		$leftNum = ceil($noStarNum / 2); 
		$starPos = $leftNum;
		for($i=0; $i<$starNum; $i++) $string[$starPos+$i] = '*';

		return $string;
	}

 
    protected function ajaxReturn($status=1, $msg='', $data='', $dialog='') {
        parent::ajaxReturn(array(
            'status' => $status,
            'msg' => $msg,
            'data' => $data,
            'dialog' => $dialog,
        ));
    }

	protected function jsonReturn($data,$type='JSON'){
    	header('Content-Type:application/json; charset=utf-8');
    	exit(json_encode($data));
    }
}