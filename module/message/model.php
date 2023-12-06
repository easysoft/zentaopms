<?php
declare(strict_types=1);
/**
 * The model file of message module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     message
 * @version     $Id$
 * @link        https://www.zentao.net
 */
class messageModel extends model
{
    /**
     * 获取消息。
     * Get messages.
     *
     * @param  string $status
     * @access public
     * @return array
     */
    public function getMessages(string $status = ''): array
    {
        return $this->dao->select('*')->from(TABLE_NOTIFY)
            ->where('objectType')->eq('message')
            ->andWhere('toList')->like("%,{$this->app->user->account},%")
            ->beginIF($status)->andWhere('status')->eq($status)->fi()
            ->fetchAll('id');
    }

    /**
     * 获取对象类型。
     * Get object types.
     *
     * @access public
     * @return array
     */
    public function getObjectTypes(): array
    {
        $this->app->loadLang('action');
        $objectTypes = array();
        foreach($this->config->message->objectTypes as $objectType => $actions) $objectTypes[$objectType] = $this->lang->action->objectTypes[$objectType];
        return $objectTypes;
    }

    /**
     * 获取对象操作。
     * Get object actions.
     *
     * @access public
     * @return array
     */
    public function getObjectActions(): array
    {
        $objectActions = array();
        foreach($this->config->message->objectTypes as $objectType => $actions)
        {
            foreach($actions as $action) $objectActions[$objectType][$action] = $this->lang->message->label->{$action};
        }
        return $objectActions;
    }

    /**
     * 发送消息。
     * Send messages.
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
    public function send(string $objectType, int $objectID, string $actionType, int $actionID, string $actor = '', string $extra = ''): void
    {
        if(commonModel::isTutorialMode()) return;

        $objectType     = strtolower($objectType);
        $messageSetting = $this->config->message->setting;
        if(is_string($messageSetting)) $messageSetting = json_decode($messageSetting, true);

        if(isset($messageSetting['mail']))
        {
            $actions = $messageSetting['mail']['setting'];
            if(isset($actions[$objectType]) && in_array($actionType, $actions[$objectType]))
            {
                /* If it is an api call, get the request method set by the user. */
                global $config;
                $requestType = $config->requestType;
                if(defined('RUN_MODE') && RUN_MODE == 'api')
                {
                    $configRoot = $this->app->getConfigRoot();
                    include file_exists($configRoot . 'my.php') ? $configRoot . 'my.php' : $configRoot . 'config.php';
                }

                if($objectType == 'feedback')
                {
                    $this->loadModel('feedback')->sendmail($objectID, $actionID);
                }
                else
                {
                    $this->loadModel('mail')->sendmail($objectID, $actionID);
                }

