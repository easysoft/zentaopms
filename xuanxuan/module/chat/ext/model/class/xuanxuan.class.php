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
        foreach(array('bug', 'task', 'doc', 'story', 'todo', 'testcase') as $moduleName)
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
