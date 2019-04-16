<?php
class admin extends control
{
    public function downloadXXD($type = '', $os = '')
    {
        if(in_array($type, array('config', 'package')))
        {
            $this->loadModel('chat');
            $server = $this->chat->getServer('zentao');
            if(strpos($server, '127.0.0.1') !== false) die(js::alert($this->lang->chat->xxdServerError));

            $setting     = $this->config->xuanxuan;
            $setting->os = $os;
            $this->chat->downloadXXD('zentao', $setting, $type);
        }
        die("Params error.");
    }
}
