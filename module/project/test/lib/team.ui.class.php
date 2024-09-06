<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class teamTester extends tester
{
    /**
     * Check remove members.
     * 移除项目团队成员
     *
     * @access public
     */
    public function removeMembers()
    {
        $form              = $this->initForm('project', 'team', array('projectID' => '1'), 'appIframe-project');
        $browseFirAccount1 = $form->dom->browseFirAccount->getText();
        $form->dom->unlinkBtn->click();
