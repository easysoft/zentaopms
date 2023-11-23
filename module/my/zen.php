<?php
declare(strict_types=1);
/**
 * The zen file of my module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     my
 * @link        https://www.zentao.net
 */
class myZen extends my
{
    /**
     * 构造任务数据。
     * Build task data.
     *
     * @param  array     $tasks
     * @access protected
     * @return array
     */
    protected function buildTaskData(array $tasks): array
    {
        foreach($tasks as $task)
        {
            if($task->parent > 0) $parents[$task->parent] = $task->parent;
            $task->estimateLabel = $task->estimate . $this->lang->execution->workHourUnit;
            $task->consumedLabel = $task->consumed . $this->lang->execution->workHourUnit;
            $task->leftLabel     = $task->left     . $this->lang->execution->workHourUnit;
            $task->status        = !empty($task->storyStatus) && $task->storyStatus == 'active' && $task->latestStoryVersion > $task->storyVersion && !in_array($task->status, array('cancel', 'closed')) ? $this->lang->my->storyChanged : $task->status;
            if($task->parent)
            {
                if(isset($tasks[$task->parent]))
                {
                    $tasks[$task->parent]->hasChild = true;
                }
                else
                {
                    $task->parent = 0;
                }
            }
        }
        return $tasks;
    }

    /**
     * 构造用例数据。
     * Build case data.
     *
     * @param  array     $cases
     * @param  string    $type  assigntome|openedbyme
     * @access protected
     * @return array
     */
    protected function buildCaseData(array $cases, string $type): array
    {
        $cases = $this->loadModel('story')->checkNeedConfirm($cases);
        $cases = $this->loadModel('testcase')->appendData($cases, $type == 'assigntome' ? 'run' : 'case');

        $failCount = 0;
        foreach($cases as $case)
        {
            if($case->lastRunResult && $case->lastRunResult != 'pass') $failCount ++;
            if($case->needconfirm)
            {
                $case->status = $this->lang->story->changed;
            }
            else if(isset($case->fromCaseVersion) and $case->fromCaseVersion > $case->version and !$case->needconfirm)
            {
                $case->status = $this->lang->testcase->changed;
            }
            if(!$case->lastRunResult) $case->lastRunResult = $this->lang->testcase->unexecuted;
        }
        $this->view->failCount = $failCount;
        return $cases;
    }
}

