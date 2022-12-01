<?php

class zentaoBot extends xuanBot
{
    /* 机器人的显示名称 */
    public $name = '禅道';

    /* 机器人的代号 */
    public $code = 'zentao';

    /* 机器人的头像 URL，可以为空 */
    public $avatar = '';

    /* 机器人的命令列表，需要与实际命令函数名称一一对应 */
    public $commands = array();

    public $userArr = array();

    public $statusArr = array();

    public $condKeywords = array();

    /* 翻页参数匹配正则表达式 */
    public $pageSearchReg = '/(pageID|recPerPage|页码|每页数量|頁碼|每頁數量)=(\d+)/';

    /* 构造函数，会随 im 模块初始化 */
    public function __construct()
    {
        $this->commands[] = array('command' => 'view', 'alias' => array('查看', '搜索', '查询', '筛选'), 'description' => '查看任务', 'internal' => true);
        $this->commands[] = array('command' => 'start', 'alias' => array('开始', '开始任务'),'description' => '开始任务', 'internal' => true);
        $this->commands[] = array('command' => 'close', 'alias' => array('关闭', '关闭任务'), 'description' => '关闭任务', 'internal' => true);
        $this->commands[] = array('command' => 'finish', 'alias' => array('完成', '完成任务'), 'description' => '完成任务', 'internal' => true);

        $this->condKeywords['task']             = array('任务', 'task');
        $this->condKeywords['pri']              = array('优先级', 'pri');
        $this->condKeywords['status']           = array('状态', 'status');
        $this->condKeywords['assignTo']         = array('指派人', '指派给', 'assignto', 'user');
        $this->condKeywords['id']               = array('编号', 'id');
        $this->condKeywords['taskName']         = array('任务名', '任务名称', 'taskname');
        $this->condKeywords['comment']          = array('备注', 'comment');
        $this->condKeywords['left']             = array('预计剩余', 'left');
        $this->condKeywords['consumed']         = array('总计消耗', 'consumed');
        $this->condKeywords['realStarted']      = array('实际开始', 'realStarted');
        $this->condKeywords['pageID']           = array('pageID', '页码', '頁碼');
        $this->condKeywords['recPerPage']       = array('recPerPage', '每页数量', '每頁數量');
        $this->condKeywords['finishedDate']     = array('实际完成', 'finishedDate');
        $this->condKeywords['currentConsumed']  = array('本次消耗', 'currentConsumed');
    }

    /* 机器人初始化方法，可以在这里进行一些初始化，会在机器人被调用时执行 */
    public function init()
    {
        $this->im->loadModel('task');
        $this->im->loadModel('user');
        $this->im->app->loadClass('pager', $static = true);
        $this->userArr   = array_filter($this->im->user->getPairs('noclosed|noletter'));
        $this->statusArr = array_filter($this->im->lang->task->statusList);
    }

    public function close($args = array())
    {
        $args = $this->removeEqualSign($args);
        foreach($args as $key => $value)
        {
            if(in_array(strtolower($value), $this->condKeywords['task']))
            {
                unset($args[$key]);
            }
        }
        $task = $this->closeTask($args);
        return $task;
    }

    /**
     * Finish command.
     *
     * @param  array $args
     * @return object|string|void
     */
    public function finish($args = array())
    {
        $originArgs = $args;
        $args = array_filter(explode('=', implode('=', $args)));
        foreach($args as $key => $value)
        {
            if(in_array(strtolower($value), $this->condKeywords['task']))
            {
                unset($args[$key]);
            }
        }
        return $this->finishTask($args, $originArgs);
    }

    public function view($args = array(), $userID = 0, $user = null)
    {
        $pager = pager::init(0, 10, 1);

        $args = array_filter($args, function ($arg) use ($pager) {
            $matches = array();
            preg_match($this->pageSearchReg, $arg, $matches);
            if(count($matches) > 2)
            {
                if(in_array(strtolower($matches[1]), $this->condKeywords['pageID']))
                {
                    $pager->pageID = $matches[2];
                }
                else
                {
                    $pager->recPerPage = $matches[2];
                }
                return false;
            }
            return true;
        });
        $originArgs = $args;

        $args = $this->removeEqualSign($args);

        foreach($args as $key => $value)
        {
            if(in_array(strtolower($value), $this->condKeywords['task']))
            {
                unset($args[$key]);
                $tasks = $this->viewTask($args, $user, $pager);
                return $this->renderTask($tasks, $originArgs, $pager);
            }
        }
    }

