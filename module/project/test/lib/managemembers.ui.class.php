<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class manageMembersTester extends tester
{
    /**
     * Check the page input when managing members.
     * 增加项目团队成员
     *
     * @param  array $members
     * @access public
     */
    public function addMembers(array $members)
    {
        $form = $this->initForm('project', 'manageMembers', array('projectID' => '1'), 'appIframe-project');
        if(isset($members['account'])) $form->dom->account->picker($members['account']);
        if(isset($members['role']))    $form->dom->role1->setValue($members['role']);
        if(isset($members['day']))     $form->dom->days1->setValue($members['day']);
        if(isset($members['hours']))   $form->dom->hours1->setValue($members['hours']);

        $form->dom->btn($this->lang->save)->click();
        $form->wait(5);
        //断言检查团队成员列表页姓名、角色、可用工日、可用工时是否正确
        $teamPage = $this->loadPage('project', 'team');
        if($teamPage->dom->browseSecAccount->getText() != $members['account'])      return $this->failed('姓名错误');
        if($teamPage->dom->browseSecRole->getText()    != $members['role'])         return $this->failed('角色错误');
        if($teamPage->dom->browseSecDay->getText()     != $members['day'].'天')     return $this->failed('可用工日错误');
        if($teamPage->dom->browseSecHours->getText()   != $members['hours'].'工时') return $this->failed('可用工时错误');

        return $this->success('项目团队成员添加成功');
    }

    /**
     * Check delete members.
     * 删除项目团队成员
     *
     * @access public
     */
    public function deleteMembers()
    {
        $browseForm = $this->initForm('project', 'team', array('projectID' => '1'), 'appIframe-project');
        $browseFirAccount1 = $browseForm->dom->browseFirAccount->getText();
        $browseForm->dom->teamBtn->click();
        $form = $this->initForm('project', 'manageMembers', array('projectID' => '1'), 'appIframe-project');
        $form->dom->firstDeleteBtn->click();
        $form->wait(2);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(2);
        //添加断言，判断是否删除成功
        if($browseForm->dom->browseFirAccount->getText() == $browseFirAccount1)      return $this->failed('项目团队成员删除失败');
        return $this->success();
    }

    /**
     * Copy team members.
     * 复制部门团队成员
     *
