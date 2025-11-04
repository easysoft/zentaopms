<?php
require_once dirname(__FILE__, 6) . '/test/lib/ui.php';
class testcaseTester extends tester
{
    /**
     * 校验user模块视图层testcase显示
     * Verify user module testcase display
     *
     * @param  array  $users
     * @param  array  $testcases
     * @param  string $byOrTo  校验方式，ToUser校验assignedTo，ByUser校验openedBy
     * @access public
     * @return object
     */
    public function verifyUserTestCases($users = array(), $testcases = array(), $byOrTo = 'ToUser')
    {
        if(!in_array($byOrTo, array('ToUser', 'ByUser'))) return $this->failed("$byOrTo={$byOrTo} 不是有效参数");

        $this->login();
        $form = $this->initForm('user', 'testcase', array('userID' => $testcases[0]->userID), 'appIframe-system');
        $form->wait(2);

        $cases = array();
        foreach($testcases as $testcase)
        {
            if($byOrTo == 'ToUser') $cases[$testcase->assignedTo][$testcase->id] = $testcase;
            else $cases[$testcase->openedBy][$testcase->id]  = $testcase;
        }

        foreach($users as $user)
        {
            $form->dom->userPicker->picker($user->realname);
            $form->wait(1);
            if($form->dom->userPicker->getText() != $user->realname) return $this->failed('testcase页面切换用户显示失败');

            if($byOrTo == 'ToUser')
            {
                $title = $form->dom->assignedTo->getText();
                $form->dom->assignedTo->click();
            }
            else
            {
                $title = $form->dom->createdBy->getText();
                $form->dom->createdBy->click();
            }
            $form->wait(1);

            $columns = array('id', 'title', 'pri', 'type', 'status', 'openedBy', 'lastRunDate', 'lastRunResult', 'lastRunner');

            $display = array();
            foreach($columns as $column) $display[$column] = $form->dom->getElementListByXpathKey($column, true);

            $himOrHer = zget($this->lang->user->thirdPerson, $user->gender);

            $expectTitle = array(
                'ToUser' => sprintf($this->lang->user->case2Him, $himOrHer),
                'ByUser' => sprintf($this->lang->user->caseByHim, $himOrHer)
            );

            if($title != $expectTitle[$byOrTo]) return $this->failed('testcase页面显示错误');

            if(count($display['id']) != count($cases[$user->account])) return $this->failed('testcase页面测试用例数量显示错误');

            foreach($display['id'] as $i => $id)
            {
                if(!in_array($id, array_column($cases[$user->account], 'id')))  return $this->failed("testcase{$id}没有指派给{$user->realname}");
                if($display['title'][$i] != $cases[$user->account][$id]->title) return $this->failed("testcase{$id}用例名称显示不匹配");
                if($display['pri'][$i] != $cases[$user->account][$id]->pri)     return $this->failed("testcase{$id}优先级显示不匹配");

                $realname = array_column($users, 'realname', 'account')[$cases[$user->account][$id]->openedBy] ?? $cases[$user->account][$id]->openedBy;
                if($display['openedBy'][$i] != $realname) return $this->failed("testcase id {$id} 创建者显示错误");

                if($byOrTo == "ByUser" and $display['lastRunner'][$i] != $cases[$user->account][$id]->lastRunner) return $this->failed("testcase{$id}执行人显示不匹配");

                $expectType   = $this->lang->testcase->typeList->{$cases[$user->account][$id]->type};
                $expectStatus = $this->lang->testcase->statusList->{$cases[$user->account][$id]->status};
                $expectResult = $this->lang->testcase->resultList->{$cases[$user->account][$id]->lastRunResult};

                if($display['type'][$i] != $expectType)            return $this->failed("testcase{$id}类型显示不匹配");
                if($display['status'][$i] != $expectStatus)        return $this->failed("testcase{$id}状态显示不匹配");
                if($display['lastRunResult'][$i] != $expectResult) return $this->failed("testcase{$id}结果显示不匹配");
            }
        }
        $message = ($byOrTo == 'ByUser') ? '由...创建' : '指派给...';
        return $this->success("用户用例页面'{$message}'测试成功");
    }
}
