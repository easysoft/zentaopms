<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class grouptaskTester extends tester
{
    /**
     * 检查全部展开时分组下数据。
     * Check the data of the group.、
     *
     * @param  string $groupData story|status|priority|assignedTo|finishedBy|closedBy|type
     * @param  array  $nums
     * @access public
     * @return void
     */
    public function checkGroupData($groupData, $nums)
    {
        $form = $this->initForm('execution', 'grouptask', array('execution' => '2' ), 'appIframe-execution');
        $form->dom->dropdownBtn->click();
        $form->wait(1);
        $form->dom->$groupData->click();
        $form->wait(1);
        if($form->dom->group->getText() != $this->lang->task->noStory) return $this->falied('研发需求分类错误');
        if($form->dom->tasks->getText() != $nums['tasks'])             return $this->falied('总任务数目错误');
        if($form->dom->waiting->getText() != $nums['waiting'])         return $this->falied('未开始任务数目错误');
        if($form->dom->doing->getText() != $nums['doing'])             return $this->falied('进行中任务数目错误');
        if($form->dom->estimates->getText() != $nums['estimates'])     return $this->falied('总预计工时错误');
        if($form->dom->cost->getText() != $nums['cost'])               return $this->falied('已消耗工时错误');
        if($form->dom->left->getText() != $nums['left'])               return $this->falied('剩余工时错误');
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
        $form = $this->initForm('execution', 'grouptask', array('execution' => '2' ), 'appIframe-execution');
        $form->dom->taskLinkedStoryBtn->click();
        $form->wait(1);
        if($form->dom->group->getText() != $this->lang->task->noStory) return $this->falied('研发需求分类错误');
        if($form->dom->tasks->getText() != $nums['tasks'])             return $this->falied('总任务数目错误');
        if($form->dom->waiting->getText() != $nums['waiting'])         return $this->falied('未开始任务数目错误');
        if($form->dom->doing->getText() != $nums['doing'])             return $this->falied('进行中任务数目错误');
        if($form->dom->estimates->getText() != $nums['estimates'])     return $this->falied('总预计工时错误');
        if($form->dom->cost->getText() != $nums['cost'])               return $this->falied('已消耗工时错误');
        if($form->dom->left->getText() != $nums['left'])               return $this->falied('剩余工时错误');
        $this->success('数据正确');
    }
}
