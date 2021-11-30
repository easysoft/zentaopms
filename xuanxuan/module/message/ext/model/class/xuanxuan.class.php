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
                $url = "xxc:openInApp/zentao-integrated/" . urlencode($url);

                $target = '';
                if(!empty($object->assignedTo)) $target .= $object->assignedTo;
                if(!empty($object->mailto))     $target .= ",{$object->mailto}";
                $target = trim($target, ',');
                $target = $this->dao->select('id')->from(TABLE_USER)->where('account')->in($target)->andWhere('account')->ne($this->app->user->account)->fetchAll('id');
                $target = array_keys($target);

                $subcontent = (object)array('action' => $actionType, 'object' => $objectID, 'objectName' => $object->$field, 'objectType' => $objectType, 'actor' => $this->app->user->id, 'actorName' => $this->app->user->realname);
                if($objectType == 'task')
                {
                    $subcontent->headTitle    = $object->projectName;
                    $subcontent->headSubTitle = $object->execuName;
                }
                elseif($objectType == 'story')
                {
                    $subcontent->headTitle = $object->productName;
                }
                elseif($objectType == 'bug')
                {
                    $subcontent->headTitle    = empty($object->productName) ? $object->projectName : $object->productName;
                    $subcontent->headSubTitle = $object->execuName;
                }

                $contentData = new stdclass();
                $contentData->sender      = (object)array('id' => $this->app->user->id, 'name' => $this->app->user->realname, 'avatar' => '');
                $contentData->title       = $title;
                $contentData->subtitle    = '';
                $contentData->contentType = "zentao-$objectType-$actionType";
                $contentData->content     = json_encode((object)$subcontent);
                $contentData->actions     = array();
                $contentData->url         = $url;
                $content = json_encode($contentData);

                if($target) $this->loadModel('im')->messageCreateNotify($target, $title, $subtitle = '', $content, $contentType = 'object', $url, $actions = array(), $sender = array('id' => 'zentao', 'realname' => $this->lang->message->sender, 'name' => $this->lang->message->sender));
                if($onlybody) $_GET['onlybody'] = $onlybody;
            }
        }
    }
}
