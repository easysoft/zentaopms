<?php
/**
 * The model file of message module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
            ->andWhere('toList')->like("%,{$this->app->user->account},%")
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
            foreach($actions as $action) $objectActions[$objectType][$action] = $this->lang->message->label->$action;
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
     * @param  string $actor
     * @param  string $extra
     * @access public
     * @return void
     */
    public function send($objectType, $objectID, $actionType, $actionID, $actor = '', $extra = '')
    {
        if(defined('TUTORIAL')) return;

        $objectType     = strtolower($objectType);
        $messageSetting = $this->config->message->setting;
        if(is_string($messageSetting)) $messageSetting = json_decode($messageSetting, true);

        if(isset($messageSetting['mail']))
        {
            $actions = $messageSetting['mail']['setting'];
            if(isset($actions[$objectType]) and in_array($actionType, $actions[$objectType]))
            {
                /* If it is an api call, get the request method set by the user. */
                global $config;
                $requestType = $config->requestType;
                if(defined('RUN_MODE') and RUN_MODE == 'api')
                {
                    $configRoot = $this->app->getConfigRoot();
                    if(file_exists($configRoot . 'my.php'))
                    {
                        include $configRoot . 'my.php';
                    }
                    else
                    {
                        include $configRoot . 'config.php';
                    }
                }

                if($objectType == 'feedback')
                {
                    $this->loadModel('feedback')->sendmail($objectID, $actionID);
                }
                else
                {
                    $this->loadModel('mail')->sendmail($objectID, $actionID);
                }

                if(defined('RUN_MODE') and RUN_MODE == 'api') $config->requestType = $requestType;
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
        $toList = $this->getToList($object, $objectType, $actionID);
        if(empty($toList)) return false;
        if($toList == $actor) return false;

        $this->app->loadConfig('mail');
        $sysURL = zget($this->config->mail, 'domain', common::getSysURL());

        $isonlybody = isonlybody();
        if($isonlybody) unset($_GET['onlybody']);

        $moduleName = $objectType == 'case' ? 'testcase' : $objectType;
        $moduleName = $objectType == 'kanbancard' ? 'kanban' : $objectType;
        $space      = common::checkNotCN() ? ' ' : '';
        $data       = $user->realname . $space . $this->lang->action->label->$actionType . $space . $this->lang->action->objectTypes[$objectType];
        $dataID     = $objectType == 'kanbancard' ? $object->kanban : $objectID;
        $url        = helper::createLink($moduleName, 'view', "id=$dataID");
        $data      .= ' ' . html::a((strpos($url, $sysURL) === 0 ? '' : $sysURL) . $url, "[#{$objectID}::{$object->$field}]");

        if($isonlybody) $_GET['onlybody'] = 'yes';

        $notify = new stdclass();
        $notify->objectType  = 'message';
        $notify->action      = $actionID;
        $notify->toList      = str_replace(",{$actor},", '', ",$toList,");
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
     * @param  int       $actionID
     * @access public
     * @return string
     */
    public function getToList($object, $objectType, $actionID = 0)
    {
        $toList = '';
        if(!empty($object->assignedTo)) $toList = $object->assignedTo;
        if(empty($toList) and $objectType == 'todo') $toList = $object->account;
        if(empty($toList) and $objectType == 'testtask') $toList = $object->owner;
        if(empty($toList) and $objectType == 'meeting') $toList = $object->host . $object->participant;
        if(empty($toList) and $objectType == 'mr') $toList = $object->createdBy . ',' . $object->assignee;
        if(empty($toList) and $objectType == 'demandpool') $toList = trim($object->owner, ',') . ',' . trim($object->reviewer, ',');
        if(empty($toList) and $objectType == 'release')
        {
            /* Get notifiy persons. */
            $notifyPersons = array();
            if(!empty($object->notify)) $notifyPersons = $this->loadModel('release')->getNotifyPersons($object);

            if(!empty($notifyPersons)) $toList = implode(',', $notifyPersons);
        }
        if(empty($toList) and $objectType == 'task' and $object->mode == 'multi')
        {
            $teamMembers = $this->loadModel('task')->getTeamMembers($object->id);
            $toList      = array_filter($teamMembers, function($account){
                return $account != $this->app->user->account;
            });
            $toList     = implode(',', $toList);
        }

        if($toList == 'closed') $toList = '';
        if($objectType == 'feedback' and $object->status == 'replied') $toList = ',' . $object->openedBy . ',';
        if($objectType == 'story' and $actionID)
        {
            $action = $this->loadModel('action')->getById($actionID);
            list($toList, $ccList) = $this->loadModel($objectType)->getToAndCcList($object, $action->action);
            $toList = $toList . $ccList;
        }

        if($objectType == 'testtask')
        {
            $members = explode(',', $object->members);
            $toList  = explode(',', $toList);
            $toList  = array_merge($toList, $members);
            $toList  = array_filter(array_unique($toList));
            $toList  = implode(',', $toList);
        }

        if(empty($toList) and $objectType == 'demand' and $this->config->edition == 'ipd')
        {
            $toList  = $object->assignedTo;
            $toList .= ',' . str_replace(' ', '', trim($object->mailto, ','));
            $toList .= ",$object->createdBy";

            $reviewers = $this->loadModel('demand')->getReviewerPairs($object->id, $object->version);
            $reviewers = array_keys($reviewers);
            if($reviewers) $toList .= ',' . implode(',', $reviewers);
            $toList = trim($toList, ',');
        }

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
