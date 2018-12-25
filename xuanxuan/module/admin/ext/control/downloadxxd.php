<?php
class admin extends control
{
    public function downloadXXD($type = '', $os = '')
    {
        if(in_array($type, array('config', 'package')))
        {
            $this->loadModel('mail');
            $this->loadModel('chat');
            $domain = empty($this->config->mail->domain) ? commonModel::getSysURL() : $this->config->mail->domain;
            if(!empty($this->config->xuanxuan->server)) $domain = $this->config->xuanxuan->server;
            if(strpos($domain, '127.0.0.1') !== false) die(js::alert($this->lang->chat->xxdServerError));
            if(empty($domain)) die(js::alert($this->lang->chat->xxdServerEmpty));

            $setting     = $this->config->xuanxuan;
            $setting->os = $os;
            $this->chat->downloadXXD($setting, $type);
        }
        die("Params error.");
    }
}
