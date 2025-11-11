<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class storyTester extends tester
{
    /**
     * 打开页面。
     * Open page.
     *
     * @access public
     * @return object
     */
    public function open()
    {
        $form = $this->initForm('execution', 'story', array('execution' => '2'), 'appIframe-execution');
        $form->wait(3);
    }

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
        $form = $this->loadPage();
        $form->wait(1);
        $form->dom->$tab->click();
        $form->wait(3);
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
        $form = $this->loadPage();
        $form->wait(1);
        $name = $form->dom->firstName->getText();
        $form->dom->firstUnlinkBtn->click();
        $form->wait(1);
        $form->dom->alertModal();
        $form->wait(3);

        $form->dom->search(array("{$this->lang->story->name},=,{$name}"));
        $form->wait(3);
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
        $form = $this->loadPage();
        $form->dom->allTab->click();
        $form->wait(3);

        $name = $form->dom->firstName->getText();
        $form->dom->firstCheckbox->click();
        $form->wait(2);
        $form->dom->btn($this->lang->execution->unlinkStory)->click();
        $form->wait(2);
        $form->dom->alertModal();
        $form->wait(3);

        $form->dom->search(array("{$this->lang->story->name},=,{$name}"));
        $form->wait(2);
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
        $form = $this->initForm('execution', 'story', array('execution' => '2'), 'appIframe-execution');
        $form->wait(3);
        $storyStatus = $this->lang->story->statusList->$status;
        $storyPhase  = $this->lang->story->stageList->$phase;

        $form->dom->xpath['stage'] = $form->dom->xpath['phases'] . "//*[text() = '{$storyPhase}']";

        $form->dom->search(array("{$this->lang->story->status},=,{$storyStatus}"));
        $form->wait(2);
        /* 因为阶段字段被遮挡，所以需要滚动到可见区域 */
        $form->dom->firstPhase->scrollToElement();

        $beforePhase = $form->dom->firstPhase->getText();
        $form->dom->firstCheckbox->click();
        $form->dom->phaseBtn->click();
        $form->wait(1);
        $form->dom->stage->click();
        if($status == 'draft' || $status == 'closed')
        {
            $form->wait(1);
            $form->dom->alertModal();
        }
        $form->wait(2);

        $afterPhase = $form->dom->firstPhase->getText();
        if($status == 'draft' || $status == 'closed')
        {
            if($afterPhase == $beforePhase) return $this->success('批量编辑' . $status . '阶段成功');
            return $this->failed('批量编辑' . $status . '阶段失败');
        }
        else
        {
            if($afterPhase == $storyPhase) return $this->success('批量编辑' . $status . '阶段成功');
            return $this->failed('批量编辑' . $status . '阶段失败');
        }
    }

    /**
     * 需求指派。
     * Assign story.
     *
     * @param  string $user
     * @access public
     * @return object
     */
    public function assignTo($user)
    {
        $form = $this->loadPage();
        $form->dom->allTab->click();
        $form->wait(3);
        $form->dom->firstAssignTo->click();
        $form->wait(1);
        $form->dom->assignedTo->picker($user);
        $form->wait(1);
        $form->dom->submitBtn->click();
        $form->wait(1);

        /* 因为指派给字段被遮挡，所以需要滚动到可见区域 */
        $form->dom->firstAssignTo->scrollToElement();
        $form->wait(1);
        $assignedTo = $form->dom->firstAssignTo->getText();
        if($assignedTo == $user) return $this->success('指派成功');
        return $this->failed('指派失败');
    }

    /**
     * 需求批量指派。
     * Batch assign story.
     *
     * @access public
     * @return object
     */
    public function batchAssignTo()
    {
        $form = $this->loadPage();
        $form->wait(1);
        $form->dom->firstCheckbox->click();
        $form->wait(1);
        $form->dom->batchAssignBtn->click();
        $form->wait(1);
        $form->dom->assignToAdmin->click();
        $form->wait(3);

        /* 因为指派给字段被遮挡，所以需要滚动到可见区域 */
        $form->dom->firstAssignTo->scrollToElement();
        $assignedTo = $form->dom->firstAssignTo->getText();
        if($assignedTo == 'admin') return $this->success('批量指派成功');
        return $this->failed('批量指派失败');
    }
}
