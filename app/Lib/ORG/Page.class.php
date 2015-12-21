<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// |         lanfengye <zibin_5257@163.com>
// +----------------------------------------------------------------------

class Page {
    
    // 分页栏每页显示的页数
    public $rollPage = 10;
    // 分页地址
    public $path = '';
    // 页数跳转时要带的参数
    public $parameter  ;
    // 分页URL地址
    public $url     =   '';
    // 默认列表每页显示行数
    public $listRows = 20;
    // 起始行数
    public $firstRow    ;
    // 分页总页面数
    protected $totalPages  ;
    // 总行数
    protected $totalRows  ;
    // 当前页数
    protected $nowPage    ;
    // 分页的栏的总页数
    protected $coolPages   ;
    // 分页显示定制
    protected $config  =    array('header'=>'条记录','prev'=>'<上一页','next'=>'下一页>','first'=>'第一页','last'=>'最后一页','theme'=>'%totalRow% %header% %nowPage%/%totalPage% 页 %first% %upPage% %linkPage% %downPage% %end%');
    // 默认分页变量名
    protected $varPage;

    /**
     * 架构函数
     * @access public
     * @param array $totalRows  总的记录数
     * @param array $listRows  每页显示记录数
     * @param array $parameter  分页跳转的参数
     */
    public function __construct($totalRows,$listRows='',$parameter='',$url='') {
        $this->totalRows    =   $totalRows;
        $this->parameter    =   $parameter;
        $this->varPage      =   C('VAR_PAGE') ? C('VAR_PAGE') : 'p' ;
        if(!empty($listRows)) {
            $this->listRows =   intval($listRows);
        }
        $this->totalPages   =   ceil($this->totalRows/$this->listRows);     //总页数
        $this->nowPage      =   !empty($_GET[$this->varPage])?intval($_GET[$this->varPage]):1;
        if(!empty($this->totalPages) && $this->nowPage>$this->totalPages) {
            $this->nowPage  =   $this->totalPages;
        }
        $this->firstRow     =   $this->listRows*($this->nowPage-1);
    }

    public function setConfig($name,$value) {
        if(isset($this->config[$name])) {
            $this->config[$name]    =   $value;
        }
    }

    /**
     * 分页显示输出
     * @access public
     */
    public function show() {
        if(0 == $this->totalRows) return '';
        $p              =   $this->varPage;
        $middle = ceil($this->rollPage/2); //中间位置

        // 分析分页参数
        if($this->url){
            $depr       =   C('URL_PATHINFO_DEPR');
            $url        =   rtrim(U('/'.$this->url,'',false),$depr).$depr.'__PAGE__';
        }else{
            if($this->parameter && is_string($this->parameter)) {
                parse_str($this->parameter,$parameter);
            }elseif(empty($this->parameter)){
                unset($_GET[C('VAR_URL_PARAMS')]);
                if(empty($_GET)) {
                    $parameter  =   array();
                }else{
                    $parameter  =   $_GET;
                }
            }
            $parameter[$p]  =   '__PAGE__';
            $url            =   U($this->path,$parameter);
        }
        //上下翻页字符串
        $upRow          =   $this->nowPage-1;
        $downRow        =   $this->nowPage+1;
        if ($upRow>0){
            $upPage     =   "<a href='".str_replace('__PAGE__',$upRow,$url)."'>".$this->config['prev']."</a>";
        }else{
            $upPage     =   '';
        }

        if ($downRow <= $this->totalPages){
            $downPage   =   "<a href='".str_replace('__PAGE__',$downRow,$url)."'>".$this->config['next']."</a>";
        }else{
            $downPage   =   '';
        }

        // << < > >>
        $theFirst = $theEnd = '';
        if ($this->totalPages > $this->rollPage) {
            if($this->nowPage - $middle < 1){
                $theFirst   =   '';
            }else{
                $theFirst   =   "<a href='".str_replace('__PAGE__',1,$url)."' >".$this->config['first']."</a>";
            }
            if($this->nowPage + $middle > $this->totalPages){
                $theEnd     =   '';
            }else{
                $theEndRow  =   $this->totalPages;
                $theEnd     =   "<a href='".str_replace('__PAGE__',$theEndRow,$url)."' >".$this->config['last']."</a>";
            }
        }

        // 1 2 3 4 5
        $linkPage = "";
        if ($this->totalPages != 1) {
            if ($this->nowPage < $middle) { //刚开始
                $start = 1;
                $end = $this->rollPage;
            } elseif ($this->totalPages < $this->nowPage + $middle - 1) {
                $start = $this->totalPages - $this->rollPage + 1;
                $end = $this->totalPages;
            } else {
                $start = $this->nowPage - $middle + 1;
                $end = $this->nowPage + $middle - 1;
            }
            $start < 1 && $start = 1;
            $end > $this->totalPages && $end = $this->totalPages;
            for ($page = $start; $page <= $end; $page++) {
                if ($page != $this->nowPage) {
                    $linkPage .= " <a href='".str_replace('__PAGE__',$page,$url)."'>&nbsp;".$page."&nbsp;</a>";
                } else {
                    $linkPage .= " <span class='current'>".$page."</span>";
                }
            }
        }
        $pageStr     =   str_replace(
            array('%header%','%nowPage%','%totalRow%','%totalPage%','%upPage%','%downPage%','%first%','%linkPage%','%end%'),
            array($this->config['header'],$this->nowPage,$this->totalRows,$this->totalPages,$upPage,$downPage,$theFirst,$linkPage,$theEnd),$this->config['theme']);
        return $pageStr;
    }


