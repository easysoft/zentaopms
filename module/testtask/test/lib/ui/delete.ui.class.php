<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class deleteTester extends tester
{
    /**
     * 删除测试单。
     * Delete testtask.
     *
     * @access public
     * @return void
     */
    public function deleteTest()
    {
        $form = $this->initForm('testtask', 'browse', array('productID' => '1'), 'appIframe-qa');
        $num  = $form->dom->num->getText();
        $id   = $form->dom->firstID->getText();
        $form->dom->firstDeleteBtn->click();
        $form->wait(1);
        $form->dom->alertModal();
        $form->wait(2);
        if($form->dom->num->getText() != $num - 1) return $this->failed('删除测试单失败');

        $form = $this->initForm('testtask', 'view', array('taskID' => $id), 'appIframe-qa');
        if(is_object($form->dom->deletedLabel) && $form->dom->deletedLabel->getText() == $this->lang->testtask->deleted) return $this->success('删除测试单成功');
        return $this->failed('删除测试单后概况页没有显示已删除标签');
    }
}
