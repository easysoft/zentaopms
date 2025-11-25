<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class assignDesignTester extends tester
{
    /**
     * Assign a design.
     * 指派设计。
     *
     * @param  array $design
     * @access public
     * @return object
     */
    public function assignDesign(array $design)
    {
        $form = $this->initForm('design', 'browse', array('projectID' => 60), 'appIframe-project');
        $form->dom->btn($this->lang->design->noAssigned)->click();
        if(isset($design['assignedTo'])) $form->dom->assignedTo->picker($design['assignedTo']);

        $form->wait(2);
        $form->dom->assignedToBtn->click();
        $form->wait(1);

        /* 跳转到设计列表，检查指派给字段信息。 */
        $browsePage = $this->loadPage('design', 'browse');
        if($browsePage->dom->assigned->getText() != $design['assignedTo']) return $this->failed('指派给错误');
        return $this->success('指派成功');
    }
}