	public function show_1() {
		$this->config = array('header'=>'条记录','prev'=>'','next'=>'','first'=>'第一页','last'=>'最后一页','theme'=>'%upPage%%linkPage%%end%%downPage%');
		if(0 == $this->totalRows) return "";
		//if(1 == $this->totalPages) return "<span class='unprev'></span><span class='current'>1</span><span class='unnext'></span>";
		if(1 == $this->totalPages) return "";
		$p = $this->varPage;
		$nowCoolPage      = ceil($this->nowPage/$this->rollPage);
		$url  =  $_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'],'?')?'':"?").$this->parameter;
		$parse = parse_url($url);
		if(isset($parse['query'])) {
			parse_str($parse['query'],$params);
			unset($params[$p]);
			$url   =  $parse['path'].'?'.http_build_query($params);
		}
		//上下翻页字符串
		$upRow   = $this->nowPage-1;
		$downRow = $this->nowPage+1;
		if ($upRow>0){
			$upPage="<a href='".$url."&".$p."=$upRow' class='prev'>".$this->config['prev']."</a>";
		}else{
			$upPage="<span class='unprev'>".$this->config['prev']."</span>";
		}

		if ($downRow <= $this->totalPages){
			$downPage="<a href='".$url."&".$p."=$downRow' class='next'>".$this->config['next']."</a>";
		}else{
			$downPage="<span class='unnext'>".$this->config['next']."</span>";
		}
		// << < > >>
		if($nowCoolPage == 1){
			$theFirst = "";
			$prePage = "";
		}else{
			$preRow =  $this->nowPage-$this->rollPage;
			$prePage = "<a href='".$url."&".$p."=$preRow' >上".$this->rollPage."页</a>";
			$theFirst = "<a href='".$url."&".$p."=1' >".$this->config['first']."</a>";
		}
		if($nowCoolPage == $this->coolPages){
			$nextPage = "";
			$theEnd="";
		}else{
			$nextRow = $this->nowPage+$this->rollPage;
			$theEndRow = $this->totalPages;
			$nextPage = "<a href='".$url."&".$p."=$nextRow' >下".$this->rollPage."页</a>";
			//$etc = "<span class='etc'></span>";
			$theEnd = "<a href='".$url."&".$p."=$theEndRow' >".$theEndRow."</a>";
		}
		// 1 2 3 4 5
		$linkPage = "";
		for($i=1;$i<=$this->rollPage;$i++){
			$page=($nowCoolPage-1)*$this->rollPage+$i;
			if($page!=$this->nowPage){
				if($page<=$this->totalPages){
					$linkPage .= "<a href='".$url."&".$p."=$page'>&nbsp;".$page."&nbsp;</a>";
				}else{
					break;
				}
			}else{
				if($this->totalPages != 1){
					$linkPage .= "<span class='current'>".$page."</span>";
				}
			}
		}
		$pageStr	 =	 str_replace(
		array('%header%','%nowPage%','%totalRow%','%totalPage%','%upPage%','%downPage%','%first%','%prePage%','%linkPage%','%nextPage%','%end%'),
		array($this->config['header'],$this->nowPage,$this->totalRows,$this->totalPages,$upPage,$downPage,$theFirst,$prePage,$linkPage,$nextPage,$theEnd),$this->config['theme']);
		return $pageStr;
	}