    public function parseArguments($args = array(), $keys = array())
    {
        $assignedToList = '';
        $priList        = '';
        $statusList     = '';
        $idList         = '';
        $taskName       = '';
        $comment        = '';
        while(!empty($args))
        {
            $arg = array_shift($args);
            if($keys['pri'])
            {
                if(in_array(strtolower($arg), $this->condKeywords['pri']))
                {
                    $pris = array_shift($args);
                    $priList = $this->buildCondParamByModifier($pris, $priList, 'p');
                    continue;
                }
                if(preg_match('/p[1-4]/i', $arg) == 1)
                {
                    $priList = $this->buildCondParamByModifier($arg, $priList, 'p');
                    continue;
                }
            }
            if($keys['id'])
            {
                if(in_array(strtolower($arg), $this->condKeywords['id']))
                {
                    $ids = array_shift($args);
                    $idList .= $this->buildCondParamByModifier($ids, $idList, '#');
                    continue;
                }
                if(preg_match('/#\d+/', $arg) == 1)
                {
                    $idList .= $this->buildCondParamByModifier($arg, $idList, '#');
                    continue;
                }
            }
            if($keys['status'])
            {
                if(in_array(strtolower($arg), $this->condKeywords['status']))
                {
                    $statuses = array_shift($args);
                    $statusList = $this->buildCondParam($statuses, $statusList, $this->statusArr);
                    continue;
                }
                if($this->checkCondType($arg, $this->statusArr))
                {
                    $statusList = $this->buildCondParam($arg, $statusList, $this->statusArr);
                    continue;
                }
            }
            if($keys['assignTo'])
            {
                if(in_array(strtolower($arg), $this->condKeywords['assignTo']))
                {
                    $usernames = array_shift($args);
                    $assignedToList = $this->buildCondParam($usernames, $assignedToList, $this->userArr);
                    continue;
                }
                if($this->checkCondType($arg, $this->userArr))
                {
                    $assignedToList = $this->buildCondParam($arg, $assignedToList, $this->userArr);
                    continue;
                }
            }
            if($keys['comment'])
            {
                if(in_array(strtolower($arg), $this->condKeywords['comment']))
                {
                    $comment = array_shift($args);
                }
                else {
                    $comment = $arg;
                }
                continue;
            }
            if($keys['taskName'])
            {
                if(in_array(strtolower($arg), $this->condKeywords['taskName']))
                {
                    $taskName = array_shift($args);
                }
                else
                {
                    $taskName = $arg;
                }
            }
        }

        $conds = new stdClass();
        if($keys['pri'])      $conds->priList        = trim($priList, ',');
        if($keys['assignTo']) $conds->assignedToList = trim($assignedToList, ',');
        if($keys['status'])   $conds->statusList     = trim($statusList, ',');
        if($keys['id'])       $conds->idList         = trim($idList, ',');
        if($keys['taskName']) $conds->taskName       = $taskName;
        if($keys['comment'])  $conds->comment        = $comment;
        return $conds;
    }

    public function checkCondType($arg, $list)
    {
        $args = explode(',', $arg);
        $array_inter = array_intersect($args, $list);
        if(!empty($array_inter)) return true;

        $list = array_flip($list);
        $array_inter = array_intersect($args, $list);
        if(!empty($array_inter)) return true;

        return false;
    }

    public function buildCondParam($arg, $condParam, $condArr)
    {
        $arr = explode(',', $arg);
        foreach($arr as $cond)
        {
            if(array_key_exists($cond, $condArr))
            {
                $condParam .= ',' . $cond;
            }
            else
            {
                $key = array_search($cond, $condArr);
                if($key !== false) $condParam .= ',' . $key;
            }
        }
        return $condParam;
    }

    public function removeEqualSign($args)
    {
        return array_filter(explode('=', implode('=', $args)));
    }

