<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class teamTesterForLite extends tester
{
    /**
     * Check remove members for lite.
     * 运营界面移除项目团队成员
     *
     * @access public
     */
    public function removeMembers()
    {
        $this->switchVision('lite');
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
