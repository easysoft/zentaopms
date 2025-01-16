<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
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
            if(!is_object($form->dom->btn($this->lang->testtask->activate))) return $this->success('未开始和进行中的测试单不显示激活按钮');
            return $this->failed('未开始和进行中的测试单错误的显示了激活按钮');
        }
    }
}