    public function buildCondParamByModifier($arg, $condParam, $modifier)
    {
        $arg = strtolower($arg);
        $arg = str_replace($modifier, '', $arg);
        $condParam .= ',' . $arg;
        return $condParam;
    }

    public function viewTask($args = array(), $user = null, $pager = null)
    {
        if(empty($args))
        {
            $conds = new stdClass();
            $conds->assignedToList = is_object($user) ? $user->account : false;
            $conds->statusList     = 'wait,doing,done,pause,cancel';
            return $this->im->task->getListByConds($conds, 'status_asc', 0, $pager);
        }

        $keys             = array();
        $keys['pri']      = true;
        $keys['id']       = true;
        $keys['status']   = true;
        $keys['assignTo'] = true;
        $keys['taskName'] = true;

        $conds = $this->parseArguments($args, $keys);
        return $this->im->task->getListByConds($conds, 'status_asc', 0, $pager);
    }

    public function closeTask($args)
    {
        $keys            = array();
        $keys['id']      = true;
        $keys['comment'] = true;

        $conds   = $this->parseArguments($args, $keys);
        $taskID  = array_pop(array_filter(explode(',', $conds->idList)));
        $comment = $conds->comment;

        if(!is_numeric($taskID)) return "无法识别该指令";
        $task = $this->loadEntry('task', 'get', array('taskID'=> $taskID));
        if(empty($task)) return "未查询到相关匹配信息";
        if($task->status != 'done' && $task->status != 'cancel') return "检测到该任务为{$this->statusArr[$task->status]}状态，无法实现指令操作";
        $task = $this->loadEntry('taskclose', 'post', array('taskID' => $taskID, 'comment' => $comment));
        if($task->result == 'fail') return $task->message;

        $link = str_replace('x.php', 'index.php', helper::createLink('task', 'view', "taskID=$taskID", 'html'));
        $messages = new stdClass();
        $messages->type = 'url';
        $messages->url  = common::getSysURL() . $link;
        return array('指令执行完成', $messages);
    }

