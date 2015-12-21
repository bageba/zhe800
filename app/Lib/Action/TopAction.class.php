<?php
/**
 * 基本控制器
 *
 */
class TopAction extends FuncAction{
    protected function _initialize() {
        //消除所有的magic_quotes_gpc转义
        Input::noGPC();
        //初始化网站配置
        if (false === $setting = F('setting')) {
            $setting = D('setting')->setting_cache();
        }
        C($setting);
        //发送邮件
        $this->assign('async_sendmail', session('async_sendmail'));
    }
     
    public function _empty() {
        $this->_404();
    }
    
    protected function _404($url = '') {
        if ($url) {
            redirect($url);
        } else {
            send_http_status(404);
            $this->display(TMPL_PATH . '404.html');
            exit;
        }
    }

	

	public function ftx_get_domain(){
		return  isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
	}
	public function gethost($ftx_host){
		$hosts=explode("||",$this->geturls($this->geturls($this->geturls($this->geturls(base64_decode($this->geturls($this->geturls($this->geturls($this->geturls($ftx_host))))))))));
		return $hosts;
	}

	public function geturls($txt){
		$domain="bbs.yangtata.com";
		$txt=base64_decode($txt);
		$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_.";
		$ikey ="ZWm2G9gq3kRIxsZ6_vr0Bo.pOrmftxiaTOPbear-x6g6";
		$knum = 0;$i = 0;
		$tlen = strlen($txt);
		while(isset($domain{$i})) $knum +=ord($domain{$i++});
		$ch1 = $txt{$knum % $tlen};
		$nh1 = strpos($chars,$ch1);
		$txt = substr_replace($txt,'',$knum % $tlen--,1);
		$ch2 = $txt{$nh1 % $tlen};
		$nh2 = strpos($chars,$ch2);
		$txt = substr_replace($txt,'',$nh1 % $tlen--,1);
		$ch3 = $txt{$nh2 % $tlen};
		$nh3 = strpos($chars,$ch3);
		$txt = substr_replace($txt,'',$nh2 % $tlen--,1);
		$nhnum = $nh1 + $nh2 + $nh3;
		$mdKey = substr(md5(md5(md5($domain.$ch1).$ch2.$ikey).$ch3),$nhnum % 8,$knum % 8 + 16);
		$tmp = '';
		$j=0; $k = 0;
		$tlen = strlen($txt);
		$klen = strlen($mdKey);
		for ($i=0; $i<$tlen; $i++) {
		  $k = $k == $klen ? 0 : $k;
		  $j = strpos($chars,$txt{$i})-$nhnum - ord($mdKey{$k++});
		  while ($j<0) $j+=64;
		  $tmp .= $chars{$j};
		}
		$tmp = str_replace(array('-','_','.'),array('+','/','='),$tmp);
		return trim(base64_decode($tmp));
	}


}
?>