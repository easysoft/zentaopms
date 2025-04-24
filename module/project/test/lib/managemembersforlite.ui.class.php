<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
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
        $browseForm        = $this->initForm('project', 'team', array('projectID' => '1'), 'appIframe-project');
        $browseFirAccount1 = $browseForm->dom->browseFirAccount->getText();