    /**
     * Finish Task.
     *
     * @param  array $args
     * @param  array $originArgs
     * @return stdClass|string
     */
    private function finishTask($args, $originArgs)
    {
        $taskID = '';
        $params = array();
        while(!empty($args))
        {
            $arg = array_shift($args);
            if(in_array(strtolower($arg), $this->condKeywords['id']))
            {
                $taskID = array_shift($args);
                $taskID = str_replace('#', '', $taskID);
            }
            elseif(preg_match('/#\d+/', $arg) == 1)
            {
                $taskID = str_replace('#', '', $arg);
            }
            else
            {
                parse_str(str_replace('amp;', '', end($originArgs)), $params);
            }
        }

        if(!is_numeric($taskID)) return "无法识别该指令";
        $task = $this->loadEntry('task', 'get', array('taskID'=> $taskID));
        if(!common::hasPriv('task', 'finish')) return '您无权操作此任务';
        if(empty($task) || isset($task->error)) return "未查询到相关匹配信息";
        if($task->status != 'wait' && $task->status != 'doing') return "检测到该任务为{$this->statusArr[$task->status]}状态，无法实现指令操作";

        $reply = new stdClass();
        $reply->messages  = array();
        $reply->responses = array();

        $realStarted   = $this->formatDate($task, 'realStarted');
        $finishedDate  = $this->formatDate($task, 'finishedDate');

        if(empty($params))
        {
            $originCommand = rawurlencode('完成 ' . implode(' ', $originArgs));

            $json = (object)array
            (
                'title'  => "#{$taskID} {$task->name}",
                'submitLabel' => $this->im->lang->task->finish,
                'inputs' =>
                    array(
                        (object)array
                        (
                            'name'     => $this->im->lang->task->hasConsumed,
                            'type'     => 'input',
                            'label'    => $this->im->lang->task->hasConsumed,
                            'value'    => $task->consumed,
                            'disabled' => true,
                            'addon'    => $this->im->lang->task->hour,
                        ),
                        (object)array
                        (
                            'name'     => $this->im->lang->task->currentConsumed,
                            'type'     => 'input',
                            'label'    => $this->im->lang->task->currentConsumed,
                            'value'    => 0,
                            'addon'    => $this->im->lang->task->hour,
                        ),
                        (object)array
                        (
                            'name'  => $this->im->lang->task->realStarted,
                            'type'  => 'datetime-local',
                            'label' => $this->im->lang->task->realStarted,
                            'value' => $realStarted,
                        ),
                        (object)array
                        (
                            'name'  => $this->im->lang->task->finishedDate,
                            'type'  => 'datetime-local',
                            'label' => $this->im->lang->task->finishedDate,
                            'value' => $finishedDate,
                        ),
                    ),
            );
            $json = rawurlencode(json_encode($json));

            $reply->messages[] = '完成任务指令需要填入工时与记录起始时间，请点击下方入口';
            $reply->messages[] = "[工时记录](xxc://openFormAndSendToServerBySendbox/$json/$originCommand)";
        }
        else
        {
            $messageID = isset($params['messageId']) ? $params['messageId'] : '';
            unset($params['messageId']);

            $currentConsumed = 0;

            foreach($params as $key => $value)
            {
                if(in_array(strtolower($key), $this->condKeywords['realStarted']))
                {
                    $realStarted = $value;
                }
                if(in_array(strtolower($key), $this->condKeywords['finishedDate']))
                {
                    $finishedDate = $value;
                }
                if(in_array(strtolower($key), $this->condKeywords['currentConsumed']))
                {
                    $currentConsumed = $value;
                }
            };

            if(empty($realStarted) || empty($finishedDate)) return '';

            $params = array
            (
                'currentConsumed'   => $currentConsumed,
                'realStarted'       => $realStarted,
                'finishedDate'      => $finishedDate,
            );

            $task = $this->loadEntry('taskfinish', 'post', array_merge(array('taskID' => $taskID, 'assignedTo' => '', 'comment' => ''), $params));
            if(isset($task->error))
            {
                foreach($task->error as $error) $reply->messages[] = is_array($error) ? implode('，', $error): $error;
                return $reply;
            }

            $finishedDate = $this->formatDate($task, 'finishedDate', '', 'Y-m-d H:i:s');

            $reply->messages[] = "任务 #{$taskID} 已完成，完成时间：{$finishedDate}，消耗：{$task->consumed} 小时";
            $reply->messages[] = $this->renderTaskTable(array($task));

            $reply->responses[] = $this->disableMarkdownLinkInMessage($messageID);

            if(!empty($task->fromBug))
            {
                $reply->messages[] = "检测到任务 #{$taskID} 关联相关Bug，您可以点击以下链接进行处理";
                $link = commonModel::getSysURL() . str_replace('x.php', 'index.php', helper::createLink('bug', 'view', "bugID={$task->fromBug}", 'html'));
                $href = urlencode($link);
                $reply->messages[] = "[关联Bug处理](xxc:openInApp/zentao-integrated/{$href})";
            }
        }

        return $reply;
    }

    /**
     * Format date.
     *
     * @param  object $task
     * @param  string $field
     * @param  string $default
     * @param  string $format
     * @return string
     */
    private function formatDate($task, $field, $default = '', $format = 'Y-m-d\TH:i')
    {
        try
        {
            $datetime = new DateTime(empty($task->$field) ? 'now' : $task->$field, new DateTimeZone('UTC'));
            $datetime->setTimezone(new DateTimeZone(date_default_timezone_get()));
            return $datetime->format($format);
        }
        catch(Exception $e)
        {
            return $default;
        }
    }

