<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class activateTester extends tester
{
    /**
     * 激活测试单
     *
     * @param  string $testtaskID
     * @access public
     * @return void
     */
    public function activateTest($testtaskID)
    {
        $form   = $this->initForm('testtask', 'view', array('taskID' => $testtaskID), 'appIframe-qa');
        $status = $form->dom->status->getText();
        if($status == $this->lang->testtask->statusList->wait || $status == $this->lang->testtask->statusList->doing)
        {
            if(strpos($form->dom->buttons->getText(), $this->lang->testtask->activate) !== false) return $this->failed('未开始和进行中的测试单错误的显示了激活按钮');
            return $this->success('未开始和进行中的测试单不显示激活按钮');
        }
        $form->dom->btn($this->lang->testtask->activate)->click();
        $form->wait(1);
        $form->dom->submitBtn->click();
        $form->wait(1);
        if($form->dom->status->getText() != $this->lang->testtask->statusList->doing) return $this->failed('激活测试单失败');
        return $this->success('激活测试单成功');
    }
}
