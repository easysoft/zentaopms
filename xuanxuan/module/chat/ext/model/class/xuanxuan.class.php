<?php
class xuanxuanChat extends chatModel
{
    public function downloadXXD($setting)
    {
        $setting->host = commonModel::getSysURL();
        $setting->key  = $this->config->xuanxuan->key;

        $agent = $this->app->loadClass('snoopy');
        $url   = "https://www.chanzhi.org/license-downloadxxd.html";
        $agent->cookies['lang'] = $this->cookie->lang;
        $agent->submit($url, $setting);
        $result = $agent->results;
        if($this->post->downloadType == 'config')
        {
            $this->loadModel('file')->sendDownHeader('xxd.conf', 'conf', $result, strlen($result));
        }
        else
        {
            header("Location: $result");
        }
        $this->loadModel('setting')->setItem('system.common.xxserver.installed', 1);
        exit;
    }
}
