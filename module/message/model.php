<?php
/**
 * The model file of message module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     message
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class messageModel extends model
{
    public function getMessages($status = '')
    {
        return $this->dao->select('*')->from(TABLE_NOTIFY)
            ->where('objectType')->eq('message')
            ->andWhere('toList')->eq($this->app->user->account)
            ->beginIF($status)->andWhere('status')->eq($status)->fi()
            ->fetchAll('id');
    }

    /**
     * Get objectTypes 
     * 
     * @access public
     * @return array
     */
    public function getObjectTypes()
    {
        $objectTypes = array();
        foreach($this->config->message->objectTypes as $objectType => $actions)
        {
            $objectTypes[$objectType] = $this->lang->action->objectTypes[$objectType];
        }
        return $objectTypes;
    }

    /**
     * Get object actions. 
     * 
     * @access public
     * @return array
     */
    public function getObjectActions()
    {
        $objectActions = array();
        foreach($this->config->message->objectTypes as $objectType => $actions)
        {
            foreach($actions as $action)
            {
                $objectActions[$objectType][$action] = str_replace($this->lang->webhook->trimWords, '', $this->lang->action->label->$action);
            }
        }
        return $objectActions;
    }

    /**
     * Check send.
     * 
     * @param  string $objectType 
     * @param  int    $objectID 
     * @param  string $actionType 
     * @param  int    $actionID 
     * @access public
     * @return void
     */
    public function send($objectType, $objectID, $actionType, $actionID, $actor = '')
    {
        $messageSetting = $this->config->message->setting;
        if(is_string($messageSetting)) $messageSetting = json_decode($messageSetting, true);

        if(isset($messageSetting['mail']))
        {
            $actions = $messageSetting['mail']['setting'];
            if(isset($actions[$objectType]) and in_array($actionType, $actions[$objectType]))
            {
                $moduleName = $objectType == 'case' ? 'testcase' : $objectType;
                $this->loadModel($moduleName);
                if(method_exists($this->$moduleName, 'sendmail')) $this->$moduleName->sendmail($objectID, $actionID);
            }
        }

        if(isset($messageSetting['webhook']))
        {
            $actions = $messageSetting['webhook']['setting'];
            if(isset($actions[$objectType]) and in_array($actionType, $actions[$objectType]))
            {
                $this->loadModel('webhook')->send($objectType, $objectID, $actionType, $actionID, $actor);
            }
        }

        if(isset($messageSetting['message']))
        {
            $actions = $messageSetting['message']['setting'];
            if(isset($actions[$objectType]) and in_array($actionType, $actions[$objectType]))
            {
                $this->saveNotice($objectType, $objectID, $actionType, $actionID, $actor);
            }
        }
    }

    /**
     * Save notice.
     * 
     * @param  string $objectType 
     * @param  int    $objectID 
     * @param  string $actionType 
     * @param  int    $actionID 
     * @access public
     * @return void
     */
    public function saveNotice($objectType, $objectID, $actionType, $actionID, $actor = '')
    {
        if(empty($actor)) $actor = $this->app->user->account;
        if(empty($actor)) return false;

        $this->loadModel('action');
        $user   = $this->loadModel('user')->getById($actor);
        $table  = $this->config->objectTables[$objectType];
        $field  = $this->config->action->objectNameFields[$objectType];
        $object = $this->dao->select('*')->from($table)->where('id')->eq($objectID)->fetch();
        $toList = $this->getToList($object, $objectType);
        if(empty($toList)) return false;
        if($toList == $actor) return false;

        $this->app->loadConfig('mail');
        $sysURL = zget($this->config->mail, 'domain', common::getSysURL());

        $moduleName = $objectType == 'case' ? 'testcase' : $objectType;
        $space = common::checkNotCN() ? ' ' : '';
        $data  = $user->realname . $space . $this->lang->action->label->$actionType . $space . $this->lang->action->objectTypes[$objectType];
        $data .= ' ' . html::a($sysURL . helper::createLink($moduleName, 'view', "id=$objectID"), "[#{$objectID}::{$object->$field}]");

        $notify = new stdclass();
        $notify->objectType  = 'message';
        $notify->action      = $actionID;
        $notify->toList      = $toList;
        $notify->data        = $data;
        $notify->status      = 'wait';
        $notify->createdBy   = $actor;
        $notify->createdDate = helper::now();

        $this->dao->insert(TABLE_NOTIFY)->data($notify)->exec();
    }

    /**
     * Get toList.
     * 
     * @param  object    $object 
     * @param  string    $objectType 
     * @access public
     * @return string
     */
    public function getToList($object, $objectType)
    {
        $toList = '';
        if(!empty($object->assignedTo)) $toList = $object->assignedTo;
        if(empty($toList) and $objectType == 'todo') $toList = $object->account;
        if(empty($toList) and $objectType == 'testtask') $toList = $object->owner;

        if($toList == 'closed') $toList = '';
        return $toList;
    }

    /**
     * Get notice todos.
     * 
     * @access public
     * @return array
     */
    public function getNoticeTodos()
    {
        $todos    = $this->loadModel('todo')->getList('today', $this->app->user->account, 'wait');
        $notices  = array();
        $now      = helper::now();
        $interval = 60;
        if($todos)
        {
            $begins[1]  = date('Hi', strtotime($now));
            $ends[1]    = date('Hi', strtotime("+$interval seconds $now"));
            $begins[10] = date('Hi', strtotime("+10 minute $now"));
            $ends[10]   = date('Hi', strtotime("+10 minute $interval seconds $now"));
            $begins[30] = date('Hi', strtotime("+30 minute $now"));
            $ends[30]   = date('Hi', strtotime("+30 minute $interval seconds $now"));
            foreach($todos as $todo)
            {
                if(empty($todo->begin)) continue;
                $time = str_replace(':', '', $todo->begin);

                $lastTime = 0;
                if((int)$time > (int)$begins[1]  and (int)$time <= (int)$ends[1])  $lastTime = 1;
                if((int)$time > (int)$begins[10] and (int)$time <= (int)$ends[10]) $lastTime = 10;
                if((int)$time > (int)$begins[30] and (int)$time <= (int)$ends[30]) $lastTime = 30;
                if($lastTime)
                {
                    $notice = new stdclass();
                    $notice->id   = 'todo' . $todo->id;
                    $notice->data = $this->lang->todo->common . ' ' . html::a(helper::createLink('todo', 'view', "id={$todo->id}"), "{$todo->begin} {$todo->name}");

                    $notices[$notice->id] = $notice;
                }
            }
        }

        return $notices;
    }
}
