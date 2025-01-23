<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class browseTester extends tester
{
    /**
     * Delete the stakeholder from the stakeholder list.
     * 干系人列表删除干系人。
     *
     * @access public
     * @return object
     */
    public function deleteStakeholder()
    {
        $form = $this->initForm('stakeholder', 'browse', array('project' => 1), 'appIframe-project');
        $form->dom->deleteBtn->click();
        $form->dom->confirmBtn->click();
        $form->wait(1);
    }
}
