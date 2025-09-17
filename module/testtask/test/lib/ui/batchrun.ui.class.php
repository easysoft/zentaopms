<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class batchRunTester extends tester
{
    /**
     * 批量执行测试用例.
     * Batch run testcase.
     *
     * @param  string $hasStep
     * @param  string $expectRusult
     * @access public
     * @return void
     */
    public function batchRun($hasStep, $expectRusult = 'pass')
    {
        $form    = $this->initForm('testtask', 'cases', array('taskID' => '1'), 'appIframe-qa');
        $firstId = $form->dom->firstId->getText();

        /* hasStep为0时，所选用例无步骤；hasStep为1时，所选用例有步骤；hasStep为其他时，所选用例包含有步骤和无步骤。*/
        if($hasStep != '0') $form->dom->lastCheckbox->click();
        if($hasStep != '1') $form->dom->firstCheckbox->click();
        $form->wait(1);
        $form->dom->batchRunBtn->click();
        $form->wait(1);
        if($hasStep == '0')
        {
            if(is_object($form->dom->modalText) && $form->dom->modalText->getText() == $this->lang->testtask->caseEmpty) return $this->success('批量执行的用例步骤为空提示正确');
            return $this->failed('批量执行的用例步骤为空提示错误');
        }

        $batchRunForm = $this->loadPage('testtask', 'batchRun');
        $batchRunForm->wait(1);
        if($hasStep !=0 && $hasStep !=1)
        {
            if(!is_object($batchRunForm->dom->modalText) || $batchRunForm->dom->modalText->getText() != sprintf($this->lang->testtask->emptyCases, $firstId)) return $this->failed('批量执行的用例中有步骤为空用例提示错误');
            $batchRunForm->dom->alertModal();
        }

        $batchRunForm->dom->$expectRusult->click();
        $batchRunForm->dom->submitBtn->click();
        $batchRunForm->wait(1);
        $form = $this->loadPage('testtask', 'cases');
        $form->wait(1);
        if($form->dom->lastResult->getText() == $this->lang->testcase->resultList->$expectRusult) return $this->success('批量执行测试用例成功');
        return $this->failed('批量执行测试用例失败');
    }
}
