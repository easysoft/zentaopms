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
     * @param  array  $tasks
     * @access public
     * @return array
     */
    public function buildTaskData(array $tasks): array
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
}

