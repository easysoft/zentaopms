<?php
class admin extends control
{
    public function downloadXXD($type = '', $os = '')
    {
        if(in_array($type, array('config', 'package')))
        {
            $setting = $this->config->xuanxuan;
            $setting->os = $os;
            $this->loadModel('chat')->downloadXXD($setting, $type);
        }
        die("Params error.");
    }
}
