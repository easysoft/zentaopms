<?php
class admin extends control
{
    /**
     * Configuration of xuanxuan.
     *
     * @access public
     * @return void
     */
    public function xuanxuan($type = '')
    {
        $this->app->loadLang('chat');
        if($_POST)
        {
            $setting = fixer::input('post')->join('staff', ',')->remove('https')->get();
            $errors  = array();

            if(!is_numeric($setting->chatPort) or (int)$setting->chatPort <= 0 or (int)$setting->chatPort > 65535) $errors['chatPort'] = $this->lang->chat->xxdPortError;
            if(!is_numeric($setting->commonPort) or (int)$setting->commonPort <= 0 or (int)$setting->commonPort > 65535) $errors['commonPort'] = $this->lang->chat->xxdPortError;
            if($setting->isHttps == 'on')
            {
                if(empty($setting->sslcrt)) $errors['sslcrt'] = $this->lang->chat->errorSSLCrt;
                if(empty($setting->sslkey)) $errors['sslkey'] = $this->lang->chat->errorSSLKey;
            }

            if(strpos($setting->server, '127.0.0.1') !== false) $errors[] = $this->lang->chat->xxdServerError;
            if(strpos($setting->server, 'https://') !== 0 and strpos($setting->server, 'http://') !== 0) $errors[] = $this->lang->chat->xxdSchemeError;
            if(empty($setting->server)) $errors[] = $this->lang->chat->xxdServerEmpty;

            if($errors) $this->send(array('result' => 'fail', 'message' => $errors));

            $result = $this->loadModel('setting')->setItems('system.common.xuanxuan', $setting);
            if(!$result) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('xuanxuan')));
        }

        $os = 'win';
        if(strpos(strtolower(PHP_OS), 'win') !== 0) $os = strtolower(PHP_OS);

        $this->view->title     = $this->lang->chat->common;
        $this->view->adminList = $this->loadModel('user')->getPairs('admin');
        $this->view->os        = $os . '_' . php_uname('m');
        $this->view->type      = $type;
        $this->view->domain    = $this->loadModel('chat')->getServer('zentao');
        $this->view->turnon    = isset($this->config->xuanxuan->turnon) ? $this->config->xuanxuan->turnon : 1;
        $this->view->isHttps   = $this->config->xuanxuan->isHttps ? $this->config->xuanxuan->isHttps : 0;
        $this->display();
    }
}
