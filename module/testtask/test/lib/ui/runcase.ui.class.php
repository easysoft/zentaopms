<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class runCaseTester extends tester
{
    /**
     * 单个执行测试用例.
     * Run testcase.
     *
     * @param  bool   $hasStep
     * @param  string $expectRusult
     * @access public
     * @return void
     */
    public function runCase($hasStep, $expectRusult)
    {
        $form = $this->initForm('testtask', 'cases', array('taskID' => '1'), 'appIframe-qa');
        if($hasStep)  $form->dom->lastRunBtn->click();
        if(!$hasStep) $form->dom->firstRunBtn->click();
        $form->wait(1);
        $form->dom->result->picker($this->lang->testcase->resultList->$expectRusult);
        $form->dom->submitBtn->click();
        $form->wait(2);
        if(is_object($form->dom->close)) $form->dom->close->click();
        $form->wait(1);

        if($hasStep)  $result = $form->dom->lastResult->getText();
        if(!$hasStep) $result = $form->dom->firstResult->getText();
        if($expectRusult == 'n/a' && $result == $this->lang->testcase->resultList->pass) return $this->success('用例执行成功');
        if($result == $this->lang->testcase->resultList->$expectRusult) return $this->success('用例执行成功');
        return $this->failed('用例执行失败');
    }
}
