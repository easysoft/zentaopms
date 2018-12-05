<?php
class xuanxuanMessage extends messageModel
{
    public function send($objectType, $objectID, $actionType, $actionID)
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
                $title  = $this->app->user->realname . $this->lang->action->label->$actionType . $this->lang->action->objectTypes[$objectType];
                $text   = $title . ' ' . "[#{$objectID}::{$object->$field}]";
                $url    = common::getSysURL() . helper::createLink($objectType, 'view', "id=$objectID");

                $target = '';
                if(!empty($object->assignedTo)) $target .= $object->assignedTo;
                if(!empty($object->mailto))     $target .= ",{$object->mailto}";
                $target = trim($target, ',');

                if($target) $this->loadModel('chat')->createNotify($target, $text, '', $text, 'text', $url);
            }
        }
    }
}
