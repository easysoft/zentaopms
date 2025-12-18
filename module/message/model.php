<?php
declare(strict_types=1);
/**
 * The model file of message module of ZenTaoPMS.
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
     * @param  string $status    all|wait|sended
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getMessages(string $status = 'all', string $orderBy = 'createdDate'): array
    {
        return $this->dao->select('t1.*')->from(TABLE_NOTIFY)->alias('t1')
            ->leftJoin(TABLE_ACTION)->alias('t2')->on("t1.objectType = 'message' AND t1.action = t2.id")
            ->where('t1.objectType')->eq('message')
            ->andWhere('t1.toList')->eq(",{$this->app->user->account},")
            ->andWhere('t2.vision')->eq($this->config->vision)
            ->beginIF(!empty($status) && $status != 'all')->andWhere('t1.status')->eq($status)->fi()
            ->orderBy($orderBy)
            ->fetchAll('id', false);
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
        $this->loadModel('action');
        $objectTypes = array();
        foreach($this->config->message->objectTypes as $objectType => $actions)
        {
            if(!isset($this->lang->action->objectTypes[$objectType])) continue;
            $objectTypes[$objectType] = $this->lang->action->objectTypes[$objectType];
        }
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
            foreach($actions as $action)
            {
                if(isset($this->lang->message->label->{$action})) $objectActions[$objectType][$action] = $this->lang->message->label->{$action};
                if(isset($this->lang->message->label->{$objectType}) && isset($this->lang->message->label->{$objectType}->{$action})) $objectActions[$objectType][$action] = $this->lang->message->label->{$objectType}->{$action};
            }
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

        /* 如果是业需和用需，则使用它们的发信配置。*/
        if($objectType == 'story')
        {
            $story = $this->loadModel('story')->fetchByID($objectID);
            if($story) $objectType = $story->type;
        }

        /* 如果对象类型是审批，动作是提交审计或者审计，使用瀑布项目审批的发信配置。*/
        if($objectType == 'review' && strpos(',toaudit,audited,', ",{$actionType},") !== false) $objectType = 'waterfall';

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

                if($objectType == 'feedback' || $objectType == 'ticket')
                {
                    $this->loadModel($objectType)->sendmail($objectID, $actionID);
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
     * 批量保存待办消息。
     * Batch save todo notice.
     *
     * @access public
     * @return array
     */
    public function batchSaveTodoNotice(): array
    {
        $todos = $this->getNoticeTodos();
        if(empty($todos)) return array();

        $account  = $this->app->user->account;
        $newTodos = array();
        foreach($todos as $todo)
        {
            $notice = new stdclass();
            $notice->objectType  = 'message';
            $notice->action      = 0;
            $notice->toList      = ",{$account},";
            $notice->data        = $todo->data;
            $notice->status      = 'wait';
            $notice->createdBy   = $account;
            $notice->createdDate = helper::now();
            $this->dao->insert(TABLE_NOTIFY)->data($notice)->exec();

            $noticeID = $this->dao->lastInsertID();
            $todo->id = $noticeID;
            $newTodos[$noticeID] = $todo;
        }
        return $newTodos;
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

        /* 如果对象类型是瀑布，动作是提交审计或者审计，那么对象类型就是审批。*/
        if($objectType == 'waterfall' && strpos(',toaudit,audited,', ",{$actionType},") !== false) $objectType = 'review';

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

        $methodNmae = 'view';
        $moduleName = $objectType == 'case' ? 'testcase' : $objectType;
        if($objectType == 'kanbancard') $moduleName = 'kanban';
        if($objectType == 'feedback' && $this->config->vision == 'rnd') $methodNmae = 'adminView';
        if($objectType == 'auditplan') $object->title = $this->lang->auditplan->common . ' #' . $object->id;
        $space      = common::checkNotCN() ? ' ' : '';
        $data       = ($actor == 'guest' ? 'guest' : $user->realname) . $space . $this->lang->action->label->{$actionType} . $space . $this->lang->action->objectTypes[$objectType];
        $dataID     = $objectType == 'kanbancard' ? $object->kanban : $objectID;
        $url        = helper::createLink($moduleName, $methodNmae, "id={$dataID}");
        $data      .= ' ' . html::a((strpos($url, $sysURL) === 0 ? '' : $sysURL) . $url, "[#{$objectID}::{$object->$field}]");

        if($isonlybody) $_GET['onlybody'] = 'yes';

        foreach(explode(',', trim($toList, ',')) as $to)
        {
            if($to == $actor) continue;
            $notify = new stdclass();
            $notify->objectType  = 'message';
            $notify->action      = $actionID;
            $notify->toList      = ",{$to},";
            $notify->data        = $data;
            $notify->status      = 'wait';
            $notify->createdBy   = $actor;
            $notify->createdDate = helper::now();

            $this->dao->insert(TABLE_NOTIFY)->data($notify)->exec();
        }
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
        $ccList = '';
        if(!empty($object->assignedTo))                    $toList = $object->assignedTo;
        if(empty($toList) && $objectType == 'todo')        $toList = $object->account;
        if(empty($toList) && $objectType == 'testtask')    $toList = $object->owner;
        if(empty($toList) && $objectType == 'meeting')     $toList = $object->host . $object->participant;
        if(empty($toList) && $objectType == 'mr')          $toList = $object->createdBy . ',' . $object->assignee;
        if(empty($toList) and $objectType == 'demandpool') $toList = trim($object->owner, ',') . ',' . trim($object->reviewer, ',');
        if(empty($toList) && in_array($objectType, array('release', 'doc', 'execution')))
        {
            list($toList, $ccList) = $this->loadModel($objectType)->getToAndCcList($object);
            $toList = $toList . ',' . $ccList;
        }

        if(empty($toList) && $objectType == 'rule' && $actionID)
        {
            $action = $this->loadModel('action')->getById($actionID);
            list($toList, $ccList) = $this->loadModel('rule')->getToAndCcList($object, $action);
            $toList = $toList . ',' . $ccList;
        }

        if($toList == 'closed') $toList = '';
        if($objectType == 'feedback' && $object->status == 'replied') $toList = ',' . $object->openedBy . ',';
        if(in_array($objectType, array('story', 'epic', 'requirement', 'ticket', 'review', 'deploy', 'task', 'feedback', 'reviewissue', 'bug')) && $actionID)
        {
            $action      = $this->loadModel('action')->getById($actionID);
            $toAndCcList = $this->loadModel($objectType)->getToAndCcList($object, $action->action);
            if(!empty($toAndCcList)) list($toList, $ccList) = $toAndCcList;
            $toList = $toList . ',' . $ccList;
        }

        if($objectType == 'testtask')
        {
            $toList = array_merge(explode(',', $toList), explode(',', $object->members));
            $toList = array_filter(array_unique($toList));
            $toList = implode(',', $toList);
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

        if(strpos(',opportunity,risk,issue,', ",{$objectType},") !== false) $toList = "{$object->assignedTo},{$object->createdBy}";

        /* 非内置工作流使用工作流的toList。 */
        if($this->config->edition != 'open')
        {
            $flow    = $this->loadModel('workflow')->getByModule($objectType);
            $groupID = $this->loadModel('workflowgroup')->getGroupIDByDataID($objectType, $object->id);
            $method  = $this->loadModel('workflowaction')->getByModuleAndAction($objectType, $this->app->rawMethod, $groupID);
            if($flow && !$flow->buildin) $toList = $this->loadModel('flow')->getToList($flow, $object->id, $method);
        }

        if($objectType == 'product') $toList = $object->createdBy . ',' . $object->PO;
        if($objectType == 'project') $toList = $object->openedBy . ',' . $object->PM;

        return trim($toList, ',');
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

    /**
     * 获取未读消息数量。
     * Get unread count.
     *
     * @access public
     * @return int
     */
    public function getUnreadCount(): int
    {
        $account = $this->app->user->account;
        return $this->dao->select('COUNT(1) as count')->from(TABLE_NOTIFY)->where('toList')->eq(",{$account},")->andWhere('objectType')->eq('message')->andWhere('status')->ne('read')->fetch('count');
    }

    /**
     * 删除过期消息。
     * Delete expire messages.
     *
     * @access public
     * @return void
     */
    public function deleteExpired(): void
    {
        $days       = (int)$this->config->message->browser->maxDays;
        $account    = $this->app->user->account;
        $expiryDate = date('Y-m-d 00:00:00', time() - 86400 * ($days + 1));
        $this->dao->delete()->from(TABLE_NOTIFY)->where('toList')->eq(",{$account},")->andWhere('objectType')->eq('message')->andWhere('createdDate')->lt($expiryDate)->exec();
    }
}