                if(defined('RUN_MODE') && RUN_MODE == 'api') $config->requestType = $requestType;
            }
        }

        if(isset($messageSetting['webhook']))
        {
            $actions = $messageSetting['webhook']['setting'];
            if(isset($actions[$objectType]) && in_array($actionType, $actions[$objectType])) $this->loadModel('webhook')->send($objectType, $objectID, $actionType, $actionID, $actor);
        }
        if(isset($messageSetting['message']))
        {
            $actions = $messageSetting['message']['setting'];
            if(isset($actions[$objectType]) && in_array($actionType, $actions[$objectType])) $this->saveNotice($objectType, $objectID, $actionType, $actionID, $actor);
        }
    }

    /**
     * 存储提示消息。
     * Save notice.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  string $actionType
     * @param  int    $actionID
     * @param  string $actor
     * @access public
     * @return bool
     */
    public function saveNotice(string $objectType, int $objectID, string $actionType, int $actionID, string $actor = ''): bool
    {
        if(empty($actor)) $actor = $this->app->user->account;
        if(empty($actor) || !$objectID) return false;

        $this->loadModel('action');
        $user   = $this->loadModel('user')->getById($actor);
        $table  = $this->config->objectTables[$objectType];
        $field  = $this->config->action->objectNameFields[$objectType];
        $object = $this->dao->select('*')->from($table)->where('id')->eq($objectID)->fetch();
        $toList = $this->getToList($object, $objectType, $actionID);
        if(empty($toList) || $toList == $actor) return false;

        $this->app->loadConfig('mail');
        $sysURL = zget($this->config->mail, 'domain', common::getSysURL());

        $isonlybody = isInModal();
        if($isonlybody) unset($_GET['onlybody']);

        $moduleName = $objectType == 'case' ? 'testcase' : $objectType;
        if($objectType == 'kanbancard') $moduleName = 'kanban';
        $space      = common::checkNotCN() ? ' ' : '';
        $data       = $user->realname . $space . $this->lang->action->label->{$actionType} . $space . $this->lang->action->objectTypes[$objectType];
        $dataID     = $objectType == 'kanbancard' ? $object->kanban : $objectID;
        $url        = helper::createLink($moduleName, 'view', "id={$dataID}");
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
        return true;
    }

    /**
     * 获取抄送给的人员。
     * Get toList.
     *
     * @param  object $object
     * @param  string $objectType
     * @param  int    $actionID
     * @access public
     * @return string
     */
    public function getToList(object $object, string $objectType, int $actionID = 0): string
    {
        $toList = '';
        if(!empty($object->assignedTo)) $toList = $object->assignedTo;
        if(empty($toList) && $objectType == 'todo') $toList = $object->account;
        if(empty($toList) && $objectType == 'testtask') $toList = $object->owner;
        if(empty($toList) && $objectType == 'meeting') $toList = $object->host . $object->participant;
        if(empty($toList) && $objectType == 'mr') $toList = $object->createdBy . ',' . $object->assignee;
        if(empty($toList) && $objectType == 'release')
        {
            /* Get notifiy persons. */
            $notifyPersons = array();
            if(!empty($object->notify)) $notifyPersons = $this->loadModel('release')->getNotifyPersons($object);

            if(!empty($notifyPersons)) $toList = implode(',', $notifyPersons);
        }
        if(empty($toList) && $objectType == 'task' && $object->mode == 'multi')
        {
            /* Get task team members. */
            $teamMembers = $this->loadModel('task')->getMultiTaskMembers($object->id);
            $toList      = array_filter($teamMembers, function($account){
                return $account != $this->app->user->account;
            });
            $toList     = implode(',', $toList);
        }

        if($toList == 'closed') $toList = '';
        if($objectType == 'feedback' && $object->status == 'replied') $toList = ',' . $object->openedBy . ',';
        if($objectType == 'story' && $actionID)
        {
            $action = $this->loadModel('action')->getById($actionID);
            list($toList, $ccList) = $this->loadModel($objectType)->getToAndCcList($object, $action->action);
            $toList = $toList . $ccList;
        }

        if($objectType == 'testtask')
        {
            $toList = array_merge(explode(',', $toList), explode(',', $object->members));
            $toList = array_filter(array_unique($toList));
            $toList = implode(',', $toList);
        }

        return $toList;
    }

    /**
     * 获取提示待办。
     * Get notice todos.
     *
     * @access public
     * @return array
     */
    public function getNoticeTodos(): array
    {
        $todos    = $this->loadModel('todo')->getList('today', $this->app->user->account, 'wait');
        $notices  = array();
        $now      = helper::now();
        $interval = 60;
        if($todos)
        {
            /* Set date array. */
            $begins[1]  = (int)date('Hi', strtotime($now));
            $begins[10] = (int)date('Hi', strtotime("+10 minute {$now}"));
            $begins[30] = (int)date('Hi', strtotime("+30 minute {$now}"));
            $ends[1]    = (int)date('Hi', strtotime("+{$interval} seconds {$now}"));
            $ends[10]   = (int)date('Hi', strtotime("+10 minute {$interval} seconds {$now}"));
            $ends[30]   = (int)date('Hi', strtotime("+30 minute {$interval} seconds {$now}"));
            foreach($todos as $todo)
            {
                if(empty($todo->begin)) continue;
                $time = (int)str_replace(':', '', $todo->begin);

                $lastTime = 0;
                if($time > $begins[1]  && $time <= $ends[1])  $lastTime = 1;
                if($time > $begins[10] && $time <= $ends[10]) $lastTime = 10;
                if($time > $begins[30] && $time <= $ends[30]) $lastTime = 30;
                /* If the todo needs to be reminded, add it to notices array. */
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

    /**
     * 获取浏览器通知的相关配置信息。
     * Get browser message config.
     *
     * @access public
     * @return array
     */
    public function getBrowserMessageConfig(): array
    {
        return array('turnon' => $this->config->message->browser->turnon, 'pollTime' => $this->config->message->browser->pollTime);
    }
}
