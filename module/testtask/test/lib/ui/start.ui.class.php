<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class startTester extends tester
{
    /**
     * 开始测试单。
     * Start testtask.
     *
     * @access public
     * @return void
     */
    public function start()
    {
        $form  = $this->initForm('testtask', 'view', array('taskID' => '1'), 'appIframe-qa');
        $form->wait(1);
        $form->dom->btn($this->lang->testtask->start)->click();
        $form->wait(1);
        $form->dom->submitBtn->click();
        $form->wait(1);

        if($form->dom->status->getText() == $this->lang->testtask->statusList->doing) return $this->success('开始测试单成功');
        return $this->failed('开始测试单失败');
    }
}
