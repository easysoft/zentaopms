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
        $actions->createBug   = array('title' => "创建 Bug", 'url' => $baseURL . helper::createLink('bug', 'create', 'product=1', 'xhtml'), 'height' => "600px", 'width' => "800px");
        $actions->createDoc   = array('title' => "创建文档", 'url' => $baseURL . helper::createLink('doc', 'create', 'lib=1', 'xhtml'), 'height' => "600px", 'width' => "800px");
        $actions->createStory = array('title' => "创建需求", 'url' => $baseURL . helper::createLink('story', 'create', 'product=1', 'xhtml'), 'height' => "600px", 'width' => "800px");
        $actions->createTask  = array('title' => "创建任务", 'url' => $baseURL . helper::createLink('task', 'create', 'project=1', 'xhtml'), 'height' => "600px", 'width' => "800px");
        $actions->createTodo  = array('title' => "创建待办", 'url' => $baseURL . helper::createLink('todo', 'create', '', 'xhtml'), 'height' => "600px", 'width' => "800px"); 

        $urls['/bug-view-']   = true;
        $urls['/task-view-']  = true;
        $urls['/doc-view-']   = true;
        $urls['/story-view-'] = true;
        $urls['/todo-view-']  = true;

        $data = new stdClass();
        $data->entryID     = 1;
        $data->name        = 'zentao-integrated';
        $data->displayName = '禅道集成';
        $data->webViewUrl  = $baseURL . $this->config->webRoot;
        $data->download    = $baseURL . $this->config->webRoot . 'data/xuanxuan/zentao-integrated.zip';
        $data->md5         = md5_file($this->app->getDataRoot() . 'xuanxuan/zentao-integrated.zip');

        $data->data['actions']  = $actions;
        $data->data['urls']     = $urls;
        $data->data['entryUrl'] = $baseURL . $this->config->webRoot;

        $entries[] = $data;
        return $entries;
    }
}
