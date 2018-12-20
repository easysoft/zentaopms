<?php
class xuanxuanChat extends chatModel
{
    public function downloadXXD($setting, $type)
    {
        $data = new stdClass();
        $data->server         = $setting->server;
        $data->uploadFileSize = $setting->uploadFileSize;
        $data->isHttps        = $setting->isHttps;
        $data->sslcrt         = $setting->sslcrt;
        $data->sslkey         = $setting->sslkey;
        $data->ip             = $setting->ip;
        $data->chatPort       = $setting->chatPort;
        $data->commonPort     = $setting->commonPort;
        $data->maxOnlineUser  = isset($setting->maxOnlineUser) ? $setting->maxOnlineUser : 0;
        $data->host           = commonModel::getSysURL() . getWebRoot();
        $data->key            = $this->config->xuanxuan->key;
        $data->os             = $setting->os;
        $data->version        = $this->config->xuanxuan->version;
        $data->downloadType   = $type;

        $url    = "https://www.chanzhi.org/license-downloadxxd.html";
        $result = common::http($url, $data);
        
        if($type == 'config')
        {
            $this->sendDownHeader('xxd.conf', 'conf', $result, strlen($result));
        }
        else
        {
            header("Location: $result");
        }

        $this->loadModel('setting')->setItem('system.common.xxserver.installed', 1);
        exit;
    }

    public function sendDownHeader($fileName, $fileType, $content, $fileSize = 0)
    {
        /* Set the downloading cookie, thus the export form page can use it to judge whether to close the window or not. */
        setcookie('downloading', 1, 0, '', '', false, true);

        /* Append the extension name auto. */
        $extension = '.' . $fileType;
        if(strpos($fileName, $extension) === false) $fileName .= $extension;

        /* urlencode the fileName for ie. */
        $isIE11 = (strpos($this->server->http_user_agent, 'Trident') !== false and strpos($this->server->http_user_agent, 'rv:11.0') !== false); 
        if(strpos($this->server->http_user_agent, 'MSIE') !== false or $isIE11) $fileName = urlencode($fileName);

        /* Judge the content type. */
        $mimes = $this->config->chat->mimes;
        $contentType = isset($mimes[$fileType]) ? $mimes[$fileType] : $mimes['default'];
        if(empty($fileSize) and $content) $fileSize = strlen($content);

        header("Content-type: $contentType");
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header("Content-length: {$fileSize}");
        header("Pragma: no-cache");
        header("Expires: 0");
        die($content);
    }

    public function getExtensionList($userID)
    {
        $entries = array();
        $baseURL = commonModel::getSysURL();

        $actions = new stdclass();
        $actions->createBug   = array('title' => $this->lang->chat->createBug,   'url' => $baseURL . str_replace('/xuanxuan.php', '/index.php', helper::createLink('bug', 'create', 'product=1', 'xhtml')),   'height' => "600px", 'width' => "800px");
        $actions->createDoc   = array('title' => $this->lang->chat->createDoc,   'url' => $baseURL . str_replace('/xuanxuan.php', '/index.php', helper::createLink('doc', 'create', 'lib=1', 'xhtml')),       'height' => "600px", 'width' => "800px");
        $actions->createStory = array('title' => $this->lang->chat->createStory, 'url' => $baseURL . str_replace('/xuanxuan.php', '/index.php', helper::createLink('story', 'create', 'product=1', 'xhtml')), 'height' => "600px", 'width' => "800px");
        $actions->createTask  = array('title' => $this->lang->chat->createTask,  'url' => $baseURL . str_replace('/xuanxuan.php', '/index.php', helper::createLink('task', 'create', 'project=1', 'xhtml')),  'height' => "600px", 'width' => "800px");
        $actions->createTodo  = array('title' => $this->lang->chat->createTodo,  'url' => $baseURL . str_replace('/xuanxuan.php', '/index.php', helper::createLink('todo', 'create', '', 'xhtml')),           'height' => "600px", 'width' => "800px");

        $urls = array();
        foreach($this->config->chat->cards as $moduleName => $methods)
        {
            foreach($methods as $methodName => $size)
            {
                if($this->config->requestType == 'GET')
                {
                    $url = "/index.php?m={$moduleName}&f={$methodName}";
                }
                else
                {
                    $url = "/{$moduleName}-{$methodName}-";
                }
                $urls[$url] = $size;
            }
        }

        $data = new stdClass();
        $data->entryID     = 1;
        $data->name        = 'zentao-integrated';
        $data->displayName = $this->lang->chat->zentaoIntegrate;
        $data->webViewUrl  = trim($baseURL . $this->config->webRoot, '/');
        $data->download    = $baseURL . $this->config->webRoot . 'data/xuanxuan/zentao-integrated.zip';
        $data->md5         = md5_file($this->app->getDataRoot() . 'xuanxuan/zentao-integrated.zip');

        $data->data['actions']  = $actions;
        $data->data['urls']     = $urls;
        $data->data['entryUrl'] = trim($baseURL . $this->config->webRoot, '/');

        $entries[] = $data;
        return $entries;
    }
}
