<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class StoryEstimateTester extends tester
{
    /**
     * 表单内容。
     * Input form.
     *
     * @param  array  $estimate
     * @access public
     * @return void
     */
    public function inputForm($estimate)
    {
        $form = $this->initForm('execution', 'story', array('execution' => '2'), 'appIframe-execution');
        $form->wait(3);

        $form->dom->xpath['firstEstimateBtn'] = "//a[@title = '{$this->lang->execution->storyEstimate}']";
        $form->dom->firstEstimateBtn->click();
        $form->wait(1);
        if(is_object($form->dom->reestimate)) $form->dom->reestimate->click();
        $form->wait(1);
        if(isset($estimate[0])) $form->dom->estimateA->setValue($estimate[0]);
        if(isset($estimate[1])) $form->dom->estimateB->setValue($estimate[1]);
        if(isset($estimate[2])) $form->dom->estimateC->setValue($estimate[2]);
        $form->wait(1);
        $form->dom->saveBtn->click();
        $form->wait(1);
    }

    /**
     * 需求估算。
     * Story estimate.
     *
     * @param  array  $estimate
     * @param  string $time
     * @access public
     * @return void
     */
    public function storyEstimate($estimate, $time)
    {
        $this->inputForm($estimate);
        $form  = $this->loadPage();
        $round = sprintf($this->lang->story->storyRound, $time);
        $form->wait(1);
        $form->dom->round->picker($round);
        $form->wait(1);
        $a   = $form->dom->estimateA->getValue();
        $b   = $form->dom->estimateB->getValue();
        $c   = $form->dom->estimateC->getValue();
        $ave = $form->dom->average->attr('value');

        if($a != $estimate[0] || $b != $estimate[1] || $c != $estimate[2] || $ave != $estimate['averge']) return $this->failed('估算失败');
        return $this->success('估算成功');
    }

    /**
     * 需求估算值为非数字或负数。
     * Story estimate value is not a number or negative.
     *
     * @param  array  $estimate
     * @param  string $type     notNumber|negative
     * @access public
     * @return void
     */
    public function checkErrorInfo($estimate, $type)
    {
        $this->inputForm($estimate);
        $form = $this->loadPage();
        $form->wait(1);
        if($type == 'notNumber')
        {
            if($form->dom->estimateTip->getText() == $this->lang->story->estimateMustBeNumber) return $this->success('估算值为非数字提示成功');
            return $this->failed('估算值为非数字提示失败');
        }
        else
        {
            if($form->dom->estimateTip->getText() == $this->lang->story->estimateMustBePlus) return $this->success('估算值为负数提示成功');
            return $this->failed('估算值为负数提示失败');
        }
    }

    /**
     * 执行没有团队成员时需求估算。
     * Story estimate when no team member.
     *
     * @access public
     * @return void
     */
    public function noTeamInfo()
    {
        $form = $this->initForm('execution', 'story', array('execution' => '3'), 'appIframe-execution');
        $form->wait(3);

        $form->dom->xpath['firstEstimateBtn'] = "//a[@title = '{$this->lang->execution->storyEstimate}']";
        $form->dom->firstEstimateBtn->click();
        $form->wait(2);
        if($form->dom->noTeamInfo->getText() == $this->lang->execution->noTeam) return $this->success('没有团队成员提示成功');
        return $this->failed('没有团队成员提示失败');
    }
}
