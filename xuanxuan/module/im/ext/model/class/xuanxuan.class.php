<?php
class xuanxuanIm extends imModel
{
    public function getExtensionList($userID)
    {
        $clientLang = $this->app->input['lang'];
        $this->app->clientLang = $clientLang;
        $this->app->loadLang('im');

        $entries = array();
        $baseURL = $this->getServer('zentao');

        $this->loadModel('user');
        $user = $this->dao->select('*')->from(TABLE_USER)->where('id')->eq($userID)->fetch();
        $user->admin  = strpos($this->app->company->admins, ",{$user->account},") !== false;
        $user->rights = $this->user->authorize($user->account);
        $user->groups = $this->user->getGroups($user->account);
        $user->view   = $this->user->grantUserView($user->account, $user->rights['acls']);

        $this->session->set('user', $user);
        $this->app->user = $this->session->user;

        $products    = $this->loadModel('product')->getPairs();
        $executions  = $this->loadModel('execution')->getPairs();
        $products    = empty($products) ? array() : array_keys($products);
        $executions  = empty($executions) ? array() : array_keys($executions);
        $libIdList   = array_keys($this->loadModel('doc')->getLibs('all'));
        $productID   = isset($products[0])   ? $products[0]   : 1;
        $executionID = isset($executions[0]) ? $executions[0] : 1;
        $libID       = isset($libIdList[0])  ? $libIdList[0]  : 1;

        $actions = new stdclass();
        if(common::hasPriv('bug',   'create') and !empty($products))  $actions->createBug   = array('title' => $this->lang->im->createBug,   'url' => $baseURL . str_replace('/x.php', '/index.php', helper::createLink('bug', 'create', "product=$productID", 'xhtml')), 'height' => "600px", 'width' => "800px");
        if(common::hasPriv('story', 'create') and !empty($products))  $actions->createStory = array('title' => $this->lang->im->createStory, 'url' => $baseURL . str_replace('/x.php', '/index.php', helper::createLink('story', 'create', "product=$productID", 'xhtml')), 'height' => "600px", 'width' => "800px");
        if(common::hasPriv('task',  'create') and !empty($executions))$actions->createTask  = array('title' => $this->lang->im->createTask,  'url' => $baseURL . str_replace('/x.php', '/index.php', helper::createLink('task', 'create', "execution=$executionID", 'xhtml')), 'height' => "600px", 'width' => "800px");
        if(common::hasPriv('doc',   'create') and !empty($libIdList)) $actions->createDoc   = array('title' => $this->lang->im->createDoc,   'url' => $baseURL . str_replace('/x.php', '/index.php', helper::createLink('doc', 'create', "objectType=&objectID=0&libID=0", 'xhtml')), 'height' => "600px", 'width' => "800px");
        if(common::hasPriv('todo',  'create')) $actions->createTodo = array('title' => $this->lang->im->createTodo,  'url' => $baseURL . str_replace('/x.php', '/index.php', helper::createLink('todo', 'create', '', 'xhtml')), 'height' => "600px", 'width' => "800px");

        $urls = array();
        foreach($this->config->im->cards as $moduleName => $methods)
        {
            foreach($methods as $methodName => $size)
            {
                if($this->config->requestType == 'GET')
                {
                    $url = $this->config->webRoot . "index.php?m={$moduleName}&f={$methodName}";
                }
                else
                {
                    $url = $this->config->webRoot . "{$moduleName}-{$methodName}-";
                }
                $urls[$url] = $size;
            }
        }

        $data = new stdClass();
        $data->entryID     = 1;
        $data->name        = 'zentao-integrated';
        $data->displayName = $this->lang->im->zentaoIntegrate;
        $data->webViewUrl  = trim($baseURL . $this->config->webRoot, '/');
        $data->download    = $baseURL . $this->config->webRoot . 'data/xuanxuan/zentao-integrated.zip';
        $data->md5         = md5_file($this->app->getDataRoot() . 'xuanxuan/zentao-integrated.zip');

        $data->data['actions']  = $actions;
        $data->data['urls']     = $urls;
        $data->data['entryUrl'] = trim($baseURL . $this->config->webRoot, '/');

        $entries[] = $data;
        unset($_SESSION['user']);
        return $entries;
    }

    public function getServer($backend = 'xxb')
    {
        $server = commonModel::getSysURL();
        if(!empty($this->config->xuanxuan->server))
        {
            $host     = $this->server->http_host;
            $position = strrpos($host, ':');
            $port     = $position === false ? '' : substr($host, $position + 1);
            $server   = $this->config->xuanxuan->server;
            if($port and strpos($server, ":") === false)
            {
                $server = rtrim($server, '/');
                $server = "{$server}:{$port}";
            }
        }
        $server = rtrim($server, '/');

        return $server;
    }

    public function uploadFile($fileName, $path, $size, $time, $userID, $users, $chat)
    {
        $user      = $this->userGetByID($userID);
        $extension = $this->loadModel('file')->getExtension($fileName);

        $file = new stdclass();
        $file->pathname    = $path;
        $file->title       = rtrim($fileName, ".$extension");
        $file->extension   = $extension;
        $file->size        = $size;
        $file->objectType  = 'chat';
        $file->objectID    = $chat->id;
        $file->addedBy     = !empty($user->account) ? $user->account : '';
        $file->addedDate   = date(DT_DATETIME1, $time);

        $this->dao->insert(TABLE_FILE)->data($file)->exec();

        $fileID = $this->dao->lastInsertID();
        $path  .= md5($fileName . $fileID . $time);
        $this->dao->update(TABLE_FILE)->set('pathname')->eq($path)->where('id')->eq($fileID)->exec();

        return $fileID;
    }

    public function chatAddAction($chatId = '', $action = '', $actorId = '', $result = '', $comment = '')
    {
        return;
    }

    public function userAddAction($user, $actionType, $result, $comment = '', $common = false)
    {
        if(!zget($this->config->xuanxuan, 'logLevel', 1) && !$common) return;

        $account = '';
        $userID  = 0;
        if(is_int($user))
        {
            $account = $this->dao->select('account')->from(TABLE_USER)->where('id')->eq($user)->fetch('account');
            $userID  = $user;
        }
        if(is_string($user))
        {
            $userID  = $this->dao->select('id')->from(TABLE_USER)->where('account')->eq($user)->fetch('id');
            $account = $user;
        }

        $actor   = !empty($account) ? $account : '';
        $extra   = json_encode(array('actorId' => $userID));
        $this->loadModel('action')->create('user', $userID, $actionType, $comment, $extra, $actor);
    }
}
