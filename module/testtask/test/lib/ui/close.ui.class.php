<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class closeTester extends tester
{
    /**
     * 关闭测试单。
     * Close testtask.
     *
     * @param  string $date
     * @access public
     * @return void
     */
    public function close($date)
    {
        $form  = $this->initForm('testtask', 'view', array('taskID' => '1'), 'appIframe-qa');
        $form->dom->btn($this->lang->testtask->close)->click();
        $form->wait(1);
        if(isset($date)) $form->dom->realFinishedDate->setValue($date);
        $form->dom->submitBtn->click();
        $form->wait(1);
        if(isset($date) && strtotime($date) < strtotime($form->dom->begin->getText()))
        {
            if($form->dom->realFinishedDateTip->getText() != sprintf($this->lang->testtask->finishedDateLess, $form->dom->begin->getText())) return $this->failed('实际完成日期小于开始日期时提示错误');
            return $this->success('实际完成日期小于开始日期时提示正确');
        }
        if(isset($date) && strtotime($date) > strtotime(date('Y-m-d')))
        {
            if($form->dom->realFinishedDateTip->getText() != $this->lang->testtask->finishedDateMore) return $this->failed('实际完成日期大于当前日期时提示错误');
            return $this->success('实际完成日期大于当前日期时提示正确');
        }
        if($form->dom->status->getText() != $this->lang->testtask->statusList->done) return $this->failed('关闭后状态不正确');
        return $this->success('关闭成功');
    }
}
