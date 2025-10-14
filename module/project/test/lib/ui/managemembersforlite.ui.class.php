<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class manageMembersForLiteTester extends tester
{
    /**
     * 增加项目团队成员。
     * Check the page input when managing members.
     *
     * @param  array $members
     * @access public
     */
    public function addMembers($members)
    {
        $this->switchVision('lite');
        $form = $this->initForm('project', 'manageMembers', array('projectID' => '1'), 'appIframe-project');
        $form->wait(2);
        if(isset($members['account'])) $form->dom->account->picker($members['account']);
        if(isset($members['role']))    $form->dom->role1->setValue($members['role']);
        $form->wait(2);

        $form->dom->saveBtn->click();
        $form->wait(2);

        //断言检查团队成员列表页姓名、角色是否正确
        $teamPage = $this->loadPage('project', 'team');
        if($teamPage->dom->browseSecAccountLite->getText() != $members['account']) return $this->failed('姓名错误');
        if($teamPage->dom->browseSecRoleLite->getText()    != $members['role'])    return $this->failed('角色错误');

        return $this->success('项目团队成员添加成功');
    }

    /**
     * 删除项目团队成员。
     * Check delete members.
     *
     * @access public
     */
    public function deleteMembers()
    {
        $form              = $this->initForm('project', 'team', array('projectID' => '1'), 'appIframe-project');
        $browseFirAccount1 = $form->dom->browseFirAccount->getText();
        $form->wait(2);
        $form->dom->teamBtn->click();
        $form = $this->initForm('project', 'manageMembers', array('projectID' => '1'), 'appIframe-project');
        $form->dom->firstDeleteBtn->click();
        $form->wait(2);
        $form->dom->saveBtn->click();
        $form->wait(2);

        //添加断言，判断是否删除成功
        $teamPage = $this->loadPage('project', 'team');
        if($teamPage->dom->browseFirAccount->getText() == $browseFirAccount1) return $this->failed('项目团队成员删除失败');
        return $this->success('项目团队成员删除成功');
    }

    /**
     * 复制部门团队成员。
     * Copy team members.
     *
     * @access public
     */
    public function copyDeptMembers()
    {
        $form = $this->initForm('project', 'team', array('projectID' => '1'), 'appIframe-project');
        $form->dom->teamBtn->click();
        $form = $this->loadPage('project', 'manageMembers');
        $form->dom->dept->picker('部门1');
        $form->wait(2);
        $form->dom->saveBtn->click();
        $form->wait(2);

        //添加断言，根据保存后的成员数量，判断是否复制团队成员成功
        $form = $this->loadPage('project', 'team');
        if($form->dom->amount->getText() != '3') return $this->failed('复制部门团队成员失败');
        return $this->success('复制部门团队成员成功');
    }
}
