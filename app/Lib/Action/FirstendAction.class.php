<?php
/**
 * 前台控制器基类
 */
class FirstendAction extends TopAction {

    protected $visitor = null;
	
    public function _initialize() {
        parent::_initialize();
        //网站状态
        if (!C('ftx_site_status')) {
            header('Content-Type:text/html; charset=utf-8');
            exit(C('ftx_closed_reason'));
        }
        $this->_init_visitor();
        $this->_assign_oauth();
        $this->assign('nav_curr', '');
        $cate_data = D('items_cate')->cate_data_cache();
        $this->assign('cate_data', $cate_data);
    }
    
    /**
    * 初始化访问者
    */
    private function _init_visitor() {
        $this->visitor = new user_visitor();
        $this->assign('visitor', $this->visitor->info);
    }

    /**
     * 第三方登陆模块
     */
    private function _assign_oauth() {
        if (false === $oauth_list = F('oauth_list')) {
            $oauth_list = D('oauth')->oauth_cache();
        }
        $this->assign('oauth_list', $oauth_list);
    }

    /**
     * SEO设置
     */
    protected function _config_seo($seo_info = array(), $data = array()) {
        $page_seo = array(
            'title' => C('ftx_site_title'),
            'keywords' => C('ftx_site_keyword'),
            'description' => C('ftx_site_description')
        );
        $page_seo = array_merge($page_seo, $seo_info);
        //开始替换
        $searchs = array('{site_name}', '{site_title}', '{site_keywords}', '{site_description}');
        $replaces = array(C('ftx_site_name'), C('ftx_site_title'), C('ftx_site_keyword'), C('ftx_site_description'));
        preg_match_all("/\{([a-z0-9_-]+?)\}/", implode(' ', array_values($page_seo)), $pageparams);
        if ($pageparams) {
            foreach ($pageparams[1] as $var) {
                $searchs[] = '{' . $var . '}';
                $replaces[] = $data[$var] ? strip_tags($data[$var]) : '';
            }
            //符号
            $searchspace = array('((\s*\-\s*)+)', '((\s*\,\s*)+)', '((\s*\|\s*)+)', '((\s*\t\s*)+)', '((\s*_\s*)+)');
            $replacespace = array('-', ',', '|', ' ', '_');
            foreach ($page_seo as $key => $val) {
                $page_seo[$key] = trim(preg_replace($searchspace, $replacespace, str_replace($searchs, $replaces, $val)), ' ,-|_');
            }
        }
        $this->assign('page_seo', $page_seo);
    }

    /**
    * 连接用户中心
    */
    protected function _user_server() {
        $passport = new passport(C('ftx_integrate_code'));
        return $passport;
    }

    /**
     * 前台分页统一
     */
    protected function _pager($count, $pagesize) {
        $pager = new Page($count, $pagesize);
        $pager->rollPage = 5;
		$pager->setConfig('header','条记录');
        $pager->setConfig('prev', '上一页');
		$pager->setConfig('next', '下一页');
		$pager->setConfig('first', '第一页');
		$pager->setConfig('last', '最后一页');
        $pager->setConfig('theme', '%upPage% %first% %linkPage% %end% %downPage%');
        return $pager;
    }
}
?>