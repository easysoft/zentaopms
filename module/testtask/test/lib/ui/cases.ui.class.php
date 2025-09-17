<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class casesTester extends tester
{
    /**
     * 检查测试单下不同标签下的数据。
     * Check the data of testtasks under different tags.
     *
     * @param  string $tags
     * @param  string $num
     * @access public
     * @return void
     */
    public function checkTagData($tags, $num)
    {
        $form = $this->initForm('testtask', 'cases', array('taskID' => '1'), 'appIframe-qa');
        if($tags == 'all')
        {
            $form->dom->btn($this->lang->testtask->allCases)->click();
            $form->wait(1);
            if($form->dom->num->getText() == $num) return $this->success('标签下数据统计正确');
            return $this->failed('标签下数据统计错误');
        }
        if($tags == 'assignedToMe')
        {
            $form->dom->btn($this->lang->testtask->assignedToMe)->click();
            $form->wait(1);
            if($form->dom->num->getText() == $num) return $this->success('标签下数据统计正确');
            return $this->failed('标签下数据统计错误');
        }
        if($tags == 'browseBySuite')
        {
            $form->dom->btn($this->lang->testtask->browseBySuite)->click();
            $form->wait(1);
            $form->dom->firstSuite->click();
            $form->wait(1);
            if($form->dom->num->getText() == $num) return $this->success('标签下数据统计正确');
            return $this->failed('标签下数据统计错误');
        }
        return $this->failed('标签不存在');
    }

    /**
     * 批量移除用例。
     * Batch unlink cases.
     *
     * @access public
     * @return void
     */
    public function batchUnlinkCases()
    {
        $form   = $this->initForm('testtask', 'cases', array('taskID' => '1'), 'appIframe-qa');
        $allNum = intval($form->dom->allCasesNum->getText());
        $form->dom->firstCheckbox->click();
        $form->wait(1);
        $form->dom->dropDownBtn->click();
        $form->wait(1);
        $form->dom->batchUnlinkBtn->click();
        $form->wait(1);
        if(intval($form->dom->allCasesNum->getText()) == $allNum - 1) return $this->success('批量移除用例成功');
        return $this->failed('批量移除用例失败');
    }

    /**
     * 批量指派用例。
     * Batch assign cases.
     *
     * @access public
     * @return void
     */
    public function batchAssignedTo()
    {
        $form = $this->initForm('testtask', 'cases', array('taskID' => '1'), 'appIframe-qa');
        $form->dom->firstCheckbox->click();
        $form->wait(1);
        $form->dom->batchAssignedToBtn->click();
        $form->wait(1);
        $user = $form->dom->secondUser->getText();
        $form->dom->secondUser->click();
        $form->wait(1);
        if($form->dom->firstAssignedTo->getText() == $user) return $this->success('批量指派用例成功');
        return $this->failed('批量指派用例失败');
    }
}
