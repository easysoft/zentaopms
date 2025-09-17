<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
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

        /* 跳转到干系人详情页，检查是否有已删除标识。 */
        $viewPage = $this->initForm('stakeholder', 'view', array('id' => 1), 'appIframe-project');
        $form->wait(1);
        $deleteFlag = $viewPage->dom->deleteFlag->getText();
        $form->wait(1);
        if($deleteFlag != $this->lang->stakeholder->deleted) return $this->failed('删除干系人失败');
        return $this->success('删除干系人成功');
    }
}
