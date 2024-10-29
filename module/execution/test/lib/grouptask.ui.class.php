<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class grouptaskTester extends tester
{
    /**
     * 检查全部展开时分组下数据。
     * Check the data of the group.
     *
     * @param  string $groupData story|status|pri|assignedTo|finishedBy|closedBy|type
     * @param  array  $nums
     * @access public
     * @return void
     */
    public function checkGroupData($groupData, $nums)
    {
        $form  = $this->initForm('execution', 'grouptask', array('execution' => '2', 'groupBy' => $groupData ), 'appIframe-execution');
        preg_match_all('/\d+(?:\.\d+)?/', $form->dom->task->getText(), $matches);
        if($matches[0][0] != $nums['tasks'])   return $this->failed('总任务数目错误');
        if($matches[0][1] != $nums['waiting']) return $this->failed('未开始任务数目错误');
        if($matches[0][2] != $nums['doing'])   return $this->failed('进行中任务数目错误');
        preg_match_all('/\d+(?:\.\d+)?/', $form->dom->time->getText(), $params);
        if($params[0][0] != $nums['estimates']) return $this->failed('总预计工时错误');
        if($params[0][1] != $nums['cost'])      return $this->failed('已消耗工时错误');
        if($params[0][2] != $nums['left'])      return $this->failed('剩余工时错误');
        return $this->success('数据正确');
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
        preg_match_all('/\d+(?:\.\d+)?/', $form->dom->task->getText(), $matches);
        if($matches[0][0] != $nums['tasks'])   return $this->failed('总任务数目错误');
        if($matches[0][1] != $nums['waiting']) return $this->failed('未开始任务数目错误');
        if($matches[0][2] != $nums['doing'])   return $this->failed('进行中任务数目错误');
        preg_match_all('/\d+(?:\.\d+)?/', $form->dom->time->getText(), $params);
        if($params[0][0] != $nums['estimates']) return $this->failed('总预计工时错误');
        if($params[0][1] != $nums['cost'])      return $this->failed('已消耗工时错误');
        if($params[0][2] != $nums['left'])      return $this->failed('剩余工时错误');
        return $this->success('数据正确');
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
        if($form->dom->rtask->getText() != $nums['tasks'])          return $this->failed('总任务数目错误');
        if($form->dom->rdoing->getText() != $nums['doing'])         return $this->failed('进行中任务数目错误');
        if($form->dom->rwaiting->getText() != $nums['waiting'])     return $this->failed('未开始任务数目错误');
        if($form->dom->restimates->getText() != $nums['estimates']) return $this->failed('总预计工时错误');
        if($form->dom->rcost->getText() != $nums['cost'])           return $this->failed('已消耗工时错误');
        if($form->dom->rleft->getText() != $nums['left'])           return $this->failed('剩余工时错误');
        return $this->success('数据正确');
    }
}
