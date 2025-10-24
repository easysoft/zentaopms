<?php
require_once dirname(__FILE__, 6) . '/test/lib/ui.php';

define('COLUMNS', array('id', 'name', 'pri', 'date', 'begin', 'end', 'status', 'type'));
define("MENUS", array('all', 'before', 'future', 'thisWeek', 'thisMonth', 'thisYear', 'assignedToOther', 'cycle'));
define('PAGESIZE', array(5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 100, 200, 500, 1000, 2000));

class todoTester extends tester
{
    /**
     * 校验user模块todo视图的内容及分页功能是否正确
     * Verify user module todo view content and pagination
     *
     * @param  array  $users
     * @param  array  $todos
     * @param  int    $pageSize
     * @access public
     * @return object
     */
    public function verifyUserTodoContentAndPagination($users = array(), $todos = array(), $pageSize = 5)
    {
        if(!in_array($pageSize, PAGESIZE, true)) return $this->failed('pageSize不支持该值');
        if(empty($users)) return $this->failed('用户列表不能为空');
        $this->login();
        $form = $this->initForm('user', 'todo', array('userID' => $users[0]->id), 'appIframe-system');
        $form->wait(2);

        $weekStart  = date('Y-m-d', strtotime('monday this week'));
        $weekEnd    = date('Y-m-d', strtotime('sunday this week'));
        $monthStart = date('Y-m-01');
        $monthEnd   = date('Y-m-t');
        $yearStart  = date('Y-01-01');
        $yearEnd    = date('Y-12-31');

        foreach($users as $user)
        {
            $form->dom->userPicker->picker($user->realname);
            $form->wait(1);
            if($form->dom->userPicker->getText() != $user->realname) return $this->failed('testcase页面切换用户显示失败');
            $assignedToSelf  = array_values(array_filter($todos, fn($t) => ($t->assignedTo ?? null) === $user->account && (!($t->cycle ?? null))));
            $assignedToOther = array_values(array_filter($todos, fn($t) => ($t->account ?? null) === $user->account && ($t->assignedTo ?? null) !== $user->account));

            foreach(MENUS as $menu)
            {
                $form->dom->{$menu}->click();
                $form->wait(1);

                switch($menu)
                {
                    case 'all':
                        $userTodos = $assignedToSelf;
                        break;
                    case 'before':
                        $userTodos = array_values(array_filter($assignedToSelf, fn($t) => ($t->status ?? '') !== 'done' && ($t->date ?? '') !== FUTURE_DATE));
                        break;
                    case 'future':
                        $userTodos = array_values(array_filter($assignedToSelf, fn($t) => ($t->date ?? '') >= FUTURE_DATE));
                        break;
                    case 'thisWeek':
                        $userTodos = array_values(array_filter($assignedToSelf, fn($t) => ($t->date ?? '') >= $weekStart && ($t->date ?? '') <= $weekEnd));
                        break;
                    case 'thisMonth':
                        $userTodos = array_values(array_filter($assignedToSelf, fn($t) => ($t->date ?? '') >= $monthStart && ($t->date ?? '') <= $monthEnd));
                        break;
                    case 'thisYear':
                        $userTodos = array_values(array_filter($assignedToSelf, fn($t) => ($t->date ?? '') >= $yearStart && ($t->date ?? '') <= $yearEnd));
                        break;
                    case 'assignedToOther':
                        $userTodos = array_values(array_filter($assignedToOther, fn($t) => ($t->account ?? '') !== ($t->assignedTo ?? '')));
                        break;
                    case 'cycle':
                        $userTodos = array_values(array_filter($todos, fn($t) => ($t->assignedTo ?? null) === $user->account && ($t->cycle ?? null)));
                        break;
                }

                $ret = $this->verifyTodoColumns($form, $userTodos, $user, $menu, $pageSize);
                if($ret) return $ret;
                $ret = $this->verifyTodoPagination($form, $userTodos, $pageSize);
                if($ret) return $ret;
            }
        }

        return $this->success('用户待办页面内容测试成功');
    }

