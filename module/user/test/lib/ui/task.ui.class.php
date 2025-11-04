<?php
require_once dirname(__FILE__, 6) . '/test/lib/ui.php';

define('COLUMNS', array('id', 'name', 'pri', 'status', 'executionName', 'deadline', 'estimate', 'consumed', 'left'));
define('MENUS', array('assignedTo', 'openedBy', 'finishedBy', 'involvedIn', 'closedBy', 'canceledBy'));
define('PAGESIZE', array(5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 100, 200, 500, 1000, 2000));

class taskTester extends tester
{
    /**
     * 校验user模块task视图的内容是否正确（对齐 todo.ui.class.php 的结构）
     * 1) 外层循环用户，2) 内层循环菜单，3) 验证列与分页
     *
     * @param  array $users      用户列表
     * @param  array $tasks      任务数据（含单人/多人）
     * @param  array $executions 执行列表（用于显示执行名称）
     * @param  array $taskteams  任务团队列表（zt_taskteam，用于多人任务参与判断）
     * @param  int   $pageSize   每页数量（默认20，对齐页面默认）
     * @access public
     * @return object
     */
    public function verifyUserTaskContentAndPagination($users = array(), $tasks = array(), $executions = array(), $taskteams = array(), $pageSize = 5)
    {
        if(!in_array($pageSize, PAGESIZE, true)) return $this->failed('pageSize不支持该值');
        if(empty($users)) return $this->failed('用户列表不能为空');
        if(empty($tasks)) return $this->failed('任务数据不能为空');
        if(empty($executions)) return $this->failed('执行列表不能为空');
        if(empty($taskteams)) return $this->failed('任务团队数据不能为空');

        $this->login();
        $form = $this->initForm('user', 'task', array('userID' => $users[0]->id), 'appIframe-system');
        $form->wait(2);

        // 1) 组织任务：按 id 建映射，过滤 deleted/vision，并补充 executionName
        $allTasks = array();
        foreach($tasks as $t)
        {
            $deleted = isset($t->deleted) ? $t->deleted : 0;
            $vision  = isset($t->vision) ? $t->vision : null;
            if($deleted != 0) continue;
            if($this->config->vision && $vision && $vision !== $this->config->vision) continue;
            $id = isset($t->id) ? (int)$t->id : null;
            if($id === null) continue;
            $allTasks[$id] = $t;
        }

        // 2) 组织执行：id=>执行对象，并为任务补充 executionName
        $execMap = array();
        foreach($executions as $e)
        {
            $eid = is_object($e) ? (isset($e->id) ? (int)$e->id : null) : (isset($e['id']) ? (int)$e['id'] : null);
            if($eid === null) continue;
            $execMap[$eid] = is_object($e) ? $e : (object)$e;
        }
        foreach($allTasks as $tid => $t)
        {
            $execID = isset($t->execution) ? (int)$t->execution : null;
            $t->executionName = ($execID !== null && isset($execMap[$execID])) ? (isset($execMap[$execID]->name) ? $execMap[$execID]->name : '') : '';
            $allTasks[$tid] = $t;
        }

        // 3) 组织团队：taskID => [account => status]
        $teamByTask = array();
        foreach($taskteams as $row)
        {
            $tid     = is_object($row) ? (isset($row->task) ? (int)$row->task : null) : (isset($row['task']) ? (int)$row['task'] : null);
            $account = is_object($row) ? (isset($row->account) ? $row->account : null) : (isset($row['account']) ? $row['account'] : null);
            $status  = is_object($row) ? (isset($row->status) ? $row->status : null) : (isset($row['status']) ? $row['status'] : null);
            if($tid === null || $account === null) continue;
            if(!isset($teamByTask[$tid])) $teamByTask[$tid] = array();
            $teamByTask[$tid][$account] = $status;
        }

        // 逐个检查传入用户：切换视图并按该用户筛选
        foreach($users as $user)
        {
            if($user != $users[0])
            {
                $form->dom->userPicker->picker($user->realname);
                $form->wait(1);
                if($form->dom->userPicker->getText() != $user->realname) return $this->failed('task页面切换用户显示失败');
            }

            $account = $user->account;
            foreach(MENUS as $menu)
            {
                $form->dom->{$menu}->click();
                $form->wait(1);

                $userTasks = array();
                foreach($allTasks as $t)
                {
                    $closed  = strtolower(isset($t->status) ? $t->status : '') === 'closed';
                    $isMulti = (isset($t->mode) ? $t->mode : 'single') === 'multi';
                    $inTeam  = isset($teamByTask[$t->id]) && array_key_exists($account, $teamByTask[$t->id]);
                    $teamDone = $inTeam ? strtolower($teamByTask[$t->id][$account]) === 'done' : false;

                    switch($menu)
                    {
                        case 'openedBy':
                        case 'finishedBy':
                        case 'closedBy':
                        case 'canceledBy':
                            if((isset($t->{$menu}) ? $t->{$menu} : null) === $account) $userTasks[] = $t;
                            break;

                        case 'assignedTo':
                            $assigned = (isset($t->assignedTo) ? $t->assignedTo : null) === $account;
                            if($assigned || ($isMulti && $inTeam && !$closed && !$teamDone)) $userTasks[] = $t;
                            break;

                        case 'involvedIn':
                            $sessionAccount  = $this->config->uitest->defaultAccount;
                            $inTeamBySession = isset($teamByTask[$t->id]) && array_key_exists($sessionAccount, $teamByTask[$t->id]);
                            // 查看他人时，团队项不计入；仅当会话账户与视图用户一致且任务未关闭/团队未完成时计入
                            $includeTeam     = ($sessionAccount === $account) && $inTeamBySession && !$closed && !$teamDone;
                            $byAssigned      = ((isset($t->assignedTo) ? $t->assignedTo : null) === $sessionAccount);
                            $byFinished      = ((isset($t->finishedBy) ? $t->finishedBy : null) === $sessionAccount);
                            if($byAssigned || $byFinished || $includeTeam) $userTasks[] = $t;
                            break;

                        default:
                            break;
                    }
                }

                $ret = $this->verifyTaskColumns($form, $userTasks, $user, $menu, $pageSize);
                if($ret) return $ret;
                $ret = $this->verifyTaskPagination($form, $userTasks, $pageSize);
                if($ret) return $ret;
            }
        }
        return $this->success('开源版user模块视图层task界面测试成功');
    }

