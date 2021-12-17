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

    public function messageGetNotifyList()
    {
        $onlineUsers = $this->loadModel('im')->userGetList('online');
        if(empty($onlineUsers)) return array();
        $onlineUsers = array_keys($onlineUsers);

        $messageUserPairsData = $this->dao->select('message,user')->from(TABLE_IM_MESSAGESTATUS)
                            ->where('status')->eq('waiting')
                            ->andWhere('user')->in($onlineUsers)
                            ->fetchAll();
        if(empty($messageUserPairsData)) return array();

        $messageUserPairs = array();
        foreach($messageUserPairsData as $data)
        {
            if(isset($messageUserPairs[$data->message]))
            {
                $messageUserPairs[$data->message][] = $data->user;
                continue;
            }
            $messageUserPairs[$data->message] = array($data->user);
        }
        $notifyMessages = $this->im->messageGetList('', array_keys($messageUserPairs), null, '', 'notify', false);
        if(empty($notifyMessages)) return array();

        $messageIDs = array();
        foreach($notifyMessages as $message) $messageIDs[] = $message->id;
        $messageUserPairs = array_intersect_key($messageUserPairs, array_flip($messageIDs));

        $notifications = $this->im->messageFormatNotify($notifyMessages);
        $data          = array();
        $messages      = array();
        foreach($notifications as $message)
        {
            foreach($messageUserPairs[$message->id] as $userID)
            {
                $messages[$userID][] = $message->id;
                $data[$userID][]     = $message;
            }
        }

        foreach($messages as $userID => $message)
        {
            $this->dao->delete()->from(TABLE_IM_MESSAGESTATUS)
                ->where('message')->in($message)
                ->andWhere('user')->eq($userID)
                ->exec();
        }
        return $this->mergeNotifications($data);
    }

    /**
     * Merge notifications with same actor, objectType, actionType.
     *
     * @param  array  $notificationData
     * @access public
     * @return array
     */
    public function mergeNotifications($notificationData)
    {
        /* Notification data: array($userID1 => array($notification1, $notification2), $userID2 => array($notification3)) */
        foreach($notificationData as $userID => $userMessages)
        {
            $messageGroups = array();
            foreach($userMessages as $message)
            {
                /* Group by $message->content->content->objectType, ...->parentType, ...->action, ...->actor */
                $contentData = json_decode($message->content);
                $contentData = json_decode($contentData->content);
                $messageGroups["$contentData->objectType-$contentData->parentType-$contentData->action-$contentData->actor"][] = $message;
            }
            foreach($messageGroups as $groupKey => $messages)
            {
                if(count($messages) < 2) continue;

                $notification = current($messages);
                array_shift($messages);
                $notificationContent = json_decode($notification->content);
                $notificationInnerContent = json_decode($notificationContent->content);

                /* Inner content: array($parentID => array($content1, $content2)) */
                $objectGroups = array($notificationInnerContent->parent => array($notificationInnerContent));
                foreach($messages as $message)
                {
                    $messageContent = json_decode($message->content);
                    $messageInnerContent = json_decode($messageContent->content);
                    $objectGroups[$messageInnerContent->parent][] = $messageInnerContent;
                }
                $objectTotal = 0;
                foreach($objectGroups as $parent => $objectGroup)
                {
                    $object = current($objectGroup);
                    $object->count = count($objectGroup);
                    $object->url   = $object->parentURL;
                    unset($object->title);

                    $objectGroups[$parent] = $object;
                    $objectTotal += $object->count;
                }
                $notificationContent->content = json_encode(array_values($objectGroups));
                /* Hack alert: title count replacement currently assumes that default count is 1. */
                $notification->title = substr_replace($notification->title, "$objectTotal", strrpos($notification->title, '1'), 1);
                $notificationContent->title = $notification->title;

                $notification->content = json_encode($notificationContent);
                $messageGroups[$groupKey] = array($notification);
            }

            $mergedMessages = array();
            $userMessageGroups = array_values($messageGroups);
            foreach($userMessageGroups as $messageGroup) $mergedMessages = array_merge($mergedMessages, $messageGroup);
            $notificationData[$userID] = $mergedMessages;
        }

        return $notificationData;
    }
}