    /**
     * Render tasks as table html.
     *
     * @param  array    $tasks
     * @param  array    $originArgs
     * @param  pager    $pager
     * @return string|array
     */
    public function renderTask($tasks, $originArgs, $pager)
    {
        $lang = $this->im->lang;
        if(!$tasks || $pager->recTotal === 0) return $lang->task->noTask;

        $sysURL = common::getSysURL();

        if($pager->recTotal == 1)
        {
            $task = current($tasks);
            $link = str_replace('x.php', 'index.php', helper::createLink('task', 'view', "taskID=$task->id", 'html'));

            $messages = new stdclass();
            $messages->type = 'url';
            $messages->url  = $sysURL . $link;
            return array("为您匹配到 $pager->recTotal 项任务。", $messages);
        }

        $messages = array();
        if($pager->pageID == 1)
        {
            $messages[] = "为您匹配到 $pager->recTotal 项任务。";
        }

        $taskTable = $this->renderTaskTable($tasks);

        $originCommand = '查看 ' . implode(' ', $originArgs);
        $paging        = "$pager->pageID / $pager->pageTotal";
        if($pager->pageID != 1)
        {
            $previousPageCommand = $originCommand . " {$this->condKeywords['pageID'][1]}=" . ($pager->pageID - 1) . " {$this->condKeywords['recPerPage'][1]}=" . $pager->recPerPage;
            $previousPageCommand = rawurlencode($previousPageCommand);
            $paging .= " [上一页](xxc://sendContentToServerBySendbox/{$previousPageCommand})";
        }

        if($pager->pageID != $pager->pageTotal)
        {
            $nextPageCommand = $originCommand . " {$this->condKeywords['pageID'][1]}=" . ($pager->pageID + 1) . " {$this->condKeywords['recPerPage'][1]}=" . $pager->recPerPage;
            $nextPageCommand = rawurlencode($nextPageCommand);
            $paging .= " [下一页](xxc://sendContentToServerBySendbox/{$nextPageCommand})";
        }
        $paging = "<div class=\"text-right\">$paging</div>";

        $messages[] = $taskTable . $paging;

        return $messages;
    }

    /**
     * render tasks as markdown table.
     * @param array $tasks
     * @return void
     */
    public function renderTaskTable($tasks)
    {
        $lang    = $this->im->lang;
        $sysURL  = common::getSysURL();
        $headMap = array('id'=>'ID', 'pri' => $lang->task->pri, 'name' => $lang->task->name, 'assignedTo' => $lang->task->assignedTo,  'status' => $lang->task->status, 'estimate' => $lang->task->estimateAB, 'consumed' => $lang->task->consumedAB, 'left' => $lang->task->leftAB, 'actions' => $lang->actions);
        $thead   = '';
        foreach ($headMap as $value)
        {
            $thead .= "<th>{$value}</th>";
        }
        $thead = "<thead class='text-nowrap'><tr>{$thead}</tr></thead>";
        $tbody = '';
        foreach ($tasks as $task)
        {
            $tr   = '';
            $link = str_replace('x.php', 'index.php', helper::createLink('task', 'view', "taskID=$task->id", 'html'));
            $href = urlencode($sysURL . $link);
            foreach ($headMap as $key => $value) {
                switch($key)
                {
                    case 'name':
                        $tr .= "<td><a href='xxc:openInApp/zentao-integrated/{$href}'>{$task->$key}</a></td>";
                    break;
                    case 'status':
                        $tr .= "<td class='text-nowrap'>{$this->statusArr[$task->$key]}</td>";
                    break;
                    case 'assignedTo':
                        if($task->mode == 'multi')
                        {
                            $tr .= "<td class='text-nowrap'>{$lang->task->team}</td>";
                        }
                        else if($task->$key == 'closed')
                        {
                            $tr .= "<td class='text-nowrap'>Closed</td>";
                        }
                        else
                        {
                            $tr .= "<td class='text-nowrap'>{$this->userArr[$task->$key]}</td>";
                        }
                    break;
                    case 'actions':
                        $startUrl  = 'xxc://sendContentToServerBySendbox/' . "开始 任务 #$task->id";
                        $finishUrl = 'xxc://sendContentToServerBySendbox/' . "完成 任务 #$task->id";
                        $closeUrl  = 'xxc://sendContentToServerBySendbox/' . "关闭 任务 #$task->id";
                        switch($task->status)
                        {
                            case 'wait':
                            case 'pause':
                                $tr .= '<td><div class="flex gap-xs">';
                                $tr .= "<a href='$startUrl'><i class='icon-zt-play'></i></a>";
                                $tr .= "<a href='$finishUrl'><i class='icon-zt-checked'></i></a>";
                                $tr .= "<div><i class='icon-zt-off disabled'></i></div>";
                                $tr .= '</div></td>';
                            break;
                            case 'doing':
                                $tr .= '<td><div class="flex gap-xs">';
                                $tr .= "<div><i class='icon-zt-play disabled'></i></div>";
                                $tr .= "<a href='$finishUrl'><i class='icon-zt-checked'></i></a>";
                                $tr .= "<div><i class='icon-zt-off disabled'></i></div>";
                                $tr .= '</div></td>';
                            break;
                            case 'done':
                            case 'cancel':
                                $tr .= '<td><div class="flex gap-xs">';
                                $tr .= "<div><i class='icon-zt-play disabled'></i></div>";
                                $tr .= "<div><i class='icon-zt-checked disabled'></i></div>";
                                $tr .= "<a href='$closeUrl'><i class='icon-zt-off'></i></a>";
                                $tr .= '</div></td>';
                            break;
                            case 'closed':
                                $tr .= '<td><div class="flex gap-xs">';
                                $tr .= "<div><i class='icon-zt-play disabled'></i></div>";
                                $tr .= "<div><i class='icon-zt-checked disabled'></i></div>";
                                $tr .= "<div><i class='icon-zt-off disabled'></i></div>";
                                $tr .= '</div></td>';
                            break;
                        }
                    break;
                    case 'estimate':
                    case 'consumed':
                    case 'left':
                        $tr .= "<td class='text-nowrap'>{$task->$key}h</td>";
                        break;
                    default:
                        $tr .= "<td class='text-nowrap'>{$task->$key}</td>";
                    break;
                }
            }
            $tbody .= "<tr>{$tr}</tr>";
        }
        return "<table>{$thead}{$tbody}</table>";
    }

