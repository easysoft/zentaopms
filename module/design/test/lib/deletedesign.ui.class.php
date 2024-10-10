<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class deleteDesignTester extends tester
{
    public function deleteDesign(array $design)
    {
        $form = $this->initForm('design', 'view', array('designID' => 2), 'appIframe-project');
        $form->dom->deleteBtn->click();
        $form->dom->confirmBtn->click();
        $form->wait(1);

        /* 跳转到设计详情页，检查是否有已删除标识。 */
        $viewPage = $this->initForm('design', 'view', array('id' => 2), 'appIframe-project');
        $form->wait(1);
        $deleteFlag = $viewPage->dom->deleteFlag->getText();
        if($deleteFlag != $this->lang->design->deleted) return $this->failed('删除设计失败');
        return $this->success('删除设计成功');
    }
}
