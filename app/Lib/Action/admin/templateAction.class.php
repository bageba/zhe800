<?php
class templateAction extends BackendAction{

    public function index() {
        $config_file = CONF_PATH . 'index/config.php';
        $config = include $config_file;
        if ($dirname = $this->_get('dirname', 'trim')) {
            $config['DEFAULT_THEME'] = $dirname;
            file_put_contents($config_file, "<?php \nreturn " . var_export($config, true) . ";", LOCK_EX);
            $obj_dir = new Dir;
            is_dir(CACHE_PATH.'index/') && $obj_dir->delDir(CACHE_PATH.'index/');
            @unlink(RUNTIME_FILE);
        }
        $tpl_dir = TMPL_PATH.'index/';
        $opdir = dir($tpl_dir);
        $template_list = array();
        while (false !== ($entry = $opdir->read())) {
            if ($entry{0} == '.') {
                continue;
            }
            if (!is_file($tpl_dir . $entry . '/info.php')) {
                continue;
            }
            $info = include_once($tpl_dir . $entry . '/info.php');
            $info['preview'] = TMPL_PATH . 'index/' . $entry . '/preview.gif';
            $info['dirname'] = $entry;
            $template_list[$entry] = $info;
        }
        $this->assign('template_list',$template_list);
        $this->assign('def_tpl', $config['DEFAULT_THEME']);
        $this->display();
    }
}