    public function start($args = array(), $userID = 0)
    {
        $originArgs = $args;
        foreach($args as $key => $value)
        {
            if(in_array(strtolower($value), $this->condKeywords['task']))
            {
                unset($args[$key]);
            }
        }

        if(count($args) == 0) return '请输入任务编号';

        $taskID = 0;
        foreach($args as $key => $value)
        {
            if(strpos($value, '#') !== false)
            {
                $id = str_replace('#', '', $value);
                if($id && is_numeric($id))
                {
                    $taskID = $id;
                    unset($args[$key]);
                }
                else
                {
                    if($args[$key + 1] && is_numeric($args[$key + 1]))
                    {
                        $taskID = $args[$key + 1];
                        unset($args[$key]);
                        unset($args[$key + 1]);
                    }
                }
            }
        }
        if(!$taskID) return '请输入任务编号';

        $task = $this->loadEntry('task', 'get', array('taskID' => $taskID));
        if(!$task) return '任务不存在';
        if($task->status != 'wait') return "检测到该任务为{$this->statusArr[$task->status]}状态，无法实现指令操作";

        $reply = new stdClass();
        $reply->messages  = array();
        $reply->responses = array();

        if(count($args) == 0)
        {
            $startByDefault = true;

            $originCommand = rawurlencode('开始 ' . implode(' ', $originArgs));

            $realStarted = $this->formatDate($task, 'realStarted');

            $json = (object)array
            (
                'title'  => "#{$taskID} {$task->name}",
                'submitLabel' => $this->im->lang->task->start,
                'inputs' => array
                (
                            (object)array
                            (
                                'name'  => $this->im->lang->task->realStarted,
                                'type'  => 'datetime-local',
                                'label' => $this->im->lang->task->realStarted,
                                'value' => $realStarted,
                            ),
                            (object)array
                            (
                                'name'     => $this->im->lang->task->consumed,
                                'type'     => 'number',
                                'label'    => $this->im->lang->task->consumed,
                                'value'    => $task->consumed,
                                'addon'    => $this->im->lang->task->hour,
                            ),
                            (object)array
                            (
                                'name'     => $this->im->lang->task->left,
                                'type'     => 'number',
                                'label'    => $this->im->lang->task->left,
                                'value'    => $task->left,
                                'addon'    => $this->im->lang->task->hour,
                                'helpText' => '"剩余"为0，任务将标记为"已完成"',
                            )
                ),
            );
            $json = rawurlencode(json_encode($json));

            $reply->messages[] = '点击链接开始任务 #'.$taskID;
            $reply->messages[] = "[开始任务](xxc://openFormAndSendToServerBySendbox/$json/$originCommand)";
        }
        else
        {
            $hasFormArgs = false;

            $left = $task->left;
            $consumed = $task->consumed;
            $realStarted = $task->realStarted;

            foreach($args as $arg)
            {
                if(strpos($arg, 'messageId'))
                {
                    $hasFormArgs = true;
                    $formArgs = explode('&', $arg);
                    $replyArgs = array();
                    foreach($formArgs as $formArg)
                    {
                        $item = explode('=', $formArg);
                        if(count($item) == 2)
                        {
                            $replyArgs[$item[0]] = $item[1];
                        }
                    }
                    foreach($replyArgs as $key => $value)
                    {
                        if(in_array(strtolower($key), $this->condKeywords['consumed']))
                        {
                            $consumed = $value;
                        }
                        if(in_array(strtolower($key), $this->condKeywords['realStarted']))
                        {
                            $realStarted = $value;
                        }
                        if(in_array(strtolower($key), $this->condKeywords['left']))
                        {
                            $left = $value;
                        }
                    }
                    if($consumed == 0 && $left == 0) return $this->im->lang->task->noticeTaskStart;

                    $task = $this->loadEntry('taskStart', 'post', array
                    (
                        'taskID' => $taskID,
                        'left' => $left,
                        'realStarted' => $realStarted,
                        'consumed' => $consumed,
                    ));
                    if($task->result and $task->result == 'fail')
                    {
                        $reply->messages[] = $task->message;
                    }
                    elseif(isset($task->error))
                    {
                        foreach($task->error as $error) $reply->messages[] = is_array($error) ? implode('，', $error): $error;
                    }
                    else
                    {
                        $reply->messages[] = '任务 #'.$taskID.' 已完成工时信息填写';
                        $reply->messages[] = $this->renderTaskTable(array($task));

                        $reply->responses[] = $this->disableMarkdownLinkInMessage($replyArgs['messageId']);
                    }
                }
            }

            if(!$hasFormArgs)
            {

                while(!empty($args))
                {
                    $arg = array_shift($args);
                    if(in_array(strtolower($arg), $this->condKeywords['left']))
                    {
                        $left = array_shift($args);
                    }
                    elseif(in_array(strtolower($arg), $this->condKeywords['consumed']))
                    {
                        $consumed = array_shift($args);
                    }
                    elseif(in_array(strtolower($arg), $this->condKeywords['realStarted']))
                    {
                        $realStarted = array_shift($args);
                    }
                }

                $task = $this->loadEntry('taskStart', 'post', array
                (
                    'taskID' => $taskID,
                    'left' => $left,
                    'realStarted' => $realStarted,
                    'consumed' => $consumed,
                ));
                if($task->result and $task->result == 'fail')
                {
                    $reply->messages[] = $task->message;
                }
                elseif(isset($task->error))
                {
                    foreach($task->error as $error) $reply->messages[] = is_array($error) ? implode('，', $error): $error;
                }
                else
                {
                    $reply->messages[] = '任务 #'.$taskID.' 已完成工时信息填写';
                    $reply->messages[] = $this->renderTaskTable(array($task));
                }
            }
        }

        return $reply;
    }

    /**
     * remove a Markdown link in the message and generate a response.
     * @param $messageID
     * @return stdclass
     */
    private function disableMarkdownLinkInMessage($messageID)
    {
        if(!$messageID) return null;
        $message = $this->im->message->getList('', array($messageID));
        $message = current($message);
        if($message->user != 'xuanbot') return null;
        $message->content = preg_replace('/\[(.*?)\]\(.*?\)/', '$1', $message->content);
        $this->im->message->setMessage($message);

        $userID = array_filter(explode('&', $message->cgid), function($item)
        {
            return is_numeric($item);
        });

        $response = new stdclass();
        $response->result = 'success';
        $response->method = 'messageUpdate';
        $response->users  = $userID;
        $response->data   = array($message);

        return $response;
    }
}


