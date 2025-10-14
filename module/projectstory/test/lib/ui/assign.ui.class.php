<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class assignStoryTester extends tester
{
    /**
     * 需求指派。
     * Assign story.
     *
     * @param  string $user
     * @access public
     * @return object
     */
    public function assignTo($user)
    {
        $form = $this->initForm('projectstory', 'story', array('projectID' => '1'), 'appIframe-project');
        $form->dom->firstAssignTo->click();
        $form->wait(2);
        $form->dom->assignedTo->picker($user);
        $form->wait(2);
        $form->dom->assignBtn->click();
        $form->wait(2);

        /* 将指派人字段拖动到可见区域 */
        $form->dom->firstAssignTo->scrollToElement();
        $assignedToAfter = $form->dom->firstAssignTo->getText();
        if($assignedToAfter == $user) return $this->success('指派成功');
        return $this->failed('指派失败');
    }

    /**
     * 需求批量指派。
     * Batch assign story.
     *
     * @access public
     * @return object
     */
    public function batchAssignTo()
    {
        $form = $this->initForm('projectstory', 'story', array('projectID' => '1'), 'appIframe-project');
        $form->dom->allTab->click();
        $form->dom->selectAllBtn->click();
        $form->wait(1);
        $form->dom->batchAssignBtn->click();
        $form->wait(1);
        $form->dom->assignToAdmin->click();
        $form->wait(3);

        /* 将指派人字段拖动到可见区域 */
        $form->dom->firstAssignTo->scrollToElement();
        $assignedToAfter = $form->dom->firstAssignTo->getText();
        if($assignedToAfter == 'admin') return $this->success('批量指派成功');
        return $this->failed('批量指派失败');
    }
}
