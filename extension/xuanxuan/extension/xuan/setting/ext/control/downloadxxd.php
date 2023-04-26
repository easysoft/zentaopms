<?php
class setting extends control
{
    public function downloadXXD($type = '', $os = '')
    {
        if(in_array($type, array('config', 'package')))
        {
            $this->loadModel('im');
            $server = $this->im->getServer('zentao');
            if(strpos($server, '127.0.0.1') !== false) die(js::alert($this->lang->im->xxdServerError));

            $this->setting->setItem('system.common.xxserver.installed', 1);

            $setting     = $this->config->xuanxuan;
            $setting->os = $os;
            $result = $this->im->downloadXXD($setting, $type);
            if($result['result'] == 'success') $this->locate($result['message']);
            die($result['message']);
        }
        die("Params error.");
    }
}
