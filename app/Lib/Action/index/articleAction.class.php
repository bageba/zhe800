<?php

class articleAction extends FirstendAction {


	public function index(){
		$cid = I('cid','', 'trim');
		$title = '文章阅读';
		$p = I('p',1, 'intval');
		$map['status']="1";
		if($cid){
            $map['cate_id'] = $cid;
			$article_cate = M('article_cate')->field('id,name')->find($cid);
			$title = $article_cate['name'];
        }
		$page_size = 20;
		$start = $page_size * ($p - 1) ;
		$order = 'ordid asc ';
		$order.= ', id DESC';
		$article_mod = M('article');
        $article_list = $article_mod->where($map)->order($order)->limit($start . ',' . $page_size)->select();

		$help_mod = M('help');
		$help_list = $help_mod->select();

		$article_cate_mod = M('article_cate');
		$article_cate_list = $article_cate_mod->select();

		$this->assign('cid',$cid);
		$this->assign('title',$title);
		$this->assign('article_list',$article_list);
		$this->assign('help_list',$help_list);
		$this->assign('article_cate_list',$article_cate_list);
		$count = $article_mod->where($map)->count();
        $pager = $this->_pager($count, $page_size);
        $this->assign('page', $pager->fshow());
		$page_seo=array(
			'title' => $title.' - '.C('ftx_site_name'),
        );
		$this->assign('page_seo', $page_seo);
        $this->display();
	}

	public function cate(){
		$cid = I('id','', 'trim');
		$title = '文章阅读';
		$p = I('p',1, 'intval');
		$map['status']="1";
		if($cid){
            $map['cate_id'] = $cid;
			$article_cate = M('article_cate')->field('id,name')->find($cid);
			$title = $article_cate['name'];
        }
		$page_size = 20;
		$start = $page_size * ($p - 1) ;
		$order = 'ordid asc ';
		$order.= ', id DESC';
		$article_mod = M('article');
        $article_list = $article_mod->where($map)->order($order)->limit($start . ',' . $page_size)->select();

		$help_mod = M('help');
		$help_list = $help_mod->select();

		$article_cate_mod = M('article_cate');
		$article_cate_list = $article_cate_mod->select();

		$this->assign('cid',$cid);
		$this->assign('title',$title);
		$this->assign('article_list',$article_list);
		$this->assign('help_list',$help_list);
		$this->assign('article_cate_list',$article_cate_list);
		$count = $article_mod->where($map)->count();
        $pager = $this->_pager($count, $page_size);
        $this->assign('page', $pager->fshow());
		$page_seo=array(
			'title' => $title.' - '.C('ftx_site_name'),
        );
		$this->assign('page_seo', $page_seo);
        $this->display();
	}

	public function read(){
		$id = I('id','', 'intval');
        !$id && $this->_404();
        $article_mod = M('article');
		D('article')->hits($id);
        $article = $article_mod->field('id,title,info')->find($id);
		$this->_config_seo(C('ftx_seo_config.article'), array(
            'title' => $article['title'],
        ));
        $this->assign('article',$article); //分类选中
        $this->display();
	}

}