    /**
     * 验证列数据
     * Verify column data
     *
     * @param  object $form
     * @param  array  $tasks
     * @param  object $user
     * @param  string $menu
     * @param  int    $pageSize
     * @access private
     * @return object
     */
    private function verifyTaskColumns($form, $tasks, $user, $menu, $pageSize = 5)
    {
        if(count($tasks) > $pageSize)
        {
            $form->dom->pagerSizeMenu->click();
            $form->wait(1);
            $form->dom->dropdownPicker(max(PAGESIZE));
        }
        $menuInLang = $this->lang->todo->periods->{$menu} ?? $menu;

        // 建立任务ID到任务对象的映射，便于在列校验中读取 mode 等属性
        $tasksById = array();
        foreach($tasks as $t)
        {
            $tid = is_object($t) ? (isset($t->id) ? (string)$t->id : null) : (isset($t['id']) ? (string)$t['id'] : null);
            if($tid !== null) $tasksById[$tid] = $t;
        }

        $display = array();
        foreach(COLUMNS as $column)
        {
            $display[$column] = $form->dom->getElementListByXpathKey($column, true);
            $header = isset($this->lang->task->$column) ? $this->lang->task->$column : $column;
            if(count($display[$column]) != count($tasks)) return $this->failed("用户'{$user->realname}'的'{$menuInLang}'菜单'{$header}'列数据数量与实际不匹配");
            if($column == 'id') continue;

            foreach($display[$column] as $i => $item)
            {
                $id   = $display['id'][$i];
                $data = array_column($tasks, $column, 'id')[$id];

                // 当列为 name 且任务为多人模式时，名称前应带有多人标记（如“多人”）
                if($column === 'name')
                {
                    $item = str_replace("\xC2\xA0", ' ', $item);
                    $item = preg_replace('/\s+/u', ' ', trim($item));
                    $data = str_replace("\xC2\xA0", ' ', $data);
                    $data = preg_replace('/\s+/u', ' ', trim($data));

                    $taskObj = isset($tasksById[(string)$id]) ? $tasksById[(string)$id] : null;
                    $isMulti = $taskObj && isset($taskObj->mode) ? ($taskObj->mode === 'multi') : false;

                    if($isMulti)
                    {
                        $badge = $this->lang->task->multipleAB;
                        $expectedNoSpace   = $badge . $data;
                        $expectedWithSpace = $badge . ' ' . $data;

                        if($item === $expectedNoSpace || $item === $expectedWithSpace)  $data = $item;
                        else $data = $expectedWithSpace;
                    }
                }

                if($column == 'status') $data = $this->lang->task->statusList->{$data};

                if(in_array($column, array('estimate', 'consumed', 'left'))) $data = $data . $this->lang->task->suffixHour;
                if($data != $item) return $this->failed("用户'{$user->realname}'的'{$menuInLang}'菜单'{$header}'列'id={$id}'时数据不匹配:期望'{$data}',实际’{$item}'");
            }
        }
        return null;
    }

    /**
     * 验证分页功能
     * Verify pagination functionality
     * @param  object $form
     * @param  array  $todos
     * @param  int    $pageSize
     * @access public
     * @return object
     */
    private function verifyTaskPagination($form, $tasks, $pageSize = 5)
    {
        $totalTasks = count($tasks);
        $pages      = (int) ceil($totalTasks / $pageSize);

        if(count($tasks) > $pageSize)
        {
            $form->dom->pagerSizeMenu->click();
            $form->wait(1);
            $form->dom->dropdownPicker($pageSize);
            $form->dom->firstPage->click();
            $form->wait(1);
        }

        for($i = 1; $i <= $pages; $i++)
        {
            $totalActual   = count($form->dom->getElementListByXpathKey('id' ));
            $totalExpected = ($pages === 1) ? $totalTasks : ($i < $pages ? $pageSize : ($totalTasks - $pageSize * ($pages - 1)));

            if($totalActual !== $totalExpected) return $this->failed("分页第{$i}页数量不匹配：期望{$totalExpected}，实际{$totalActual}");

            if($i < $pages && $form->dom->nextPage)
            {
                $form->dom->nextPage->click();
                $form->wait(1);
            }
        }
        return null;
    }
}