    /**
     * 验证待办列数据
     * Verify todo column data
     *
     * @param  object $form
     * @param  array  $todos
     * @param  object $user
     * @param  string $menu
     * @param  int    $pageSize
     * @access private
     * @return object
     */
    private function verifyTodoColumns($form, $todos, $user, $menu, $pageSize = 5)
    {
        // Make sure all todos are displayed on one page first
        if(count($todos) > $pageSize)
        {
            $form->dom->pagerSizeMenu->click();
            $form->wait(1);
            $form->dom->dropdownPicker(2000);
        }
        $display       = array();
        $menuInLang    = $this->lang->todo->periods->{$menu};
        $viewerAccount = $this->config->uitest->defaultAccount;

        foreach(COLUMNS as $column)
        {
            $display[$column] = $form->dom->getElementListByXpathKey($column, true);

            $header = $this->lang->todo->{$column};
            if(count($display[$column]) != count($todos)) return $this->failed("用户'{$user->realname}'的'{$menuInLang}'菜单'{$header}'列数据数量与实际不匹配");
            if($column == 'id') continue;

            foreach($display[$column] as $i => $item)
            {
                $id      = $display['id'][$i];
                $data    = array_column($todos, $column, 'id')[$id];
                $private = array_column($todos, 'private', 'id')[$id];
                $account = array_column($todos, 'account', 'id')[$id];

                if(($column == 'name') and $private and ($account != $viewerAccount)) $data = $this->lang->todo->thisIsPrivate;
                if($column == 'date' && $data == FUTURE_DATE) $data = $this->lang->todo->periods->future;
                if($column == 'begin' || $column == 'end') $data = sprintf('%02d:%02d', intval($data) / 100, intval($data) % 100);
                if($column == 'status') $data = $this->lang->todo->statusList->{$data} ?? $data;
                if($column == 'type')   $data = $this->lang->todo->typeList->{$data} ?? $data;

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
    private function verifyTodoPagination($form, $todos, $pageSize = 5)
    {
        $totalTodos = count($todos);
        $pages      = (int) ceil($totalTodos / $pageSize);

        if(count($todos) > $pageSize)
        {
            $form->dom->pagerSizeMenu->click();
            $form->wait(1);
            $form->dom->dropdownPicker($pageSize);
            $form->dom->firstPage->click();
            $form->wait(1);
        }

        $waitLabel  = $this->lang->todo->statusList->wait  ?? 'wait';
        $doingLabel = $this->lang->todo->statusList->doing ?? 'doing';

        $todosById = array();
        foreach($todos as $t)
        {
            $id = is_object($t) ? ($t->id ?? null) : ($t['id'] ?? null);
            if($id !== null) $todosById[$id] = $t;
        }

        for($i = 1; $i <= $pages; $i++)
        {
            $ids         = $form->dom->getElementListByXpathKey('id', true);
            $statusTexts = $form->dom->getElementListByXpathKey('status', true);
            $totalActual = count($statusTexts);
            $waitActual  = 0;
            $doingActual = 0;
            foreach ($statusTexts as $st)
            {
                $st = trim($st);
                if ($st === $waitLabel)  $waitActual++;
                if ($st === $doingLabel) $doingActual++;
            }
            $waitExpected  = 0;
            $doingExpected = 0;
            foreach($ids as $id)
            {
                $id = (int)trim($id);
                if(!isset($todosById[$id])) continue;
                $todo   = $todosById[$id];
                $status = is_object($todo) ? ($todo->status ?? '') : ($todo['status'] ?? '');
                if($status === 'wait')  $waitExpected++;
                if($status === 'doing') $doingExpected++;
            }

            $totalExpected = ($pages === 1) ? $totalTodos : ($i < $pages ? $pageSize : ($totalTodos - $pageSize * ($pages - 1)));

            if($totalActual !== $totalExpected) return $this->failed("分页第{$i}页数量不匹配：期望{$totalExpected}，实际{$totalActual}");
            if($waitActual !== $waitExpected)   return $this->failed("分页第{$i}页状态计数不匹配：wait期望{$waitExpected}/实际{$waitActual}");
            if($doingActual !== $doingExpected) return $this->failed("分页第{$i}页状态计数不匹配：doing期望{$doingExpected}/实际{$doingActual}");

            if($i < $pages && $form->dom->nextPage)
            {
                $form->dom->nextPage->click();
                $form->wait(1);
            }
        }

        return null;
    }
}
