<?php
class xuanxuanChat extends chatModel
{
    public function downloadXXD($setting, $type)
    {
        $data = new stdClass();
        $data->server         = $setting->server;
        $data->uploadFileSize = $setting->uploadFileSize;
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

        $url   = "https://www.chanzhi.org/license-downloadxxd.html";
        $agent = $this->app->loadClass('snoopy');
        $agent->cookies['lang'] = $this->cookie->lang;
        $agent->submit($url, $data);
        $result = $agent->results;
        
        if($type == 'config')
        {
            $this->sendDownHeader('xxd.conf', 'conf', $result, strlen($result));
        }
        else
        {
            header("Location: $result");
        }
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
        $actions->createBug   = array('title' => $this->lang->chat->createBug,   'url' => $baseURL . helper::createLink('bug', 'create', 'product=1', 'xhtml'),   'height' => "600px", 'width' => "800px");
        $actions->createDoc   = array('title' => $this->lang->chat->createDoc,   'url' => $baseURL . helper::createLink('doc', 'create', 'lib=1', 'xhtml'),       'height' => "600px", 'width' => "800px");
        $actions->createStory = array('title' => $this->lang->chat->createStory, 'url' => $baseURL . helper::createLink('story', 'create', 'product=1', 'xhtml'), 'height' => "600px", 'width' => "800px");
        $actions->createTask  = array('title' => $this->lang->chat->createTask,  'url' => $baseURL . helper::createLink('task', 'create', 'project=1', 'xhtml'),  'height' => "600px", 'width' => "800px");
        $actions->createTodo  = array('title' => $this->lang->chat->createTodo,  'url' => $baseURL . helper::createLink('todo', 'create', '', 'xhtml'),           'height' => "600px", 'width' => "800px");

        $urls = array();
        foreach(array('bug', 'task', 'doc', 'story', 'testcase') as $moduleName)
        {
            $url = '/';
            if($this->config->requestType == 'GET')
            {
                $url .= "index.php?m={$moduleName}&f=view";
            }
            else
            {
                $url .= "{$moduleName}-view-";
            }
            $urls[$url] = true;
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
