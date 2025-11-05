<?php
require_once dirname(__FILE__, 6) . '/test/lib/ui.php';

define('EXECUTION_COLUMNS', array('id', 'name', 'status', 'role', 'begin', 'end', 'join', 'hours'));
define('PAGESIZE', array(5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 100, 200, 500, 1000, 2000));

class executionTester extends tester
{
    /**
     * 校验user模块execution视图的内容及分页功能
     * Verify the content and pagination of user execution view.
     *
     * @param  array  $users      用户列表
     * @param  array  $executions execution数据
     * @param  int    $pageSize   每页大小
     * @return object             成功或失败对象
     */
    public function verifyUserExecutionContentAndPagination($users = array(), $executions = array(), $pageSize = 5)
    {
        if(empty($users))      return $this->failed('用户列表不能为空');
        if(empty($executions)) return $this->failed('execution数据不能为空');
        if(!in_array($pageSize, PAGESIZE, true)) return $this->failed('pageSize无效');

        $this->login();
        $form = $this->initForm('user', 'execution', array('userID' => $users[0]->id), 'appIframe-system');
        $form->wait(2);

        foreach($users as $user)
        {
            if($user != $users[0])
            {
                $form->dom->userPicker->picker($user->realname);
                $form->wait(2);
                if($form->dom->userPicker->getText() != $user->realname) return $this->failed('execution页面切换用户显示失败');
            }

            $userExecutions = isset($executions[$user->account]) ? $executions[$user->account] : array();

            $ret = $this->verifyContent($form, $user, $userExecutions);
            if($ret) return $ret;

            $items = $form->dom->getElementListByXpathKey('id');
            $ret = $this->verifyPagination($form, $items, $pageSize);
            if($ret) return $ret;
        }

        return $this->success("开源版m=user&f=execution测试成功");
    }

    /**
     * 验证内容
     * verify content
     *
     * @param  object $form       页面对象
     * @param  object $user       用户对象
     * @param  array  $executions 过滤后的execution数据
     * @return object             成功返回null，失败返回对象
     */
    private function verifyContent($form, $user, $executions)
    {
        $currentPageSize = (int) filter_var($form->dom->pagerSizeMenu->getText(), FILTER_SANITIZE_NUMBER_INT);

        if(count($executions) > $currentPageSize)
        {
            $form->dom->pagerSizeMenu->click();
            $form->wait(1);
            $form->dom->dropdownPicker(max(PAGESIZE));
            $form->wait(1);
        }

        $display = array();
        foreach(EXECUTION_COLUMNS as $c) $display[$c] = $form->dom->getElementListByXpathKey($c, true);

        if(count($display['id']) != count($executions)) return $this->failed("用户'{$user->realname}'的执行数量不符: 期望" . count($executions) . "条，实际" . count($display['id']) . "条");

        foreach($display['id'] as $i => $idText)
        {
            $id = (int)trim($idText);
            if(!isset($executions[$id])) return $this->failed("用户'{$user->realname}'执行页面显示了不应该显示的ID: {$id}");
            $execution = $executions[$id];
            foreach(EXECUTION_COLUMNS as $column)
            {
                $displayValue = $display[$column][$i] ?? '';
                if($column == 'status') $expected = $this->lang->program->statusList->{$execution->status} ?? $execution->status;
                else $expected = $execution->{$column} ?? '';
                if($displayValue != $expected) return $this->failed("用户'{$user->realname}'执行页面ID={$id} {$column}不匹配，期望'{$expected}'，实际'{$displayValue}'");
            }
        }

        return null;
    }

    /**
     * 验证分页
     * verify pagination
     *
     * @param  object $form      页面对象
     * @param  array  $items     需求数据
     * @param  int    $pageSize  每页数量
     * @return object            成功返回null，失败返回对象
     */
    private function verifyPagination($form, $items, $pageSize = 5)
    {
        $totalTasks = count($items);
        $pages      = (int) ceil($totalTasks / $pageSize);

        if(count($items) > $pageSize)
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
