<?php
require_once dirname(__FILE__, 6) . '/test/lib/ui.php';

define('BUG_MENUS', array('assignedTo','openedBy','resolvedBy','closedBy'));
define('BUG_COLUMNS', array('id','title','severity','pri','type','openedByName','resolvedByName','resolution'));
define('PAGESIZE', array(5,10,15,20,25,30,35,40,45,50,100,200,500,1000,2000));

class bugTester extends tester
{
    /**
     * 验证m=user&f=bug的各个子菜单内容和分页功能
     * Verify the content and pagination of each sub-menu in m=user&f=bug.
     *
     * @param  array  $users    用户列表
     * @param  array  $bugs     Bug数据
     * @param  int    $pageSize 每页大小
     * @return object           成功或失败对象
     */
    public function verifyUserBugMenus($users = array(), $bugs = array(), $pageSize = 5)
    {
        if(empty($users)) return $this->failed('用户列表不能为空');
        if(empty($bugs))  return $this->failed('Bug列表不能为空');
        if(!in_array($pageSize, PAGESIZE, true)) return $this->failed('pageSize无效');

        $this->login();
        $form = $this->initForm('user', 'bug', array('userID' => $users[0]->id), 'appIframe-system');
        $form->wait(2);

        foreach($users as $user)
        {
            if($user != $users[0])
            {
                $form->dom->userPicker->picker($user->realname);
                $form->wait(1);
                if($form->dom->userPicker->getText() != $user->realname) return $this->failed('bug页面切换用户显示失败');
            }

            foreach(BUG_MENUS as $menu)
            {
                $form->dom->{$menu}->click();
                $form->wait(1);
                $ret = $this->verifyUserBugContentAndPagination($form, $user, $bugs, $menu, $pageSize);
                if($ret) return $ret;
            }
        }

        return $this->success('开源版m=user&f=bug测试成功');
    }

    /**
     * 检查具体子菜单的内容以及分页功能
     * Verify the content and pagination of a specific sub-menu in m=user&f=bug.
     *
     * @param  object $form     表单对象
     * @param  object $user     用户对象
     * @param  array  $bugs     Bug数据
     * @param  string $menu     子菜单名称
     * @param  int    $pageSize 每页大小
     * @return object           成功返回null,失败返回对象
     */
    private function verifyUserBugContentAndPagination($form, $user, $bugs = array(), $menu = 'assignedTo', $pageSize = 5)
    {
        $userBugs = $this->filterBugsByMenu($bugs, $user, $menu);
        if(!count($userBugs)) return null;
        $currentPageSize = (int) filter_var($form->dom->pagerSizeMenu->getText(), FILTER_SANITIZE_NUMBER_INT);
        if(count($userBugs) > $currentPageSize)
        {
            $form->dom->pagerSizeMenu->click();
            $form->wait(1);
            $form->dom->dropdownPicker(max(PAGESIZE));
            $form->wait(1);
        }

        $ret = $this->verifyBugColumns($form, $userBugs);
        if($ret) return $ret;

        $ret = $this->verifyBugPagination($form, $userBugs, $pageSize);
        if($ret) return $ret;

        return null;
    }

    /**
     * 根据菜单过滤数据
     * Filter data based on menu
     * @param  array  $bugs   Bug数据
     * @param  object $user   用户对象
     * @param  string $menu   子菜单名称
     * @return array          过滤后的Bug数据
     */
    private function filterBugsByMenu($bugs, $user, $menu)
    {
        $account  = is_object($user) ? ($user->account ?? '') : ($user['account'] ?? '');
        $filtered = array();

        foreach($bugs as $bug)
        {
            $bid    = is_object($bug) ? ($bug->id ?? null) : ($bug['id'] ?? null);
            $status = is_object($bug) ? ($bug->status ?? '') : ($bug['status'] ?? '');
            $val    = is_object($bug) ? ($bug->{$menu} ?? '') : ($bug[$menu] ?? '');
            if($val === $account)
            {
                if($menu != 'closedBy') $filtered[$bid] = $bug;
                else if($status === 'closed') $filtered[$bid] = $bug;
            }
        }
        return $filtered;
    }

    /**
     * 验证列值
     * Verify the values in bug columns
     *
     * @param  object $form 表单对象
     * @param  array  $bugs Bug数据
     * @return object       成功返回null,失败返回对象
     */
    private function verifyBugColumns($form, $bugs)
    {
        $columns = BUG_COLUMNS;

        $display = array();
        foreach($columns as $col)
		{
			if($col != 'severity') $display[$col] = $form->dom->getElementListByXpathKey($col, true);
			else $display[$col] = $form->dom->getElementListByXpathKey($col);
		}

        if(count($display['id']) != count($bugs)) return $this->failed('bug页面数据数量与期望不一致');

        foreach($display['id'] as $i => $id)
        {
            if(!isset($bugs[$id])) return $this->failed("bug{$id}不在当前用户视图范围内");
            $b  = $bugs[$id];

            foreach($columns as $c)
            {
                // Severity 列的值在 data-severity 属性中而不是在text里
                if($c == 'severity') $actual = $display[$c][$i]->getAttribute('data-severity');
                else $actual = $display[$c][$i];

                switch ($c)
                {
                    case 'severity':
                        $expected = $this->lang->bug->severityList->{$b->severity} ?? $b->severity;
                        break;
                    case 'pri':
                        $expected = (string)$b->pri; // pri 列显示为数字徽标
                        break;
                    case 'type':
                        $expected = $this->lang->bug->typeList->{$b->type} ?? $b->type;
                        break;
                    case 'openedByName':
                    case 'resolvedByName':
                        // 页面使用 userMap 渲染真实姓名，测试数据中已提供 realname
                        $key = $c === 'openedByName' ? 'openedBy' : 'resolvedBy';
                        $expected = $b->{$key . 'Name'} ?? $b->{$key};
                        break;
                    case 'resolution':
                        $expected = $this->lang->bug->resolutionList->{$b->resolution} ?? $b->resolution;
                        break;
                    default:
                        $expected = $b->{$c};
                        break;
                }

                if($actual != $expected) return $this->failed("ID={$id} {$c}不匹配，期望'{$expected}'，实际'{$actual}'");
            }
        }
        return null;
    }

    /**
     * 验证分页
     * Verify the pagination of bug items
     *
     * @param  object $form     表单对象
     * @param  array  $items    项目数据
     * @param  int    $pageSize 每页项目数量
     * @return object           成功返回null,失败返回对象
     */
    private function verifyBugPagination($form, $items, $pageSize = 5)
    {
        $total = count($items);
        $pages = (int) ceil($total / $pageSize);

        if($total > $pageSize)
        {
            $form->dom->pagerSizeMenu->click();
            $form->wait(1);
            $form->dom->dropdownPicker($pageSize);
            $form->dom->firstPage->click();
            $form->wait(1);
        }

        for($i = 1; $i <= $pages; $i++)
        {
            $actual   = count($form->dom->getElementListByXpathKey('id'));
            $expected = ($pages === 1) ? $total : ($i < $pages ? $pageSize : ($total - $pageSize * ($pages - 1)));
            if($actual !== $expected) return $this->failed("分页第{$i}页数量不匹配：期望{$expected}，实际{$actual}");

            if($i < $pages && $form->dom->nextPage)
            {
                $form->dom->nextPage->click();
                $form->wait(1);
            }
        }
        return null;
    }
}
