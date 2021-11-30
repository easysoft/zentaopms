<?php
class xuanxuanMessage extends messageModel
{
    public function send($objectType, $objectID, $actionType, $actionID, $actor = '')
    {
        $messageSetting = $this->config->message->setting;
        if(is_string($messageSetting)) $messageSetting = json_decode($messageSetting, true);
        if(isset($messageSetting['xuanxuan']))
        {
            $actions = $messageSetting['xuanxuan']['setting'];
            if(isset($actions[$objectType]) and in_array($actionType, $actions[$objectType]))
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
                $object = $this->dao
                    ->select($field)
                    ->from($this->config->objectTables[$objectType])->alias('obj')
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
                    ->where('obj.id')->eq($objectID)->fetch();
                $field  = $this->config->action->objectNameFields[$objectType];
                $text  = sprintf($this->lang->message->notifyTitle, $this->app->user->realname, $this->lang->action->label->$actionType, 1, $this->lang->action->objectTypes[$objectType]);

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

                $subtitle = '';
                $content                = array();
                $content['title']       = $text;
                $content['subtitle']    = $subtitle;
                $content['url']         = $url;
                $content['content']     = '';
                $content['actions']     = array();
                $content['sender']      = array('id' => 'zentao', 'realname' => $this->lang->message->sender, 'name' => $this->lang->message->sender);

                $subcontent         = array();
                $subcontent['id']   = sprintf('%03d', $object->id);
                $subcontent['name'] = $object->$field;

                if($objectType == 'task')
                {
                    $content['contentType'] = 'zentao-task';

                    $subcontent['headTitle'] = $object->projectName;
                    $subcontent['headSubTitle']   = $object->execuName;
                }
                else if($objectType == 'story')
                {
                    $content['contentType'] = 'zentao-story';

                    $subcontent['headTitle'] = $object->productName;
                }
                else if($objectType == 'bug')
                {
                    $content['contentType'] = 'zentao-bug';

                    $subcontent['headTitle'] = empty($object->productName) ? $object->projectName : $object->productName;
                    $subcontent['headSubTitle']   = $object->execuName;
                }

                $content['content'] = json_encode(array($subcontent));

                $content = json_encode($content);
                $contentType = 'object';

                if($target) $this->loadModel('im')->messageCreateNotify($target, $text, $subtitle, $content, $contentType, $url, array(), array('id' => 'zentao', 'realname' => $this->lang->message->sender, 'name' => $this->lang->message->sender));
                if($onlybody) $_GET['onlybody'] = $onlybody;
            }
        }
    }
}
