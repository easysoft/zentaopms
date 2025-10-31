<?php
require_once dirname(__FILE__, 6) . '/test/lib/ui.php';

define('TESTTASK_MENUS', array('assignedTo'));
define('TESTTASK_COLUMNS', array('id', 'name', 'pri', 'buildName', 'executionName', 'status', 'begin', 'end'));
define('PAGESIZE', array(5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 100, 200, 500, 1000, 2000));

class testtaskTester extends tester
{
    /**
     * 校验user模块testtask视图的内容及分页功能
     * Verify the content and pagination of user testtask view.
     *
     * @param  array  $users    用户列表
     * @param  array  $tasks    测试单数据
     * @param  int    $pageSize 每页大小
     * @return object           成功或失败对象
     */
    public function verifyUserTesttask($users = array(), $tasks = array(), $pageSize = 5)
    {
        if(empty($users)) return $this->failed('用户列表不能为空');
        if(empty($tasks)) return $this->failed('测试单列表不能为空');
        if(!in_array($pageSize, PAGESIZE, true)) return $this->failed('pageSize无效');

        $this->login();
        $form = $this->initForm('user', 'testtask', array('userID' => $users[0]->id), 'appIframe-system');
        $form->wait(2);

        foreach($users as $user)
        {
            if($user != $users[0])
            {
                $form->dom->userPicker->picker($user->realname);
                $form->wait(1);
                if($form->dom->userPicker->getText() != $user->realname) return $this->failed('testtask页面切换用户显示失败');
            }

            foreach(TESTTASK_MENUS as $menu)
            {
                $form->dom->{$menu}->click();
                $form->wait(1);
                $ret = $this->verifyMenuContentAndPagination($form, $user, $tasks, $menu, $pageSize);
                if($ret) return $ret;
            }
        }

        return $this->success("开源版m=user&f=testtask测试成功");
    }

    /**
     * 检查具体子菜单的内容以及分页功能
     * Verify the content and pagination of a specific sub-menu in m=user&f=testtask.
     *
     * @param  object $form     页面对象
     * @param  object $user     用户对象
     * @param  array  $tasks    测试单数据
     * @param  string $menu     assignedTo
     * @param  int    $pageSize 每页大小
     * @return object           成功返回null，失败返回对象
     */
    private function verifyMenuContentAndPagination($form, $user, $tasks, $menu, $pageSize = 5)
    {
        $userTasks = $this->filterDataByMenu($tasks, $user, $menu);

        $ret = $this->verifyContent($form, $userTasks, $user, $menu);
        if($ret) return $ret;

        $ret = $this->verifyPagination($form, $userTasks, $user, $menu, $pageSize);
        if($ret) return $ret;

        return null;
    }

    /**
     * 根据菜单过滤数据
     * Filter data based on menu
     *
     * @param  array  $tasks 测试单数据
     * @param  object $user  用户对象
     * @param  string $menu  菜单
     * @return array         过滤后的测试单列表
     */
    private function filterDataByMenu($tasks, $user, $menu)
    {
        $account  = is_object($user) ? ($user->account ?? '') : ($user['account'] ?? '');
        $filtered = array();

        foreach($tasks as $task)
        {
            $tid       = is_object($task) ? ($task->id ?? null) : ($task['id'] ?? null);
            $owner     = is_object($task) ? ($task->owner ?? '') : ($task['owner'] ?? '');
            $members   = is_object($task) ? ($task->members ?? '') : ($task['members'] ?? '');
            $execution = is_object($task) ? ($task->execution ?? null) : ($task['execution'] ?? null);

            // 首先检查sprints权限：execution必须在用户的sprints权限范围内
            $userSprints     = isset($user->sprints) ? explode(',', $user->sprints) : array();
            $hasSprintAccess = in_array((string)$execution, $userSprints);

            if(!$hasSprintAccess) continue;

            // 然后检查owner和members权限
            $isOwner  = $owner === $account;
            $isMember = !empty($members) && in_array($account, explode(',', $members));

            if($menu === 'assignedTo' && ($isOwner || $isMember)) $filtered[$tid] = $task;
        }

        return $filtered;
    }

    /**
     * 验证列值
     * Verify the values in columns
     *
     * @param  object $form  页面对象
     * @param  array  $tasks 测试单数据
     * @param  object $user  用户对象
     * @param  string $menu  菜单名称
     * @return object        成功返回null，失败返回对象
     */
    private function verifyContent($form, $tasks, $user, $menu)
    {
        if(!$tasks)
        {
            if(!$form->dom->pageSizeMenu) return null;
            return $this->failed("用户'{$user->realname}'的'{$menu}'菜单显示数量与实际不符");
        }
        $currentPageSize = (int) filter_var($form->dom->pagerSizeMenu->getText(), FILTER_SANITIZE_NUMBER_INT);
        if(count($tasks) > $currentPageSize)
        {
            $form->dom->pagerSizeMenu->click();
            $form->wait(1);
            $form->dom->dropdownPicker(max(PAGESIZE));
            $form->wait(1);
        }

        $display = array();
        foreach(TESTTASK_COLUMNS as $c) $display[$c] = $form->dom->getElementListByXpathKey($c, true);

        if(count($display['id']) != count($tasks)) return $this->failed("用户'{$user->realname}'的'{$menu}'菜单显示数量不匹配：期望" . count($tasks) . "个，实际显示" . count($display['id']) . "个");

        foreach($display['id'] as $i => $id)
        {
            $t = $tasks[$id];

            foreach(TESTTASK_COLUMNS as $c)
            {
                $actual = $display[$c][$i];
                $header = isset($this->lang->testtask->{$c}) ? $this->lang->testtask->{$c} : $c;
                switch ($c)
                {
                    case 'status':
                        $expected = $this->lang->testtask->statusList->{$t->{$c}};
                        break;
                    case 'begin':
                    case 'end':
                        $expected = ($t->{$c} == '0000-00-00') ? '' : $t->{$c};
                        break;
                    default:
                        $expected = $t->{$c};
                        break;
                }
                if($actual != $expected) return $this->failed("USER={$user->realname}, MENU={$menu}, ID={$id}: {$header}不匹配，期望'{$expected}'，实际'{$actual}'");
            }
        }

        return null;
    }

    /**
     * 验证分页
     * verify pagination
     *
     * @param  object $form     页面对象
     * @param  array  $items    测试数据
     * @param  int    $pageSize 每页数量
     * @return object           成功返回null，失败返回对象
     */
    private function verifyPagination($form, $items, $user, $menu, $pageSize = 5)
    {
        $total = count($items);
        $pages = (int) ceil($total / $pageSize);

        // 如果数据量大于每页大小，则进行分页验证
        if($total > $pageSize)
        {
            $form->dom->pagerSizeMenu->click();
            $form->wait(1);
            $form->dom->dropdownPicker($pageSize);
            $form->wait(1);
            $form->dom->firstPage->click();
            $form->wait(1);
        }

        for($i = 1; $i <= $pages; $i++)
        {
            $totalActual = count($form->dom->getElementListByXpathKey('id'));
            $totalExpected = ($pages === 1) ? $total : ($i < $pages ? $pageSize : ($total - $pageSize * ($pages - 1)));

            if($totalActual !== $totalExpected) return $this->failed("USER={$user->realname}, MENU={$menu} 分页第{$i}页数量不匹配：期望{$totalExpected}，实际{$totalActual}");

            if($i < $pages)
            {
                $form->dom->nextPage->click();
                $form->wait(1);
            }
        }

        return null;
    }
}
