<?php declare(strict_types=1);
/**
 * The tao file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunguangming <sunguangming@easycorp.ltd>
 * @link        https://www.zentao.net
 */
class projectTao extends projectModel
{
    /**
     * Update project table when start a project.
     * @param  int    $projectID
     * @param  object $project
     * @access protected
     * @return void
     */
    protected function doStart(int $projectID, object $project) :void
    {
        $this->dao->update(TABLE_PROJECT)->data($project)
            ->autoCheck()
            ->check($this->config->project->start->requiredFields, 'notempty')
            ->checkIF($project->realBegan != '', 'realBegan', 'le', helper::today())
            ->checkFlow()
            ->where('id')->eq((int)$projectID)
            ->exec();
    }

    /**
     * Update project.
     *
     * @param  object $project
     * @access protected
     * @return void
     */
    protected function updateProject(object $project) :void
    {
        $this->dao->update(TABLE_PROJECT)->data($project)
            ->autoCheck()
            ->checkFlow()
            ->where('id')->eq((int)$project->id)
            ->exec();
    }

    /**
     * Fetch undone tasks.
     *
     * @param  int $projectID
     * @access protected
     * @return array
     */
    protected function fetchUndoneTasks(int $projectID) :array
    {
        return $this->dao->select('id,estStarted,deadline,status')->from(TABLE_TASK)
            ->where('deadline')->notZeroDate()
            ->andWhere('status')->in('wait,doing')
            ->andWhere('project')->eq($projectID)
            ->fetchAll();
    }

    /**
     * Update start and end date of tasks.
     *
     * @param  array $tasks
     * @access protected
     * @return void
     */
    protected function updateTasksStartAndEndDate(array $tasks) :void
    {
        foreach($tasks as $task)
        {
            if($task->status == 'wait' and !helper::isZeroDate($task->estStarted))
            {
                $taskDays   = helper::diffDate($task->deadline, $task->estStarted);
                $taskOffset = helper::diffDate($task->estStarted, $oldProject->begin);

                $estStartedTimeStamp = $beginTimeStamp + $taskOffset * 24 * 3600;
                $estStarted = date('Y-m-d', $estStartedTimeStamp);
                $deadline   = date('Y-m-d', $estStartedTimeStamp + $taskDays * 24 * 3600);

                if($estStarted > $project->end) $estStarted = $project->end;
                if($deadline > $project->end)   $deadline   = $project->end;
                $this->dao->update(TABLE_TASK)->set('estStarted')->eq($estStarted)->set('deadline')->eq($deadline)->where('id')->eq($task->id)->exec();
            }
            else
            {
                $taskOffset = helper::diffDate($task->deadline, $oldProject->begin);
                $deadline   = date('Y-m-d', $beginTimeStamp + $taskOffset * 24 * 3600);

                if($deadline > $project->end) $deadline = $project->end;
                $this->dao->update(TABLE_TASK)->set('deadline')->eq($deadline)->where('id')->eq($task->id)->exec();
            }
        }
    }
}
