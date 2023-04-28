<?php
declare(strict_types=1);
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
     *
     * @param  int    $projectID
     * @param  object $project
     * @access protected
     * @return bool
     */
    protected function doStart(int $projectID, object $project): bool
    {
        $this->dao->update(TABLE_PROJECT)->data($project)
            ->autoCheck()
            ->check($this->config->project->start->requiredFields, 'notempty')
            ->checkIF($project->realBegan != '', 'realBegan', 'le', helper::today())
            ->checkFlow()
            ->where('id')->eq($projectID)
            ->exec();

        return !dao::isError();
    }

    /**
     * Update project table when suspend a project.
     *
     * @param  int    $projectID
     * @param  object $project
     *
     * @access protected
     * @return bool
     */
    protected function doSuspend(int $projectID, object $project): bool
    {
        $this->dao->update(TABLE_PROJECT)->data($project)
            ->autoCheck()
            ->checkFlow()
            ->where('id')->eq($projectID)
            ->exec();

        return !dao::isError();
    }

    /**
     * Update project table when close a project.
     *
     * @param  int    $projectID
     * @param  object $project
     * @param  object $oldProject
     *
     * @access protected
     * @return bool
     */
    protected function doClosed(int $projectID, object $project, object $oldProject): bool
    {
        $this->dao->update(TABLE_PROJECT)->data($project)
            ->autoCheck()
            ->check($this->config->project->close->requiredFields, 'notempty')
            ->checkIF($project->realEnd != '', 'realEnd', 'le', helper::today())
            ->checkIF($project->realEnd != '', 'realEnd', 'ge', $oldProject->realBegan)
            ->checkFlow()
            ->where('id')->eq($projectID)
            ->exec();

        return !dao::isError();
    }

    /**
     * Update project.
     *
     * @param  int    $projectID
     * @param  object $project
     * @access protected
     * @return bool
     */
    protected function doActivate(int $projectID ,object $project): bool
    {
        $this->dao->update(TABLE_PROJECT)->data($project , 'readjustTime, readjustTask, comment')
            ->autoCheck()
            ->checkFlow()
            ->where('id')->eq((int)$projectID)
            ->exec();

        return !dao::isError();
    }

    /**
     * Fetch undone tasks.
     *
     * @param  int $projectID
     * @access protected
     * @return array|false
     */
    protected function fetchUndoneTasks(int $projectID): array|false
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
     * @return bool
     */
    protected function updateTasksStartAndEndDate(array $tasks): bool
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

                $this->dao->update(TABLE_TASK)
                    ->set('estStarted')->eq($estStarted)
                    ->set('deadline')->eq($deadline)
                    ->where('id')->eq($task->id)
                    ->exec();

                if(dao::isError()) return false;
            }
            else
            {
                $taskOffset = helper::diffDate($task->deadline, $oldProject->begin);
                $deadline   = date('Y-m-d', $beginTimeStamp + $taskOffset * 24 * 3600);

                if($deadline > $project->end) $deadline = $project->end;
                $this->dao->update(TABLE_TASK)->set('deadline')->eq($deadline)->where('id')->eq($task->id)->exec();

                if(dao::isError()) return false;
            }
        }

        return true;
    }

    /**
     * Get project details, including all contents of the TABLE_PROJECT.
     * 获取项目的详情，包含project表的所有内容。
     *
     * @param  int       $projectID
     * @access protected
     * @return object|false
     */
    protected function fetchProjectInfo(int $projectID): object|false
    {
        $project = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($projectID)->fetch();

        /* Filter the date is empty or 1970. */
        if($project and helper::isZeroDate($project->end)) $project->end = '';
        return $project;
    }
}
