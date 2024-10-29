<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class grouptaskTester extends tester
{
    /**
     * 检查全部展开时分组下数据。
     * Check the data of the group.、
     *
     * @param  string $groupData story|status|pri|assignedTo|finishedBy|closedBy|type
     * @param  array  $nums
     * @access public
     * @return void
     */
    public function checkGroupData($groupData, $nums)
    {
        $form = $this->initForm('execution', 'grouptask', array('execution' => '2', 'groupBy' => $groupData ), 'appIframe-execution');
        if($form->dom->group->getText() != $this->lang->task->noStory)                                     return $this->failed('研发需求分类错误');
        if(filter_var($form->dom->tasks->getText(), FILTER_SANITIZE_NUMBER_INT) != $nums['tasks'])         return $this->failed('总任务数目错误');
        if(filter_var($form->dom->waiting->getText(), FILTER_SANITIZE_NUMBER_INT) != $nums['waiting'])     return $this->failed('未开始任务数目错误');
        if(filter_var($form->dom->doing->getText(), FILTER_SANITIZE_NUMBER_INT) != $nums['doing'])         return $this->failed('进行中任务数目错误');
        if(filter_var($form->dom->estimates->getText(), FILTER_SANITIZE_NUMBER_INT) != $nums['estimates']) return $this->failed('总预计工时错误');
        if(filter_var($form->dom->cost->getText(), FILTER_SANITIZE_NUMBER_INT) != $nums['cost'])           return $this->failed('已消耗工时错误');
        if(filter_var($form->dom->left->getText(), FILTER_SANITIZE_NUMBER_INT) != $nums['left'])           return $this->failed('剩余工时错误');
        $this->success('数据正确');
    }

    /**
     * 检查已关联研发需求的任务数据统计。
     * Check the data of the group.
     *
     * @param  array  $nums
     * @access public
     * @return void
     */
    public function checkTaskLinkedStory($nums)
    {
        $form = $this->initForm('execution', 'grouptask', array('execution' => '2', 'groupBy' => 'story' ), 'appIframe-execution');
        $form->dom->taskLinkedStoryBtn->click();
        $form->wait(1);
        if(filter_var($form->dom->group->getText(), FILTER_SANITIZE_NUMBER_INT) != $this->lang->task->noStory) return $this->failed('研发需求分类错误');
        if(filter_var($form->dom->tasks->getText(), FILTER_SANITIZE_NUMBER_INT) != $nums['tasks'])             return $this->failed('总任务数目错误');
        if(filter_var($form->dom->waiting->getText(), FILTER_SANITIZE_NUMBER_INT) != $nums['waiting'])         return $this->failed('未开始任务数目错误');
        if(filter_var($form->dom->doing->getText(), FILTER_SANITIZE_NUMBER_INT) != $nums['doing'])             return $this->failed('进行中任务数目错误');
        if(filter_var($form->dom->estimates->getText(), FILTER_SANITIZE_NUMBER_INT) != $nums['estimates'])     return $this->failed('总预计工时错误');
        if(filter_var($form->dom->cost->getText(), FILTER_SANITIZE_NUMBER_INT) != $nums['cost'])               return $this->failed('已消耗工时错误');
        if(filter_var($form->dom->left->getText(), FILTER_SANITIZE_NUMBER_INT) != $nums['left'])               return $this->failed('剩余工时错误');
        $this->success('数据正确');
    }

    /**
     * 检查折叠后的数据统计。
     * Check the data of the group.
     *
     * @param  array  $nums
     * @access public
     * @return void
     */
    public function checkCollapse($nums)
    {
        $form = $this->initForm('execution', 'grouptask', array('execution' => '2', 'groupBy' => 'story' ), 'appIframe-execution');
        $form->dom->collapseBtn->click();
        $form->wait(1);
        if($form->dom->rtasks->getText() != $nums['tasks'])             return $this->failed('总任务数目错误');
        var_dump($form->dom->rdoing->getText());
        var_dump($nums['doing']);
        if($form->dom->rdoing->getText() != $nums['doing'])             return $this->failed('进行中任务数目错误');
        if($form->dom->rwaiting->getText() != $nums['waiting'])         return $this->failed('未开始任务数目错误');
        if($form->dom->restimates->getText() != $nums['estimates'])     return $this->failed('总预计工时错误');
        if($form->dom->rcost->getText() != $nums['cost'])               return $this->failed('已消耗工时错误');
        if($form->dom->rleft->getText() != $nums['left'])               return $this->failed('剩余工时错误');
        $this->success('数据正确');
    }
}
