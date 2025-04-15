<?php
class xuanxuanMessage extends messageModel
{
    public function send(string $objectType, int $objectID, string $actionType, int $actionID, string $actor = '', string $extra = ''): void
    {
        f(commonModel::isTutorialMode()) return;

        $messageSetting = $this->config->message->setting;
        if(is_string($messageSetting)) $messageSetting = json_decode($messageSetting, true);
        if($objectType == 'instance' && $actionType == 'monitor') return;
        if(isset($messageSetting['xuanxuan']))
        {
            $messageActions = $messageSetting['xuanxuan']['setting'];
            if(isset($messageActions[$objectType]) and in_array($actionType, $messageActions[$objectType]))
            {
                $this->loadModel('action');

                $field = 'obj.*';
                if($objectType == 'task')     $field = 'obj.*,project.name as projectName,execu.name as execuName';
                if($objectType == 'story')    $field = 'obj.*,product.name as productName';
                if($objectType == 'bug')      $field = 'obj.*,project.name as projectName,product.name as productName,execu.name as execuName';
                if($objectType == 'feedback') $field = 'obj.*,product.name as productName';
                if($objectType == 'demand')   $field = 'obj.*,pool.name as poolName';

                $object = $this->dao->select($field)->from($this->config->objectTables[$objectType])->alias('obj')
                    ->beginIF($objectType == 'task')
                    ->leftJoin($this->config->objectTables['project'])->alias('project')->on('project.id = obj.project')
                    ->leftJoin($this->config->objectTables['execution'])->alias('execu')->on('execu.id = obj.execution')
                    ->fi()
                    ->beginIF($objectType == 'story')
                    ->leftJoin($this->config->objectTables['product'])->alias('product')->on('product.id = obj.product')
                    ->fi()
                    ->beginIF($objectType == 'bug')
                    ->leftJoin($this->config->objectTables['project'])->alias('project')->on('project.id = obj.project')
                    ->leftJoin($this->config->objectTables['execution'])->alias('execu')->on('execu.id = obj.execution')
                    ->leftJoin($this->config->objectTables['product'])->alias('product')->on('product.id = obj.product')
                    ->fi()
                    ->beginIF($objectType == 'feedback')
                    ->leftJoin($this->config->objectTables['product'])->alias('product')->on('product.id = obj.product')
                    ->fi()
                    ->beginIF($objectType == 'demand')
                    ->leftJoin($this->config->objectTables['demandpool'])->alias('pool')->on('pool.id = obj.pool')
                    ->fi()
                    ->where('obj.id')->eq($objectID)
                    ->fetch();
                $field = $this->config->action->objectNameFields[$objectType];
                $title = $objectType == 'mr' ? '' : sprintf($this->lang->message->notifyTitle, $this->app->user->realname, $this->lang->action->label->$actionType, 1, $this->lang->action->objectTypes[$objectType]);
                if($objectType == 'story' && $actionType == 'reviewed' && !empty($extra))
                {
                    $notifyExtra = explode(',', $extra);
                    $notifyType  = strtolower($notifyExtra[0]);

                    if($notifyType == 'pass')    $title = sprintf($this->lang->message->notifyPassTitle,    $this->app->user->realname, 1);
                    if($notifyType == 'clarify') $title = sprintf($this->lang->message->notifyClarifyTitle, $this->app->user->realname, 1);
                    if($notifyType == 'reject')  $title = sprintf($this->lang->message->notifyRejectTitle,  $this->app->user->realname, 1);
                }

                if($objectType == 'feedback' && ($actionType == 'tobug' || $actionType == 'tostory' || $actionType == 'totask' || $actionType == 'todo'))
                {
                    if($actionType == 'tobug')   $title = sprintf($this->lang->message->feedbackToBugTitle,   $this->app->user->realname, 1);
                    if($actionType == 'tostory') $title = sprintf($this->lang->message->feedbackToStoryTitle, $this->app->user->realname, 1);
                    if($actionType == 'totask')  $title = sprintf($this->lang->message->feedbackToTaskTitle,  $this->app->user->realname, 1);
                    if($actionType == 'todo')    $title = sprintf($this->lang->message->feedbackToDoTitle,    $this->app->user->realname, 1);
                }

                $server = $this->loadModel('im')->getServer('zentao');
                $dataID = $objectType == 'kanbancard' ? $object->kanban : $objectID;
                $url    = $server . helper::createLink($objectType == 'kanbancard' ? 'kanban' : $objectType, 'view', "id=$dataID", 'html');

                $target = '';
                if($objectType == 'feedback')
                {
                    $feedback   = $this->loadModel('feedback')->getByID($objectID);
                    $senderUser = $this->feedback->getToAndCcList($feedback);
                    if(is_array($senderUser) && !empty($senderUser))
                    {
                        foreach($senderUser as $user) $target .= ',' . $user;
                    }
                }
                elseif($objectType == 'demandpool')
                {
                    if(!empty($object->owner))    $target .= trim($object->owner, ',');
                    if(!empty($object->reviewer)) $target .= ',' . trim($object->reviewer, ',');
                }
                elseif($objectType == 'demand')
                {
                    $target .= $object->createdBy;
                    if(!empty($object->assignedTo)) $target .= $object->assignedTo == 'closed' ? '' : $object->assignedTo;
                    if(!empty($object->mailto))     $target .= ",{$object->mailto}";
                    $reviewers = $this->loadModel('demand')->getReviewerPairs($object->id, $object->version);
                    $reviewers = array_keys($reviewers);
                    if($reviewers) $target .= ',' . implode(',', $reviewers);
                    $target = trim($target, ',');
                }
                elseif($objectType == 'deploy')
                {
                    $senderUser = $this->loadModel('deploy')->getToAndCcList($object, $actionType);
                    if(is_array($senderUser) && !empty($senderUser))
                    {
                        foreach($senderUser as $user) $target .= ',' . $user;
                    }
                }
                else
                {
                    if(!empty($object->assignedTo)) $target .= $object->assignedTo == 'closed' ? $object->openedBy : $object->assignedTo;
                    if(!empty($object->mailto))     $target .= ",{$object->mailto}";
                }
                if(($objectType == 'mr' or $objectType == 'kanbancard') and !empty($object->createdBy)) $target .= ",{$object->createdBy}";
                $target = trim($target, ',');
                $target = explode(',', $target);
                $target = $this->dao->select('id')->from(TABLE_USER)
                    ->where('account')->in($target)
                    ->beginIF($objectType != 'mr')->andWhere('account')->ne($this->app->user->account)->fi()
                    ->fetchAll('id');
                $target = array_keys($target);

                $subContent = (object)array('action' => $actionType, 'object' => $objectID, 'objectName' => $object->$field, 'objectType' => $objectType, 'actor' => $this->app->user->id, 'actorName' => $this->app->user->realname);
                $subContent->name = $object->$field;
                $subContent->id = sprintf('%03d', $object->id);
                $subContent->count = 1;
                if($objectType == 'task')
                {
                    $subContent->headTitle    = $object->projectName;
                    $subContent->headSubTitle = $object->execuName;
                    $subContent->parentType   = 'execution';
                    $subContent->parent       = $object->execution;
                    $subContent->parentURL    = "xxc:openInApp/zentao-integrated/" . urlencode($server . helper::createLink('execution', 'task', "id=$object->execution", 'html'));
                    $subContent->cardURL      = $url;
                }
                elseif($objectType == 'story')
                {
                    $subContent->headTitle  = $object->productName;
                    $subContent->parentType = 'product';
                    $subContent->parent     = $object->product;
                    $subContent->parentURL  = "xxc:openInApp/zentao-integrated/" . urlencode($server . helper::createLink('product', 'browse', "id=$object->product", 'html'));
                    $subContent->cardURL    = $url;
                }
                elseif($objectType == 'bug')
                {
                    $parentType = empty($object->execuName) ? 'product' : 'project';
                    $parentNameKey = $parentType . 'Name';
                    $subContent->headTitle    = $object->$parentNameKey;
                    $subContent->headSubTitle = $object->execuName;
                    $subContent->parentType   = $parentType;
                    $subContent->parent       = $object->$parentType;
                    $subContent->parentURL    = "xxc:openInApp/zentao-integrated/" . urlencode($server . helper::createLink($parentType, 'browse', "id=$subContent->parent", 'html'));
                    $subContent->cardURL      = $url;
                }
                elseif($objectType == 'feedback')
                {
                    $subContent->headTitle  = $object->productName;
                    $subContent->parentType = 'product';
                    $subContent->parent     = $object->product;
                    $subContent->parentURL  = "xxc:openInApp/zentao-integrated/" . urlencode($server . helper::createLink('feedback', 'browse', "id=$object->product", 'html'));
                    $subContent->cardURL    = $url;
                }
                elseif($objectType == 'demandpool')
                {
                    $subContent->headTitle  = $object->name;
                    $subContent->parentType = '';
                    $subContent->parent     = 0;
                    $subContent->parentURL  = "xxc:openInApp/zentao-integrated/" . urlencode($server . helper::createLink('demandpool', 'browse', '', 'html'));
                    $subContent->cardURL    = $url;
                }
                elseif($objectType == 'demand')
                {
                    $subContent->headTitle  = $object->poolName;
                    $subContent->parentType = 'demandpool';
                    $subContent->parent     = $object->pool;
                    $subContent->parentURL  = "xxc:openInApp/zentao-integrated/" . urlencode($server . helper::createLink('demand', 'browse', "id=$object->pool", 'html'));
                    $subContent->cardURL    = $url;
                }
                elseif($objectType == 'deploy')
                {
                    $subContent->headTitle    = $object->name;
                    $subContent->headSubTitle = $this->lang->devops->deploy;
                    $subContent->parentType   = $objectType;
                    $subContent->parent       = 0;
                    $subContent->parentURL    = "xxc:openInApp/zentao-integrated/" . urlencode($url);
                    $subContent->cardURL      = $url;
                }
                else
                {
                    $subContent->parentType = $objectType;
                }

                $contentData = new stdclass();
                if($objectType == 'mr')
                {
                    $contentData->contentType = 'text';
                    $contentData->url         = "xxc:openInApp/zentao-integrated/" . urlencode($url);
                    $contentData->actions     = array();

                    if(is_array($this->lang->message->mr->$actionType))
                    {
                        $contentData->content = sprintf($this->lang->message->mr->{$actionType}['creator'], $object->title);
                    }
                    else
                    {
                        $contentData->content   = sprintf($this->lang->message->mr->$actionType, $object->title);
                        $contentData->actions[] = array(
                            'type' => 'normal',
                            'label' => $this->lang->message->mr->logTitle,
                            'url' => "xxc:openInApp/zentao-integrated/" . urlencode($server . helper::createLink('compile', 'logs', "compileID=$object->compileID", 'html'))
                        );
                    }
                }
                else
                {
                    $contentData->title       = $title;
                    $contentData->subtitle    = '';
                    $contentData->contentType = "zentao-$objectType-$actionType";
                    $contentData->parentType  = $subContent->parentType;
                    $contentData->content     = json_encode($subContent);
                    $contentData->actions     = array();
                    $contentData->url         = "xxc:openInApp/zentao-integrated/" . urlencode($url);
                }
                $contentData->extra = is_array($extra) ? $extra : '';

                $content   = json_encode($contentData);
                $avatarUrl = $server . $this->app->getWebRoot() . 'favicon.ico';

                $objectTypeList = array('bug', 'task', 'story', 'feedback', 'demand', 'demandpool', 'deploy');
                if($target && in_array($objectType, $objectTypeList)) $this->loadModel('im')->messageCreateNotify($target, $title, '', $content, 'object', $url, array(), array('id' => 'zentao', 'realname' => $this->lang->message->sender, 'name' => $this->lang->message->sender, 'avatar' => $avatarUrl));

                if($objectType == 'mr' and is_array($this->lang->message->mr->$actionType) and !empty($object->assignee))
                {
                    $contentData->content = sprintf($this->lang->message->mr->{$actionType}['reviewer'], $object->title);

                    $content = json_encode($contentData);
                    $target  = $this->dao->select('id')->from(TABLE_USER)->where('account')->eq($object->assignee)->fetch('id');
                    if($target) $this->loadModel('im')->messageCreateNotify(array($target), $title, '', $content, 'object', $url, array(), array('id' => 'zentao', 'realname' => $this->lang->message->sender, 'name' => $this->lang->message->sender, 'avatar' => $avatarUrl));
                }
            }
        }
        return;
    }
}
