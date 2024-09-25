<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class StoryEstimateTester extends tester
{
    /**
     * 需求估算。
     * Story estimate.
     *
     * @param  array  $estimate
     * @access public
     * @return void
     */
    public function storyEstimate($estimate)
    {
        $form = $this->initForm('execution', 'story', array('execution' => '2'), 'appIframe-execution');
        var_dump($form->dom->firstEstimateBtn);die;
        $form->dom->firstEstimateBtn->click();
        $form->wait(1);
        $form->dom->estimateA->setValue($estimate[0]);
        $form->dom->estimateB->setValue($estimate[1]);
        $form->dom->estimateC->setValue($estimate[2]);
        $averageA = $form->dom->average->getText();
        $form->dom->submitBtn->click();

        $a    = $form->dom->estimateA->getValue();
        $b    = $form->dom->estimateB->getValue();
        $c    = $form->dom->estimateC->getValue();
        $aveA = $form->dom->average->getText();
        if($a != $estimate[0] || $b != $estimate[1] || $c != $estimate[2] || $aveA != $averageA || $aveA != $estimate['avergeA']) return $this->failed('第1轮估算失败');

        $form->dom->reestimate->click();

        $form->dom->newEstimateA->setValue($estimate[3]);
        $form->dom->newEstimateB->setValue($estimate[4]);
        $form->dom->newEstimateC->setValue($estimate[5]);
        $averageB = $form->dom->average->getText();
        $form->dom->submitBtn->click();

        $roundB = sprintf($this->lang->story->storyRound, '2');
        $form->dom->round->picker($roundB);
        $d    = $form->dom->newEstimateA->getValue();
        $e    = $form->dom->newEstimateB->getValue();
        $f    = $form->dom->newEstimateC->getValue();
        $aveB = $form->dom->newAverage->getText();

        if($d != $estimate[3] || $e != $estimate[4] || $f != $estimate[5] || $aveB != $averageB || $aveB != $estimate['avergeB']) return $this->failed('第2轮估算失败');
        return $this->successed('估算成功');
    }
}
