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
