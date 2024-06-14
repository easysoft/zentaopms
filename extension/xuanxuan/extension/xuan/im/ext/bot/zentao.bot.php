<?php
class zentaoBot extends xuanBot
{
    /**
     * Bot name.
     *
     * @var    string
     * @access public
     */
    public $name = 'ZenTao';

    /**
     * Bot codename.
     *
     * @var    string
     * @access public
     */
    public $code = 'zentao';

    /**
     * Command list, inits in init() since all commands are internal commands.
     *
     * @var    array
     * @access public
     */
    public $commands = array();

    /**
     * Construct function, load lang of zentao and setup commands.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        if(isset($_SESSION['clientLang']))
        {
            if($_SESSION['clientLang'] == 'zh-cn') $this->name = '禅道';
            if($_SESSION['clientLang'] == 'zh-tw') $this->name = '禪道';
        }
    }

    /**
     * Init bot, load model.
     *
     * @access public
     * @return void
     */
    public function init()
    {
        $this->lang = $this->im->lang->im->bot->zentaoBot;

        foreach(array('view', 'start', 'close', 'finish') as $command)
        {
            $this->commands[] = array('command' => $command, 'alias' => $this->lang->commands->$command->alias, 'description' => $this->lang->commands->$command->description, 'internal' => true);
        }

        /* Backup user model of im module, load user module and restore. Use `$this->userModel` as user module from now on. */
        $imUser = $this->im->user;
        $this->userModel = $this->im->loadModel('user');
        $this->im->user = $imUser;

        $this->im->loadModel('task');
        $this->im->app->loadClass('pager', $static = true);

        $this->users          = array_filter($this->userModel->getPairs('noclosed|noletter'));
        $this->taskStatusList = array_filter($this->im->lang->task->statusList);
        $this->help           = $this->lang->help;

        $this->inited = true;
    }

    /**
     * Close command.
     *
     * @param  array         $args  bot command arguments
     * @access public
     * @return object|string
     */
    public function close($args = array())
    {
        $args = $this->removeEqualSign($args);
        foreach($args as $key => $value)
        {
            if(in_array(strtolower($value), $this->lang->condKeywords['task'])) unset($args[$key]);
        }
        $task = $this->closeTask($args);
        return $task;
    }

    /**
     * Finish command.
     *
     * @param  array         $args  bot command arguments
     * @access public
     * @return object|string
     */
    public function finish($args = array())
    {
        $originArgs = $args;
        $args = array_filter(explode('=', implode('=', $args)));
        foreach($args as $key => $value)
        {
            if(in_array(strtolower($value), $this->lang->condKeywords['task'])) unset($args[$key]);
        }
        return $this->finishTask($args, $originArgs);
    }

