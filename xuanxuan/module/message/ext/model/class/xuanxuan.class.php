<?php
class xuanxuanMessage extends messageModel
{
    public function send($objectType, $objectID, $actionType, $actionID, $actor = '')
    {
        $messageSetting = $this->config->message->setting;
        if(is_string($messageSetting)) $messageSetting = json_decode($messageSetting, true);
        if(isset($messageSetting['xuanxuan']))
        {
            $messageActions = $messageSetting['xuanxuan']['setting'];
            if(isset($messageActions[$objectType]) and in_array($actionType, $messageActions[$objectType]))
            {
                $this->loadModel('action');
                if($objectType == 'task')
                {
                    $field = 'obj.*,project.name as projectName,execu.name as execuName';
                }
                else if($objectType == 'story')
                {
                    $field = 'obj.*,product.name as productName';
                }
                else if($objectType == 'bug')
                {
                    $field = 'obj.*,project.name as projectName,product.name as productName,execu.name as execuName';
                }
                else
                {
                    $field = 'task.*';
                }
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
                    ->where('obj.id')->eq($objectID)
                    ->fetch();
                $field = $this->config->action->objectNameFields[$objectType];
                $title = sprintf($this->lang->message->notifyTitle, $this->app->user->realname, $this->lang->action->label->$actionType, 1, $this->lang->action->objectTypes[$objectType]);

                $server   = $this->loadModel('im')->getServer('zentao');
                $onlybody = isset($_GET['onlybody']) ? $_GET['onlybody'] : '';
                unset($_GET['onlybody']);
                $url = $server . helper::createLink($objectType, 'view', "id=$objectID", 'html');

                $target = '';
                if(!empty($object->assignedTo)) $target .= $object->assignedTo;
                if(!empty($object->mailto))     $target .= ",{$object->mailto}";
                $target = trim($target, ',');
                $target = $this->dao->select('id')->from(TABLE_USER)->where('account')->in($target)->andWhere('account')->ne($this->app->user->account)->fetchAll('id');
                $target = array_keys($target);

                $subcontent = (object)array('action' => $actionType, 'object' => $objectID, 'objectName' => $object->$field, 'objectType' => $objectType, 'actor' => $this->app->user->id, 'actorName' => $this->app->user->realname);
                $subcontent->name = $object->$field;
                $subcontent->id = sprintf('%03d', $object->id);
                $subcontent->count = 1;
                if($objectType == 'task')
                {
                    $subcontent->headTitle    = $object->projectName;
                    $subcontent->headSubTitle = $object->execuName;
                    $subcontent->parentType   = 'execution';
                    $subcontent->parent       = $object->execution;
                    $subcontent->parentURL    = "xxc:openInApp/zentao-integrated/" . urlencode($server . helper::createLink('execution', 'task', "id=$object->execution", 'html'));
                    $subcontent->cardURL      = $url;
                }
                elseif($objectType == 'story')
                {
                    $subcontent->headTitle  = $object->productName;
                    $subcontent->parentType = 'product';
                    $subcontent->parent     = $object->product;
                    $subcontent->parentURL  = "xxc:openInApp/zentao-integrated/" . urlencode($server . helper::createLink('product', 'browse', "id=$object->product", 'html'));
                    $subcontent->cardURL    = $url;
                }
                elseif($objectType == 'bug')
                {
                    $parentType = empty($object->execuName) ? 'product' : 'project';
                    $parentNameKey = $parentType . 'Name';
                    $subcontent->headTitle    = $object->$parentNameKey;
                    $subcontent->headSubTitle = $object->execuName;
                    $subcontent->parentType   = $parentType;
                    $subcontent->parent       = $object->$parentType;
                    $subcontent->parentURL    = "xxc:openInApp/zentao-integrated/" . urlencode($server . helper::createLink($parentType, 'browse', "id=$subcontent->parent", 'html'));
                    $subcontent->cardURL      = $url;
                }

                $contentData = new stdclass();
                $contentData->title       = $title;
                $contentData->subtitle    = '';
                $contentData->contentType = "zentao-$objectType-$actionType";
                $contentData->parentType  = $subcontent->parentType;
                $contentData->content     = json_encode($subcontent);
                $contentData->actions     = array();
                $contentData->url         = "xxc:openInApp/zentao-integrated/" . urlencode($url);
                $content = json_encode($contentData);

                if($target) $this->loadModel('im')->messageCreateNotify($target, $title, $subtitle = '', $content, $contentType = 'object', $url, $actions = array(), $sender = array('id' => 'zentao', 'realname' => $this->lang->message->sender, 'name' => $this->lang->message->sender));
                if($onlybody) $_GET['onlybody'] = $onlybody;
            }
        }
    }
}
