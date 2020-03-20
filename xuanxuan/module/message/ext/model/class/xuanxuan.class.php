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
                $object = $this->dao->select('*')->from($this->config->objectTables[$objectType])->where('id')->eq($objectID)->fetch();
                $field  = $this->config->action->objectNameFields[$objectType];
                $title  = $this->app->user->realname . ' ' . $this->lang->action->label->$actionType . $this->lang->action->objectTypes[$objectType];
                $text   = $title . ' ' . "[#{$objectID}::{$object->$field}]";

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

                if($target) $this->loadModel('im')->messageCreateNotify($target, $text, '', '', 'text', $url, array(), array('id' => 'zentao', 'realname' => $this->lang->message->sender, 'name' => $this->lang->message->sender));
                if($onlybody) $_GET['onlybody'] = $onlybody;
            }
        }
    }
}