    /**
     * View command.
     *
     * @param  array         $args   bot command arguments
     * @param  int           $userID
     * @param  object        $user
     * @access public
     * @return object|string
     */
    public function view($args = array(), $userID = 0, $user = null)
    {
        $pager = pager::init(0, 10, 1);

        $pageSearchRegex = $this->lang->pageSearchRegex;
        $pageIDKeywords  = $this->lang->condKeywords['pageID'];

        $args = array_filter($args, function($arg) use ($pager, $pageSearchRegex, $pageIDKeywords)
        {
            $matches = array();
            preg_match($pageSearchRegex, $arg, $matches);
            if(count($matches) > 2)
            {
                if(in_array(strtolower($matches[1]), $pageIDKeywords))
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
            if(in_array(strtolower($value), $this->lang->condKeywords['task']))
            {
                unset($args[$key]);
                $tasks = $this->viewTask($args, $user, $pager);
                return $this->renderTask($tasks, $originArgs, $pager);
            }
        }
    }

    /**
     * Parse bot command arguments.
     *
     * @param  array         $args   bot command arguments
     * @param  array         $keys
     * @access public
     * @return object
     */
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
            if(isset($keys['pri']))
            {
                if(in_array(strtolower($arg), $this->lang->condKeywords['pri']))
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
            if(isset($keys['id']))
            {
                if(in_array(strtolower($arg), $this->lang->condKeywords['id']))
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
            if(isset($keys['status']))
            {
                if(in_array(strtolower($arg), $this->lang->condKeywords['status']))
                {
                    $statuses = array_shift($args);
                    $statusList = $this->buildCondParam($statuses, $statusList, $this->taskStatusList);
                    continue;
                }
                if($this->checkCondType($arg, $this->taskStatusList))
                {
                    $statusList = $this->buildCondParam($arg, $statusList, $this->taskStatusList);
                    continue;
                }
            }
            if(isset($keys['assignTo']))
            {
                if(in_array(strtolower($arg), $this->lang->condKeywords['assignTo']))
                {
                    $usernames = array_shift($args);
                    $assignedToList = $this->buildCondParam($usernames, $assignedToList, $this->users);
                    continue;
                }
                if($this->checkCondType($arg, $this->users))
                {
                    $assignedToList = $this->buildCondParam($arg, $assignedToList, $this->users);
                    continue;
                }
            }
            if(isset($keys['comment']))
            {
                if(in_array(strtolower($arg), $this->lang->condKeywords['comment']))
                {
                    $comment = array_shift($args);
                }
                else {
                    $comment = $arg;
                }
                continue;
            }
            if(isset($keys['taskName']))
            {
                if(in_array(strtolower($arg), $this->lang->condKeywords['taskName']))
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
        if(isset($keys['pri']))      $conds->priList        = trim($priList, ',');
        if(isset($keys['assignTo'])) $conds->assignedToList = trim($assignedToList, ',');
        if(isset($keys['status']))   $conds->statusList     = trim($statusList, ',');
        if(isset($keys['id']))       $conds->idList         = trim($idList, ',');
        if(isset($keys['taskName'])) $conds->taskName       = $taskName;
        if(isset($keys['comment']))  $conds->comment        = $comment;
        return $conds;
    }

    /**
     * Check condition type.
     *
     * @param  string        $arg
     * @param  array         $list
     * @access public
     * @return bool
     */
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

    /**
     * Build condition param.
     *
     * @param  string        $arg    bot command arguments
     * @param  string        $condParam
     * @param  array         $condArr
     * @access public
     * @return string
     */
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

    /**
     * Remove equal sign from bot command arguments.
     *
     * @param  array         $args   bot command arguments
     * @access public
     * @return array
     */
    public function removeEqualSign($args)
    {
        return array_filter(explode('=', implode('=', $args)));
    }

    /**
     * Build condition param by modifier.
     *
     * @param  array         $args      bot command arguments
     * @param  string        $condParam
     * @param  string        $modifier
     * @access public
     * @return string
     */
    public function buildCondParamByModifier($arg, $condParam, $modifier)
    {
        $arg = strtolower($arg);
        $arg = str_replace($modifier, '', $arg);
        $condParam .= ',' . $arg;
        return $condParam;
    }

    /**
     * View task command.
     *
     * @param  array         $args   bot command arguments
     * @param  object        $user
     * @param  object        $pager
     * @access public
     * @return array
     */
    public function viewTask($args = array(), $user = null, $pager = null)
    {
        $keys = array();
        foreach(array('pri', 'id', 'status', 'assignTo', 'taskName') as $key) $keys[$key] = true;

        if(empty($args))
        {
            $args['assignTo'] = is_object($user) ? $user->account : false;
            $args['status']   = 'wait,doing,done,pause,cancel';
        }

        $conds = $this->parseArguments($args, $keys);
        $conds->search = 1;
        $conds->order  = 'status_asc';
        $conds->limit  = 10;

        if(is_object($pager))
        {
            $conds->page  = $pager->pageID;
            $conds->limit = $pager->recPerPage;
        }

        $result = $this->loadEntry('tasks', 'get', array(), (array)$conds);

        if(isset($result->tasks))
        {
            $pager->setRecTotal($result->total);
            $pager->setRecPerPage($result->limit);
            $pager->setPageID($result->page);
            $pager->setPageTotal();
            return $result->tasks;
        }

        return array();
    }

    /**
     * Close task command.
     *
     * @param  array         $args   bot command arguments
     * @access public
     * @return object|string
     */
    public function closeTask($args)
    {
        $keys            = array();
        $keys['id']      = true;
        $keys['comment'] = true;

        $conds   = $this->parseArguments($args, $keys);
        $tasks   = array_filter(explode(',', $conds->idList));
        $taskID  = array_pop($tasks);
        $comment = $conds->comment;

        if(!is_numeric($taskID)) return $this->lang->errors->invalidCommand;

        $task = $this->loadEntry('task', 'get', array('taskID'=> $taskID));
        if(empty($task)) return $this->lang->errors->emptyResult;

        if($task->status != 'done' && $task->status != 'cancel') return sprintf($this->lang->errors->invalidStatus, $this->taskStatusList[$task->status]);

        $task = $this->loadEntry('taskclose', 'post', array('taskID' => $taskID, 'comment' => $comment));
        if(isset($task->result) && $task->result == 'fail') return $task->message;

        $link = str_replace('x.php', 'index.php', helper::createLink('task', 'view', "taskID=$taskID", 'html'));
        $messages = new stdClass();
        $messages->type = 'url';
        $messages->url  = common::getSysURL() . $link;
        return array($this->lang->success, $messages);
    }

    /**
     * Finish task.
     *
     * @param  array         $args
     * @param  array         $originArgs
     * @access public
     * @return object|string
     */
    private function finishTask($args, $originArgs)
    {
        $taskID = '';
        $params = array();
        while(!empty($args))
        {
            $arg = array_shift($args);
            if(in_array(strtolower($arg), $this->lang->condKeywords['id']))
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

        if(!is_numeric($taskID)) return $this->lang->errors->invalidCommand;

        $task = $this->loadEntry('task', 'get', array('taskID'=> $taskID));
        if(!common::hasPriv('task', 'finish')) return $this->lang->errors->unauthorized;

        if(empty($task) || isset($task->error)) return $this->lang->errors->emptyResult;
        if($task->status != 'wait' && $task->status != 'doing') return sprintf($this->lang->errors->invalidStatus, $this->taskStatusList[$task->status]);

        $reply = new stdClass();
        $reply->messages  = array();
        $reply->responses = array();

        $realStarted   = $this->formatDate($task, 'realStarted');
        $finishedDate  = $this->formatDate($task, 'finishedDate');

        if(empty($params))
        {
            $originCommand = rawurlencode($this->lang->finishCommand . ' ' . implode(' ', $originArgs));

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

            $reply->messages[] = $this->lang->finish->tip;
            $reply->messages[] = "[{$this->lang->finish->tipLinkTitle}](xxc://openFormAndSendToServerBySendbox/$json/$originCommand)";
        }
        else
        {
            $messageID = isset($params['messageId']) ? $params['messageId'] : '';
            unset($params['messageId']);

            $currentConsumed = 0;

            foreach($params as $key => $value)
            {
                if(in_array(strtolower($key), $this->lang->condKeywords['realStarted']))     $realStarted = $value;
                if(in_array(strtolower($key), $this->lang->condKeywords['finishedDate']))    $finishedDate = $value;
                if(in_array(strtolower($key), $this->lang->condKeywords['currentConsumed'])) $currentConsumed = $value;
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

            $reply->messages[] = sprintf($this->lang->finish->done, $taskID, $finishedDate, $task->consumed);
            $reply->messages[] = $this->renderTaskTable(array($task));

            $reply->responses[] = $this->disableMarkdownLinkInMessage($messageID);

            if(!empty($task->fromBug))
            {
                $reply->messages[] = sprintf($this->lang->finish->bugTip, $taskID);
                $link = commonModel::getSysURL() . str_replace('x.php', 'index.php', helper::createLink('bug', 'view', "bugID={$task->fromBug}", 'html'));
                $href = urlencode($link);
                $reply->messages[] = "[{$this->lang->finish->bugTipLinkTitle}](xxc:openInApp/zentao-integrated/{$href})";
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

        $taskCount = $pager->recTotal ? $pager->recTotal : count($tasks);

        if($taskCount === 0) return $lang->task->noTask;

        if($taskCount == 1)
        {
            $task = current($tasks);
            $link = str_replace('x.php', 'index.php', helper::createLink('task', 'view', "taskID=$task->id", 'html'));

            $messages = new stdclass();
            $messages->type = 'url';
            $messages->url  = common::getSysURL() . $link;
            return array(sprintf($this->lang->tasksFound, $taskCount), $messages);
        }

        $messages = array();
        if($pager->pageID == 1) $messages[] = sprintf($this->lang->tasksFound, $taskCount);

        $taskTable = $this->renderTaskTable($tasks);

        $originCommand = $this->lang->viewCommand . ' ' . implode(' ', $originArgs);
        $paging        = "$pager->pageID / $pager->pageTotal";
        if($pager->pageID != 1)
        {
            $previousPageCommand = $originCommand . " {$this->lang->condKeywords['pageID'][1]}=" . ($pager->pageID - 1) . " {$this->lang->condKeywords['recPerPage'][1]}=" . $pager->recPerPage;
            $previousPageCommand = rawurlencode($previousPageCommand);
            $paging .= " [{$this->lang->prevPage}](xxc://sendContentToServerBySendbox/{$previousPageCommand})";
        }

        if($pager->pageID != $pager->pageTotal)
        {
            $nextPageCommand = $originCommand . " {$this->lang->condKeywords['pageID'][1]}=" . ($pager->pageID + 1) . " {$this->lang->condKeywords['recPerPage'][1]}=" . $pager->recPerPage;
            $nextPageCommand = rawurlencode($nextPageCommand);
            $paging .= " [{$this->lang->nextPage}](xxc://sendContentToServerBySendbox/{$nextPageCommand})";
        }
        $paging = "<div class=\"text-right\">$paging</div>";

        $messages[] = $taskTable . $paging;

        return $messages;
    }

    /**
     * Render tasks as markdown table.
     *
     * @param  array  $tasks
     * @access public
     * @return void
     */
    public function renderTaskTable($tasks)
    {
        $lang    = $this->im->lang;
        $headMap = array('id'=>'ID', 'pri' => $lang->task->pri, 'name' => $lang->task->name, 'assignedTo' => $lang->task->assignedTo,  'status' => $lang->task->status, 'estimate' => $lang->task->estimateAB, 'consumed' => $lang->task->consumedAB, 'left' => $lang->task->leftAB, 'actions' => $lang->actions);
        $thead   = '';

        foreach($headMap as $value) $thead .= "<th>{$value}</th>";

        $thead = "<thead class='text-nowrap'><tr>{$thead}</tr></thead>";
        $tbody = '';
        foreach($tasks as $task)
        {
            $tr   = '';
            $link = str_replace('x.php', 'index.php', helper::createLink('task', 'view', "taskID=$task->id", 'html'));
            $href = urlencode(common::getSysURL() . $link);
            $isParent = $task->parent == -1;
            $isChild  = $task->parent > 0;
            $isMulti  = !empty($task->mode);
            foreach($headMap as $key => $value)
            {
                switch($key)
                {
                    case 'name':
                        $tr .= "<td>";
                        if($isParent) $tr .= " <span class='label label-sm circle'>{$lang->task->parentAB}</span>&nbsp;";
                        if($isChild)  $tr .= " <span class='label label-sm circle'>{$lang->task->childrenAB}</span>&nbsp;";
                        if($isMulti)  $tr .= " <span class='label label-sm circle'>" . zget($lang->task->modeList, $task->mode) . "</span>&nbsp;";
                        $tr .= "<a href='xxc:openInApp/zentao-integrated/{$href}'>{$task->$key}</a>";
                        $tr .= "</td>";
                        break;
                    case 'status':
                        $tr .= "<td class='text-nowrap'>{$this->taskStatusList[$task->$key]}</td>";
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
                            $tr .= "<td class='text-nowrap'>" . (empty($task->assignedTo) ? $lang->task->noAssigned : (is_object($task->assignedTo) ? $task->assignedTo->realname : $task->assignedTo)) . "</td>";
                        }
                    break;
                    case 'actions':
                        $startUrl  = 'xxc://sendContentToServerBySendbox/' . "{$this->lang->startCommand} #$task->id";
                        $finishUrl = 'xxc://sendContentToServerBySendbox/' . "{$this->lang->finishCommand} #$task->id";
                        $closeUrl  = 'xxc://sendContentToServerBySendbox/' . "{$this->lang->closeCommand} #$task->id";

                        $canStart  = in_array($task->status, array('wait', 'pause'));
                        $canFinish = in_array($task->status, array('wait', 'pause', 'doing'));
                        $canClose  = in_array($task->status, array('done', 'cancel'));

                        /* Disable all if task has children. */
                        if($isParent)
                        {
                            $canStart  = false;
                            $canFinish = false;
                            $canClose  = false;
                        }

                        $tr .= '<td><div class="flex gap-xs">';
                        $tr .= $canStart  ? "<a href='$startUrl'><i class='icon-zt-play'></i></a>"     : "<div><i class='icon-zt-play disabled'></i></div>";
                        $tr .= $canFinish ? "<a href='$finishUrl'><i class='icon-zt-checked'></i></a>" : "<div><i class='icon-zt-checked disabled'></i></div>";
                        $tr .= $canClose  ? "<a href='$closeUrl'><i class='icon-zt-off'></i></a>"      : "<div><i class='icon-zt-off disabled'></i></div>";
                        $tr .= '</div></td>';
                        break;
                    case 'estimate':
                    case 'consumed':
                    case 'left':
                        $tr .= "<td class='text-nowrap'>{$task->$key}h</td>";
                        break;
                    default:
                        $tr .= "<td class='text-nowrap'>{$task->$key}</td>";
                }
            }
            $tbody .= "<tr>{$tr}</tr>";
        }
        return "<table>{$thead}{$tbody}</table>";
    }

    /**
     * Start task command.
     *
     * @param  array         $args   bot command arguments
     * @param  int           $userID
     * @access public
     * @return object|string
     */
    public function start($args = array(), $userID = 0)
    {
        $originArgs = $args;
        foreach($args as $key => $value)
        {
            if(in_array(strtolower($value), $this->lang->condKeywords['task']))
            {
                unset($args[$key]);
            }
        }

        if(count($args) == 0) return $this->lang->errors->taskIDRequired;

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
        if(!$taskID) return $this->lang->errors->taskIDRequired;

        $task = $this->loadEntry('task', 'get', array('taskID' => $taskID));
        if(!$task) return $this->lang->errors->taskNotFound;
        if($task->status != 'wait') return sprintf($this->lang->errors->invalidStatus, $this->taskStatusList[$task->status]);

        $reply = new stdClass();
        $reply->messages  = array();
        $reply->responses = array();

        if(count($args) == 0)
        {
            $originCommand = rawurlencode($this->lang->startCommand . ' ' . implode(' ', $originArgs));

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
                        'helpText' => $this->lang->start->finishWithZeroLeft,
                    )
                ),
            );
            $json = rawurlencode(json_encode($json));

            $reply->messages[] = sprintf($this->lang->start->tip, $taskID);
            $reply->messages[] = "[{$this->lang->start->tipLinkTitle}](xxc://openFormAndSendToServerBySendbox/$json/$originCommand)";
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
                        if(in_array(strtolower($key), $this->lang->condKeywords['consumed']))    $consumed    = $value;
                        if(in_array(strtolower($key), $this->lang->condKeywords['realStarted'])) $realStarted = $value;
                        if(in_array(strtolower($key), $this->lang->condKeywords['left']))        $left        = $value;
                    }
                    if($consumed == 0 && $left == 0) return $this->im->lang->task->noticeTaskStart;

                    $task = $this->loadEntry('taskStart', 'post', array
                    (
                        'taskID' => $taskID,
                        'left' => $left,
                        'realStarted' => $realStarted,
                        'consumed' => $consumed,
                    ));
                    if(isset($task->result) and $task->result == 'fail')
                    {
                        $reply->messages[] = $task->message;
                    }
                    elseif(isset($task->error))
                    {
                        foreach($task->error as $error) $reply->messages[] = is_array($error) ? implode('，', $error): $error;
                    }
                    else
                    {
                        $reply->messages[] = sprintf($this->lang->effortRecorded, $taskID);
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
                    if(in_array(strtolower($arg), $this->lang->condKeywords['left']))
                    {
                        $left = array_shift($args);
                    }
                    elseif(in_array(strtolower($arg), $this->lang->condKeywords['consumed']))
                    {
                        $consumed = array_shift($args);
                    }
                    elseif(in_array(strtolower($arg), $this->lang->condKeywords['realStarted']))
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
                    $reply->messages[] = sprintf($this->lang->effortRecorded, $taskID);
                    $reply->messages[] = $this->renderTaskTable(array($task));
                }
            }
        }

        return $reply;
    }

    /**
     * Remove a Markdown link in the message and generate a response.
     *
     * @param  $messageID
     * @access public
     * @return object
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

    /**
     * Used to call zentao's API.
     *
     * @param  string $entry
     * @param  string $action
     * @param  array  $params
     * @param  array  $query
     * @param  string $version
     * @access public
     * @return object
     */
    public function loadEntry($entry, $action, $params = array(), $query = array(), $version = 'v1')
    {
        try
        {
            $this->im->loadModel('user');

            $user = $this->userModel->getByID($this->im->app->input['userID'], 'id');
            /* Authorize him and save to session. */
            $user->rights = $this->userModel->authorize($user->account);
            $user->groups = $this->userModel->getGroups($user->account);
            $user->view   = $this->userModel->grantUserView($user->account, $user->rights['acls'], $user->rights['projects']);
            $user->admin  = strpos($this->im->app->company->admins, ",{$user->account},") !== false;

            global $app;
            $app->action = $action;
            $app->user   = $user;

            include_once($this->im->app->appRoot . "framework/api/entry.class.php");
            include_once($this->im->app->appRoot . "api/{$version}/entries/" . strtolower($entry) . ".php");

            $entryName = $entry . 'Entry';
            $entry     = new $entryName();

            if($action == 'post' || $action == 'put')
            {
                $entry->requestBody = (object)$params;
            }
            elseif($action == 'get' && !empty($query))
            {
                $entry->setParam($query);
            }

            $content = call_user_func_array(array($entry, $action), $params);
            return json_decode($content);
        }
        catch(EndResponseException $endResponseException)
        {
            return $endResponseException->getContent();
        }
    }
}
