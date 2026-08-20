<?php
declare(strict_types=1);
/**
 * The model file of message module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
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
        $now = helper::now();
        return $this->dao->select('*')->from(TABLE_NOTIFY)->where('`objectType`')->eq('message')
            ->andWhere('`toList`')->eq(",{$this->app->user->account},")
            ->beginIF(!empty($status) && $status != 'all')->andWhere('status')->eq($status)->fi()
            ->andWhere("(`sendTime` IS NULL OR `sendTime` <= '{$now}')")
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
            if($story && $story->status == 'draft') return;
        }

        if(isset($messageSetting['mail']))
        {
            $actions = $messageSetting['mail']['setting'];
            if(isset($actions[$objectType]) && in_array($actionType, $actions[$objectType]))
            {
                /* If it is an api call, get the request method set by the user. */
                global $config;
                $requestType = $config->requestType;
                if(helper::isApiRequest())
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

                if(helper::isApiRequest()) $config->requestType = $requestType;
            }
        }

        if(isset($messageSetting['webhook']))
        {
            $actions = $messageSetting['webhook']['setting'];
            if(isset($actions[$objectType]) && in_array($actionType, $actions[$objectType])) $this->loadModel('webhook')->send($objectType, $objectID, $actionType, $actionID, $actor);
        }
        if(isset($messageSetting['message']))
        {
            $isBuiltinMethod = true;
            if($this->config->edition != 'open')
            {
                $groupID = $this->loadModel('workflowgroup')->getGroupIDByDataID($objectType, $objectID);
                $method  = $this->loadModel('workflowaction')->getByModuleAndAction($objectType, $this->app->rawMethod, $groupID);
                if($method && !$method->buildin) $isBuiltinMethod = false;
            }

            $actions = $messageSetting['message']['setting'];
            if($isBuiltinMethod && isset($actions[$objectType]) && in_array($actionType, $actions[$objectType])) $this->saveNotice($objectType, $objectID, $actionType, $actionID, $actor);
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
        $table  = $this->config->objectTables[$objectType];
        $field  = $this->config->action->objectNameFields[$objectType];
        $object = $this->dao->select('*')->from($table)->where('id')->eq($objectID)->fetch();
        if(!$object) return false;

        if(in_array($objectType, array('issue', 'risk', 'opportunity')) && !empty($object->lib)) return false; // 资产库中的数据不发送通知

        $this->app->loadConfig('mail');
        $sysURL = zget($this->config->mail, 'domain', common::getSysURL());

        $isonlybody = isInModal();
        if($isonlybody) unset($_GET['onlybody']);

        $methodName = 'view';
        $moduleName = $objectType == 'case' ? 'testcase' : $objectType;
        if($objectType == 'kanbancard') $moduleName = 'kanban';
        if($objectType == 'feedback' && $this->config->vision == 'rnd') $methodName = 'adminView';
        if($objectType == 'auditplan') $object->title = $this->lang->auditplan->common . ' #' . $object->id;

        $dataID = $objectType == 'kanbancard' ? $object->kanban : $objectID;
        $url    = helper::createLink($moduleName, $methodName, "id={$dataID}");
        if(in_array($objectType, array('story', 'task')) && $this->app->tab == 'project') $url .= '#app=project'; // 无迭代项目要跳转到项目下

        $objectUrl   = (strpos($url, $sysURL) === 0 ? '' : $sysURL) . $url;
        $objectTitle = html::a($objectUrl, "#{$objectID}::" . zget($object, $field, ''), '_self', '', false);

        $sendTime = null;
        if($objectType == 'aitask' && in_array($actionType, array('finished', 'failed')))
        {
            $sendTime = helper::now();
            if($object && !empty($object->noticeTime) && $object->noticeTime != '1')
            {
                $today           = date('Y-m-d');
                $targetTime      = $today . ' ' . $object->noticeTime . ':00';
                $targetTimestamp = strtotime($targetTime);
                $nowTimestamp    = time();
                $sendTime        = $targetTimestamp < $nowTimestamp ? helper::now() : $targetTime;
            }
        }

        if($isonlybody) $_GET['onlybody'] = 'yes';

        $toCcList = $this->getToAndCcList($object, $objectType, $actionID);
        $users    = $this->loadModel('user')->getPairs('noletter|noclosed|nodeleted');
        foreach($toCcList as $role => $userList)
        {
            foreach(array_unique(array_filter(explode(',', $userList))) as $account)
            {
                if($account == $actor || empty($account) || !isset($users[$account])) continue;

                $notify = new stdclass();
                $notify->objectType  = 'message';
                $notify->action      = $actionID;
                $notify->toList      = ",{$account},";
                $notify->subject     = $this->buildNoticeTitle($objectType, $actionType, $account, $object, $role, $users);
                $notify->data        = $this->buildNoticeTextByAccount($account, $object, $actionType, $actionID, $objectType, $objectTitle, $role, $users);
                $notify->status      = 'wait';
                $notify->createdBy   = $actor;
                $notify->createdDate = helper::now();
                $notify->sendTime    = $sendTime;

                $this->dao->insert(TABLE_NOTIFY)->data($notify)->exec();
            }
        }
        return true;
    }

    public function buildNoticeTitle(string $objectType, string $actionType, string $account, object $object, string $role = 'to', array $users = array()): string
    {
        $objectActions  = $this->getObjectActions();
        $actionName     = zget(zget($objectActions, $objectType, array()), $actionType);
        $objectTypeName = zget($this->lang->action->objectTypes, $objectType);

        if($actionType == 'assigned')
        {
            $assignedTo = zget($object, 'assignedTo', '');
            if($assignedTo != $account && $role == 'to') $role = 'other';
            if($assignedTo == $account && $role != 'to') $role = 'to';

            $assignedTo = zget($users, $assignedTo);
        }

        $noticeTitle = zget($this->lang->message->noticeTitle, $objectType, $this->lang->message->noticeTitle->generic);
        if(isset($lang->message->noticeTitle->extend[$actionType][$role])) $noticeTitle = $lang->message->noticeTitle->extend[$actionType][$role];
        if($objectType == 'aitask' && in_array($actionType, array('finished', 'failed'))) $noticeTitle = zget($this->lang->message->noticeTitle, $objectType . $actionType, $this->lang->message->noticeTitle->generic);

        $noticeTitle = str_replace(array('{objectType}', '{action}'), array($objectTypeName, $actionName), $noticeTitle);
        if($assignedTo) $noticeTitle = str_replace('{assignedTo}', $assignedTo, $noticeTitle);
        return $noticeTitle;
    }

    public function buildNoticeTextByAccount(string $account, object $object, string $actionType, int $actionID, string $objectType, string $objectTitle, string $role = 'to', array $users = array()): string
    {
        if($actionType == 'assigned')
        {
            $assignedTo = zget($object, 'assignedTo', '');
            if($assignedTo != $account && $role == 'to') $role = 'other';
            if($assignedTo == $account && $role != 'to') $role = 'to';

            $assignedTo = zget($users, $assignedTo);
        }

        $actorName      = $account == 'guest' ? 'guest' : zget($users, $account);
        $objectTypeName = zget($this->lang->action->objectTypes, $objectType);
        $actionName     = zget($this->lang->action->label, $actionType);

        $noticeText = zget($this->lang->message->noticeText, $objectType, $this->lang->message->noticeText->generic);
        if(isset($lang->message->noticeText->extend[$actionType][$role])) $noticeText = $lang->message->noticeText->extend[$actionType][$role];
        if($objectType == 'aitask' && in_array($actionType, array('finished', 'failed'))) $noticeText = zget($this->lang->message->noticeText, $objectType . $actionType, $this->lang->message->noticeText->generic);

        $noticeText     = str_replace(array('{actor}', '{action}', '{objectType}', '{objectTitle}'), array($actorName, $actionName, $objectTypeName, $objectTitle), $noticeText);
        $appendCCSuffix = !isset($lang->message->noticeText->extend[$actionType]['cc']);
        if($role == 'cc' && $appendCCSuffix) $noticeText .= $noticeText->ccSuffix;

        if(isset($assignedTo)) $noticeText = str_replace(array('{assignedTo}'), array($assignedTo), $noticeText);

        if($actionType == 'reviewed')
        {
            list($reviewResult, $reviewComment) = $this->getReviewResultAndComment($actionID);
            $noticeText = str_replace(array('{reviewResult}', '{reviewComment}'), array($reviewResult, $reviewComment), $noticeText);
        }

        return $noticeText;
    }

    /**
     * 获取评审结果和评审意见。
     * Get review result and comment.
     *
     * @param  int $actionID
     * @access protected
     * @return array
     */
    protected function getReviewResultAndComment(int $actionID): array
    {
        $action  = $this->loadModel('action')->getById($actionID);
        $result  = '';
        $comment = '';
        if($action)
        {
            $result  = (string)$action->extra;
            if(strpos($result, '|') !== false) $result = strtolower(explode('|', $result)[0]);
            $result  = zget($this->lang->message->reviewResultList, $result, '');
            $comment = trim(strip_tags((string)$action->comment));
        }
        return array($result, $comment);
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
        list('to' => $toList, 'cc' => $ccList) = $this->getToAndCcList($object, $objectType, $actionID);
        return trim($toList . ',' . $ccList, ',');
    }

    /**
     * 获取发送对象和抄送对象。
     * Get toList and ccList.
     *
     * @param  object $object
     * @param  string $objectType
     * @param  int    $actionID
     * @access public
     * @return array
     */
    public function getToAndCcList(object $object, string $objectType, int $actionID = 0): array
    {
        $toList = '';
        $ccList = '';

        if($objectType == 'aitask')
        {
            $toList = !empty($object->assignedTo) ? $object->assignedTo : ($object->createdBy ?? '');
            return array('to' => trim($toList, ','), 'cc' => '');
        }
        if($objectType == 'case') $objectType = 'testcase';

        $action      = $this->loadModel('action')->fetchById($actionID);
        $objectModel = $this->loadModel($objectType);
        if($objectModel && method_exists($objectModel, 'getToAndCcList'))
        {
            if($objectType == 'rule')
            {
                $toAndCcList = $objectModel->getToAndCcList($object, $action);
            }
            else
            {
                $toAndCcList = $objectModel->getToAndCcList($object, $action->action);
            }
            if(!empty($toAndCcList)) list($toList, $ccList) = $toAndCcList;
        }
        if(empty($toList) && $objectType == 'todo') $toList = $object->account;

        /* 非内置工作流使用工作流的toList。 */
        if($this->config->edition != 'open')
        {
            $flow    = $this->loadModel('workflow')->getByModule($objectType);
            $groupID = $this->loadModel('workflowgroup')->getGroupIDByDataID($objectType, $object->id);
            $method  = $this->loadModel('workflowaction')->getByModuleAndAction($objectType, $this->app->rawMethod, $groupID);
            if(($flow && !$flow->buildin) || ($method && !$method->buildin))
            {
                $toList = $this->loadModel('flow')->getToList($flow, $object->id, $method);
                $ccList = '';
            }
        }

        return array('to' => trim($toList, ','), 'cc' => $ccList);
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

        $expiredIdList = $this->dao->select('id')->from(TABLE_NOTIFY)->where('toList')->eq(",{$account},")->andWhere('objectType')->eq('message')->andWhere('createdDate')->lt($expiryDate)->fetchPairs('id', 'id');
        if(empty($expiredIdList)) return;

        $this->dao->delete()->from(TABLE_NOTIFY)->where('id')->in($expiredIdList)->exec();
    }

    /**
     * 从 html 中获取提及的用户。
     * Get mention users from html.
     *
     * @param  string $html
     * @access public
     * @return array
     */
    public function getMentionUsersFromHtml(string $html): array
    {
        /* TipTap may output data-type before class; do not rely on attribute order. */
        $pattern = '/<span(?=[^>]*\bmention-label\b)(?=[^>]*\bdata-type=["\']mention["\'])[^>]*\bdata-id=["\']([^"\']+)["\'][^>]*>/i';

        $accounts = array();
        if(preg_match_all($pattern, $html, $matches))
        {
            foreach($matches[1] as $match)
            {
                $account = trim($match);
                if($account) $accounts[$account] = $account;
            }
        }
        return array_keys($accounts);
    }

    /**
     * 从 BlockSuite 文档 JSON 中获取被 @ 的用户账号。
     * Get mention users from doc raw content.
     *
     * @param  string $rawContent
     * @access public
     * @return string[]
     */
    public function getMentionUsersFromDoc(string $rawContent): array
    {
        if(empty($rawContent)) return array();

        $data = json_decode($rawContent, true);
        if(empty($data)) return array();

        $callback = function(array $block, array $accounts) : array
        {
            if(empty($block['props']['text']['delta']) || !is_array($block['props']['text']['delta'])) return $accounts;

            $delta = $block['props']['text']['delta'];
            foreach($delta as $op)
            {
                if(empty($op['attributes']['mention']['id'])) continue;

                $account = trim($op['attributes']['mention']['id']);
                if(!empty($account)) $accounts[$account] = $account;
            }

            return $accounts;
        };

        $mentionUsers = $this->loadModel('doc')->forEachDocBlock($data, $callback, array(), 'affine:paragraph');

        return array_keys($mentionUsers);
    }

    /**
     * 根据表单设置获取被@的用户。
     * Extract mention uers from form.
     *
     * @param  array $formConfig
     * @param  object $object
     * @access public
     * @return array
     */
    public function extractMentionUsersFromForm(array $formConfig, object $object): array
    {
        $users = array();
        foreach($formConfig as $fieldKey => $fieldConfig)
        {
            if(isset($fieldConfig['control']) && $fieldConfig['control'] == 'editor' && !empty($object->$fieldKey))
            {
                $users = array_merge($users, $this->getMentionUsersFromHtml((string)$object->$fieldKey));
            }
        }

        return array_values(array_unique($users));
    }

    /**
     * 给被@的人发送消息通知。
     * Send notice to mention users.
     *
     * @param  string $objectType
     * @param  string $method
     * @param  int    $actionID
     * @param  object $object
     * @param  object $oldObject
     * @access public
     * @return void
     */
    public function sendMentionNotice(string $objectType, string $method, int $actionID, object $object, ?object $oldObject = null)
    {
        /* 文档正文用 rawContent 提取 @；备注等场景没有 rawContent，走表单提取。 */
        $isBlocksuite = $objectType == 'doc' && isset($object->rawContent);
        if($isBlocksuite)
        {
            $mentionUsers = $this->getMentionUsersFromDoc($object->rawContent);
            if($oldObject) $oldMentionUsers = $this->getMentionUsersFromDoc(isset($oldObject->rawContent) ? $oldObject->rawContent : '');
        }
        else
        {
            $module = $method == 'comment' ? 'action' : $objectType;
            if(empty($this->config->{$module}->form->{$method})) return;

            if($method == 'comment' && $objectType == 'story' && !empty($object->type)) $objectType = $object->type;

            $formConfig = $this->config->{$module}->form->{$method};

            $mentionUsers = $this->extractMentionUsersFromForm($formConfig, $object);
            if($oldObject) $oldMentionUsers = $this->extractMentionUsersFromForm($formConfig, $oldObject);
        }

        if(!empty($oldMentionUsers)) $mentionUsers = array_diff($mentionUsers, $oldMentionUsers);

        if(empty($mentionUsers)) return;

        $messageSetting = $this->config->message->setting;
        if(is_string($messageSetting)) $messageSetting = json_decode($messageSetting, true);
        if(empty($messageSetting)) return;

        $action = $this->loadModel('action')->getByID($actionID);
        if(!$action) return;


        if($objectType == 'testcase') $objectType = 'case';
        if($objectType == 'kanban')   $objectType = 'kanbancard';

        $settingObjectType = in_array($objectType, array('review', 'projectchange')) ? 'waterfall' : $objectType;

        $linkModule = $objectType;
        if($objectType == 'case')       $linkModule = 'testcase';
        if($objectType == 'kanbancard') $linkModule = 'kanban';

        $actor           = zget($action, 'actor', '');
        $user            = $this->loadModel('user')->getByID($actor);
        $actorRealname   = zget($user, 'realname', $actor);
        $objectNameField = zget($this->config->action->objectNameFields, $objectType, 'title');
        $objectTypeName  = zget($this->lang->action->objectTypes, $settingObjectType, zget($this->lang->action->objectTypes, $objectType, strtoupper($objectType)));
        $objectTitle     = $objectTypeName . '#' . sprintf("%03d", $object->id) . ($objectType != 'auditplan' ? zget($object, $objectNameField, '') : '');
        $viewLink        = $objectType == 'kanbancard' ? helper::createLink('kanban', 'viewCard', "cardID={$object->id}") : helper::createLink($linkModule, 'view', "id={$object->id}");

        if(isset($messageSetting['mail']))
        {
            $actions = $messageSetting['mail']['setting'];
            if(isset($actions[$settingObjectType]) && in_array('mentioned', $actions[$settingObjectType]))
            {
                $mailModule = in_array($objectType, array('story', 'requirement', 'epic')) ? 'story' : $linkModule;

                $mailObjectType = $objectType == 'kanbancard' ? 'kanbancard' : $mailModule;
                $mailObject     = $this->loadModel('mail')->getObjectForMail($mailObjectType, (int)$object->id);
                if($mailObject) $object = $mailObject;

                $subject     = sprintf($this->lang->message->mention, $actorRealname, $objectTitle);
                $mailContent = $this->mail->getMailContent($mailModule, $object, $action);
                $this->mail->send(implode(',', $mentionUsers), $subject, $mailContent);
            }
        }

        if(isset($messageSetting['message']))
        {
            $actions = $messageSetting['message']['setting'];
            if(isset($actions[$settingObjectType]) && in_array('mentioned', $actions[$settingObjectType]))
            {
                $data = sprintf($this->lang->message->mention, $actorRealname, html::a($viewLink, $objectTitle));
                $now  = helper::now();
                foreach($mentionUsers as $mentionUser)
                {
                    if($mentionUser == $actor || empty($mentionUser)) continue;

                    $notify = new stdclass();
                    $notify->objectType  = 'message';
                    $notify->action      = $actionID;
                    $notify->toList      = ",{$mentionUser},";
                    $notify->data        = $data;
                    $notify->status      = 'wait';
                    $notify->createdBy   = $actor;
                    $notify->createdDate = $now;
                    $notify->sendTime    = null;

                    $this->dao->insert(TABLE_NOTIFY)->data($notify)->exec();
                }
            }
        }

        if(isset($messageSetting['xuanxuan']))
        {
            $actions = $messageSetting['xuanxuan']['setting'];
            if(isset($actions[$settingObjectType]) && in_array('mentioned', $actions[$settingObjectType]))
            {
                $mentionUserIds = $this->dao->select('id')->from(TABLE_USER)->where('account')->in($mentionUsers)->andWhere('account')->ne($actor)->fetchPairs();

                if($mentionUserIds)
                {
                    $title      = sprintf($this->lang->message->mention, $actorRealname, $objectTitle);
                    $server     = $this->loadModel('im')->getServer('zentao');
                    $url        = $server . $viewLink;
                    $xxcUrl     = 'xxc:openInApp/zentao-integrated/' . urlencode($url);
                    $avatarUrl  = $server . $this->app->getWebRoot() . 'favicon.ico';
                    $senderName = zget($this->lang->message, 'sender', 'ZenTao');
                    $sender     = array('id' => 'zentao', 'realname' => $senderName, 'name' => $senderName, 'avatar' => $avatarUrl);

                    $this->loadModel('im')->messageCreateNotify($mentionUserIds, $title, '', '', 'text', $xxcUrl, array(), $sender);
                }
            }
        }

        if(isset($messageSetting['webhook']))
        {
            $actions = $messageSetting['webhook']['setting'];
            if(isset($actions[$settingObjectType]) && in_array('mentioned', $actions[$settingObjectType]))
            {
                $webhooks = $this->loadModel('webhook')->getList();
                if($webhooks)
                {
                    $title = sprintf($this->lang->message->mention, $actorRealname, $objectTitle);
                    foreach($webhooks as $id => $webhook)
                    {
                        if(strpos($webhook->type, 'group') !== false)  continue;

                        $host = empty($webhook->domain) ? common::getSysURL() : $webhook->domain;
                        $text = sprintf($this->lang->message->mention, $actorRealname, "[{$objectTitle}]({$host}{$viewLink})");
                        $data = $this->webhook->getDataByType($webhook, $action, $title, $text, '', '', $objectType, (int)$object->id);
                        if(!$data) continue;

                        if($webhook->sendType == 'async')
                        {
                            if($webhook->type == 'dinguser')
                            {
                                $openIdList = $this->webhook->getOpenIdList($webhook->id, $actionID);
                                if(empty($openIdList)) continue;
                            }

                            $this->webhook->saveData($id, $actionID, $data, $actor);
                            continue;
                        }

                        $result = $this->webhook->fetchHook($webhook, $data, $actionID, $mentionUsers);
                        if(!empty($result)) $this->webhook->saveLog($webhook, $actionID, $data, (string)$result);
                    }
                }
            }
        }
    }
}
