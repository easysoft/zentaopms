<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class storyTester extends tester
{
    /**
     * 检查Tab标签下的数据。
     * Check the data of the Tab tag.
     *
     * @param  string $tab       allTab|unclosedTab|draftTab|reviewingTab
     * @param  string $expectNum
     * @access public
     * @return object
     */
    public function checkTab($tab, $expectNum)
    {
        $form = $this->initForm('execution', 'story', array('execution' => '2'), 'appIframe-execution');
        $form->dom->$tab->click();
        $form->wait(1);
        if($form->dom->num->getText() == $expectNum) return $this->success($tab . '下显示条数正确');
        return $this->failed($tab . '下显示条数不正确');
    }

    /**
     * 移除单个需求。
     * Unlink story.
     *
     * @access public
     * @return object
     */
    public function unlinkStory()
    {
        $form = $this->initForm('execution', 'story', array('execution' => '2'), 'appIframe-execution');
        $name = $form->dom->firstName->getText();
        $form->dom->firstUnlinkBtn->click();
        $form->dom->alertModal();
        $form->wait(1);

        $form->dom->search(array("{$this->lang->story->name},=,{$name}"));
        $form->wait(1);
        if($form->dom->firstName === false) return $this->success('需求移除成功');
        return $this->failed('需求移除失败');
    }

    /**
     * 批量移除需求。
     * Batch unlink story.
     *
     * @access public
     * @return object
     */
    public function batchUnlinkStory()
    {
        $form = $this->initForm('execution', 'story', array('execution' => '2'), 'appIframe-execution');
        $name = $form->dom->firstName->getText();
        $form->dom->firstCheckbox->click();
        $form->dom->btn($this->lang->execution->unlinkStory)->click();
        $form->wait(1);
        $form->dom->alertModal();
        $form->wait(1);

        $form->dom->search(array("{$this->lang->story->name},=,{$name}"));
        $form->wait(1);
        if($form->dom->firstName === false) return $this->success('需求批量移除成功');
        return $this->failed('需求批量移除失败');
    }

    /**
     * 批量编辑阶段。
     * Batch edit phase.
     *
     * @param  string $status draft|reviewing|active|changing|closed
     * @param  string $phase  wait|planned|projected|designing|designed|developing|developed|testing|tested|verified|rejected|delivering|delivered|released|closed
     * @access public
     * @return object
     */
    public function batchEditPhase($status, $phase)
    {
        $form        = $this->initForm('execution', 'story', array('execution' => '2'), 'appIframe-execution');
        $storyStatus = $this->lang->story->statusList->$status;
        $storyPhase  = $this->lang->story->stageList->$phase;
        $phaseXpath  = $form->dom->xpath['phases'] . "//*[text() = '{$storyPhase}']";

        $form->dom->search(array("{$this->lang->story->status},=,{$storyStatus}"));
        $form->wait(1);
        $form->dom->firstCheckbox->click();
        $form->dom->phaseBtn->click();
        $form->wait(1);
        $form = $this->loadPage();
        $form->wait(3);
        $phaseXpath->click();
        if($status == 'draft' || $status == 'closed') $form->dom->alertModal();
        $form->wait(1);

        $afterPhase = $form->dom->firstPhase->getText();
        if($afterPhase == $storyPhase) return $this->success('批量编辑阶段成功');
        return $this->failed('批量编辑阶段失败');
    }
}
