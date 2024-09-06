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
        $form->dom->confirmBtn->click();
        $form->wait(2);

        //添加断言，判断是否移除团队成员成功
        if($form->dom->browseFirAccount->getText() == $browseFirAccount1) return $this->failed('项目团队成员移除失败');
        return $this->success('项目团队成员移除成功');
    }
}