    /**
     * 分页显示输出
     * @access public
     */
    public function fshow() {
        if(0 == $this->totalRows) return '';
        $p              =   $this->varPage;
        $middle         =   ceil($this->rollPage/2); //中间位置

        // 分析分页参数
        if($this->url){
            $depr       =   C('URL_PATHINFO_DEPR');
            $url        =   rtrim(U('/'.$this->url,'',false),$depr).$depr.'__PAGE__';
        }else{
            if($this->parameter && is_string($this->parameter)) {
                parse_str($this->parameter,$parameter);
            }elseif(empty($this->parameter)){
                unset($_GET[C('VAR_URL_PARAMS')]);
                if(empty($_GET)) {
                    $parameter  =   array();
                }else{
                    $parameter  =   $_GET;
                }
            }
            $parameter[$p]  =   '__PAGE__';
            $url            =   U($this->path, $parameter);
        }
        //上下翻页字符串
        $upRow          =   $this->nowPage-1;
        $downRow        =   $this->nowPage+1;
        if ($upRow>0){
            $upPage     =   "<a href='".str_replace('__PAGE__',$upRow,$url)."'>".$this->config['prev']."</a>";
        }else{
            $upPage     =   '';
        }

        if ($downRow <= $this->totalPages){
            $downPage   =   "<a href='".str_replace('__PAGE__',$downRow,$url)."'>".$this->config['next']."</a>";
        }else{
            $downPage   =   '';
        }

        // << < > >>
        $theFirst = $theEnd = '';
        if ($this->totalPages > $this->rollPage) {
            if($this->nowPage - $middle < 1){
                $theFirst   =   '';
            }else{
                $theFirst   =   "<a href='".str_replace('__PAGE__',1,$url)."' >1</a> <i>...</i>";
            }
            if($this->nowPage + $middle > $this->totalPages){
                $theEnd     =   '';
            }else{
                $theEndRow  =   $this->totalPages;
                $theEnd     =   "<i>...</i> <a href='".str_replace('__PAGE__',$theEndRow,$url)."' >".$theEndRow."</a>";
            }
        }

        // 1 2 3 4 5
        $linkPage = "";
        if ($this->totalPages != 1) {
            if ($this->nowPage < $middle) { //刚开始
                $start = 1;
                $end = $this->rollPage;
            } elseif ($this->totalPages < $this->nowPage + $middle - 1) {
                $start = $this->totalPages - $this->rollPage + 1;
                $end = $this->totalPages;
            } else {
                $start = $this->nowPage - $middle + 1;
                $end = $this->nowPage + $middle - 1;
            }
            $start < 1 && $start = 1;
            $end > $this->totalPages && $end = $this->totalPages;
            for ($page = $start; $page <= $end; $page++) {
                if ($page != $this->nowPage) {
                    $linkPage .= " <a href='".str_replace('__PAGE__',$page,$url)."'>&nbsp;".$page."&nbsp;</a>";
                } else {
                    $linkPage .= " <span class='current'>".$page."</span>";
                }
            }
        }

        $pageStr     =   str_replace(
            array('%header%','%nowPage%','%totalRow%','%totalPage%','%upPage%','%downPage%','%first%','%linkPage%','%end%'),
            array($this->config['header'],$this->nowPage,$this->totalRows,$this->totalPages,$upPage,$downPage,$theFirst,$linkPage,$theEnd),$this->config['theme']);
        return $pageStr;
    }
    
    
    
    
    /**
     * 分页显示输出
     * @access public
     */
    public function kshow() {
        if(0 == $this->totalRows) return '';
        $p              =   $this->varPage;
        $middle         =   ceil($this->rollPage/2); //中间位置

        // 分析分页参数
        if($this->url){
            $depr       =   C('URL_PATHINFO_DEPR');
            $url        =   rtrim(U('/'.$this->url,'',false),$depr).$depr.'__PAGE__';
        }else{
            if($this->parameter && is_string($this->parameter)) {
                parse_str($this->parameter,$parameter);
            }elseif(empty($this->parameter)){
                unset($_GET[C('VAR_URL_PARAMS')]);
                if(empty($_GET)) {
                    $parameter  =   array();
                }else{
                    $parameter  =   $_GET;
                }
            }
            $parameter[$p]  =   '__PAGE__';
            $url            =   U($this->path, $parameter);
        }
        //上下翻页字符串
        $upRow          =   $this->nowPage-1;
        $downRow        =   $this->nowPage+1;
        if ($upRow>0){
            $upPage     =   "<a class='pg-prev'  href='".str_replace('__PAGE__',$upRow,$url)."'>".$this->config['prev']."</a>";
        }else{
            $upPage     =   '<span class="pg-prev">上一页</span>';
        }

        if ($downRow <= $this->totalPages){
            $downPage   =   "<a href='".str_replace('__PAGE__',$downRow,$url)."' class='pg-next'>下一页<em></em></a>";
        }else{
            $downPage   =   '<span class="pg-next">下一页<em></em></span>';
        }

        // << < > >>
        $theFirst = $theEnd = '';
        if ($this->totalPages > $this->rollPage) {
            if($this->nowPage - $middle < 1){
                $theFirst   =   '';
            }else{
                $theFirst   =   "<a href='".str_replace('__PAGE__',1,$url)."' >1</a> <i>...</i>";
            }
            if($this->nowPage + $middle > $this->totalPages){
                $theEnd     =   '';
            }else{
                $theEndRow  =   $this->totalPages;
                $theEnd     =   "<i>...</i> <a href='".str_replace('__PAGE__',$theEndRow,$url)."' >".$theEndRow."</a>";
            }
        }

        // 1 2 3 4 5
        $linkPage = "";
        if ($this->totalPages != 1) {
            if ($this->nowPage < $middle) { //刚开始
                $start = 1;
                $end = $this->rollPage;
            } elseif ($this->totalPages < $this->nowPage + $middle - 1) {
                $start = $this->totalPages - $this->rollPage + 1;
                $end = $this->totalPages;
            } else {
                $start = $this->nowPage - $middle + 1;
                $end = $this->nowPage + $middle - 1;
            }
            $start < 1 && $start = 1;
            $end > $this->totalPages && $end = $this->totalPages;
            for ($page = $start; $page <= $end; $page++) {
                if ($page != $this->nowPage) {
                    $linkPage .= " <a href='".str_replace('__PAGE__',$page,$url)."'>&nbsp;".$page."&nbsp;</a>";
                } else {
                    $linkPage .= " <span>".$page."</span>";
                }
            }
        }

        $pageStr     =   str_replace(
            array('%header%','%nowPage%','%totalRow%','%totalPage%','%upPage%','%downPage%','%first%','%linkPage%','%end%'),
            array($this->config['header'],$this->nowPage,$this->totalRows,$this->totalPages,$upPage,$downPage,$theFirst,$linkPage,$theEnd),$this->config['theme']);
        return $pageStr;
    }
    
    
    
    
    /**
     * 折800分页显示输出
     * @access public
     */
    public function zshow() {
        if(0 == $this->totalRows) return '';
        $p              =   $this->varPage;
        $middle         =   ceil($this->rollPage/2); //中间位置

        // 分析分页参数
        if($this->url){
            $depr       =   C('URL_PATHINFO_DEPR');
            $url        =   rtrim(U('/'.$this->url,'',false),$depr).$depr.'__PAGE__';
        }else{
            if($this->parameter && is_string($this->parameter)) {
                parse_str($this->parameter,$parameter);
            }elseif(empty($this->parameter)){
                unset($_GET[C('VAR_URL_PARAMS')]);
                if(empty($_GET)) {
                    $parameter  =   array();
                }else{
                    $parameter  =   $_GET;
                }
            }
            $parameter[$p]  =   '__PAGE__';
            $url            =   U($this->path, $parameter);
        }
        //上下翻页字符串
        $upRow          =   $this->nowPage-1;
        $downRow        =   $this->nowPage+1;
        if ($upRow>0){
            $upPage     =   "<span class='prev'><a href='".str_replace('__PAGE__',$upRow,$url)."'  rel='prev' style='width:100px;'>".$this->config['prev']."</a></span>";
        }else{
            $upPage     =   '';
        }

        if ($downRow <= $this->totalPages){
            $downPage   =   "<span class='next'><a href='".str_replace('__PAGE__',$downRow,$url)."' rel='next' style='width:100px;'>".$this->config['next']."</a></span>";
        }else{
            $downPage   =   '';
        }

        // << < > >>
        $theFirst = $theEnd = '';
        if ($this->totalPages > $this->rollPage) {
            if($this->nowPage - $middle < 1){
                $theFirst   =   '';
            }else{
                $theFirst   =   "<span class='page'><a href='".str_replace('__PAGE__',1,$url)."' >1</a></span> <span class='page gap'>...</span>";
            }
            if($this->nowPage + $middle > $this->totalPages){
                $theEnd     =   '';
            }else{
                $theEndRow  =   $this->totalPages;
                $theEnd     =   "<span class='page gap'>...</span> <span class='page'><a href='".str_replace('__PAGE__',$theEndRow,$url)."' >".$theEndRow."</a></span>";
            }
        }

        // 1 2 3 4 5
        $linkPage = "";
        if ($this->totalPages != 1) {
            if ($this->nowPage < $middle) { //刚开始
                $start = 1;
                $end = $this->rollPage;
            } elseif ($this->totalPages < $this->nowPage + $middle - 1) {
                $start = $this->totalPages - $this->rollPage + 1;
                $end = $this->totalPages;
            } else {
                $start = $this->nowPage - $middle + 1;
                $end = $this->nowPage + $middle - 1;
            }
            $start < 1 && $start = 1;
            $end > $this->totalPages && $end = $this->totalPages;
            for ($page = $start; $page <= $end; $page++) {
                if ($page != $this->nowPage) {
                    $linkPage .= " <span class='page'><a href='".str_replace('__PAGE__',$page,$url)."'>".$page."</a></span>";
                } else {
                    $linkPage .= " <span class='page current'>".$page."</span>";
                }
            }
        }

        $pageStr     =   str_replace(
            array('%header%','%nowPage%','%totalRow%','%totalPage%','%upPage%','%downPage%','%first%','%linkPage%','%end%'),
            array($this->config['header'],$this->nowPage,$this->totalRows,$this->totalPages,$upPage,$downPage,$theFirst,$linkPage,$theEnd),$this->config['theme']);
        return $pageStr;
    }


	
    /**
     * 手机版 分页 上下页 九块邮
     * @access public
     */
    public function mshow() {
        if(0 == $this->totalRows) return '';
        $p              =   $this->varPage;
        $middle         =   ceil($this->rollPage/2); //中间位置

        // 分析分页参数
        if($this->url){
            $depr       =   C('URL_PATHINFO_DEPR');
            $url        =   rtrim(U('/'.$this->url,'',false),$depr).$depr.'__PAGE__';
        }else{
            if($this->parameter && is_string($this->parameter)) {
                parse_str($this->parameter,$parameter);
            }elseif(empty($this->parameter)){
                unset($_GET[C('VAR_URL_PARAMS')]);
                if(empty($_GET)) {
                    $parameter  =   array();
                }else{
                    $parameter  =   $_GET;
                }
            }
            $parameter[$p]  =   '__PAGE__';
            $url            =   U($this->path, $parameter);
        }
        //上下翻页字符串
        $upRow          =   $this->nowPage-1;
        $downRow        =   $this->nowPage+1;
        if ($upRow>0){
            $upPage     =   "<a href='".str_replace('__PAGE__',$upRow,$url)."' class='prev'>".$this->config['prev']."</a>";
        }else{
            $upPage     =   "";
        }

        if ($downRow <= $this->totalPages){
            $downPage   =   "<a href='".str_replace('__PAGE__',$downRow,$url)."' class='next'>".$this->config['next']."</a>";
        }else{
            $downPage   =   "";
        }

		$theFirst = $theEnd = '';
        if ($this->totalPages > $this->rollPage) {
			$this->totalPages > 100;
			$this->totalPages = 100;
		}

		$theFirst = '<span class="num">'.$this->nowPage.'/'.$this->totalPages.'</span>';
		$theEnd ='';

        $linkPage = "";
        $pageStr     =   str_replace(
            array('%header%','%nowPage%','%totalRow%','%totalPage%','%upPage%','%downPage%','%first%','%linkPage%','%end%'),
            array($this->config['header'],$this->nowPage,$this->totalRows,$this->totalPages,$upPage,$downPage,$theFirst,$linkPage,$theEnd),$this->config['theme']);
        return $pageStr;
    }

}