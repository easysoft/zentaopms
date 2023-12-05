<?php
declare(strict_types=1);
/**
 * The model file of holiday module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     holiday
 * @version     $Id
 * @link        http://www.zentao.net
 */
class holidayModel extends model
{
    /**
     * 通过 ID 获取节假日。
     * Get holiday by id.
     *
     * @param  int         $id
     * @access public
     * @return object|bool
     */
    public function getById(int $id): object|bool
    {
        return $this->dao->select('*')->from(TABLE_HOLIDAY)->where('id')->eq($id)->fetch();
    }

    /**
     * 获取节假日列表。
     * Get holiday list.
     *
     * @param  string $year
     * @param  string $type
     * @access public
     * @return array
     */
    public function getList(string $year = '', string $type = 'all'): array
    {
        return $this->dao->select('*')->from(TABLE_HOLIDAY)
            ->where('1=1')
            ->beginIf(!empty($year))
            ->andWhere('year', true)->eq($year)
            ->orWhere('begin')->like("$year-%")
            ->orWhere('end')->like("$year-%")
            ->markright(1)
            ->fi()
            ->beginIf($type != 'all' && $type)->andWhere('type')->eq($type)->fi()
            ->fetchAll('id');
    }

    /**
     * 获取年份列表。
     * Get year pairs.
     *
     * @access public
     * @return array
     */
    public function getYearPairs(): array
    {
        return $this->dao->select('year,year')->from(TABLE_HOLIDAY)->groupBy('year')->orderBy('year_desc')->fetchPairs();
    }

    /**
     * 创建一个节假日。
     * Create a holiday.
     *
     * @param  object   $holiday
     * @access public
     * @return int|bool
     */
    public function create(object $holiday): int|bool
    {
        $this->dao->insert(TABLE_HOLIDAY)->data($holiday)
            ->autoCheck()
            ->batchCheck($this->config->holiday->require->create, 'notempty')
            ->check('end', 'ge', $holiday->begin)
            ->exec();
        $lastInsertID = $this->dao->lastInsertID();

        if(dao::isError()) return false;

        $beginDate = $holiday->begin;
        $endDate   = $holiday->end;

        /* Update project. */
        $this->updateProgramPlanDuration($beginDate, $endDate);
        $this->updateProjectRealDuration($beginDate, $endDate);

        /* Update task. */
        $this->updateTaskPlanDuration($beginDate, $endDate);
        $this->updateTaskRealDuration($beginDate, $endDate);

        return $lastInsertID;
    }

    /**
     * 编辑一个节假日。
     * Edit holiday.
     *
     * @param  object $holiday
     * @access public
     * @return bool
     */
    public function update(object $holiday): bool
    {
        $this->dao->update(TABLE_HOLIDAY)
            ->data($holiday)
            ->autoCheck()
            ->batchCheck($this->config->holiday->require->edit, 'notempty')
            ->check('end', 'ge', $holiday->begin)
            ->where('id')->eq($holiday->id)
            ->exec();

        if(dao::isError()) return false;

        $beginDate = $holiday->begin;
        $endDate   = $holiday->end;

        /* Update project. */
        $this->updateProgramPlanDuration($beginDate, $endDate);
        $this->updateProjectRealDuration($beginDate, $endDate);

        /* Update task. */
        $this->updateTaskPlanDuration($beginDate, $endDate);
        $this->updateTaskRealDuration($beginDate, $endDate);

        return !dao::isError();
    }

    /**
     * 通过开始和结束日期获取节假日。
     * Get holidays by begin and end.
     *
     * @param  string $begin
     * @param  string $end
     * @access public
     * @return array
     */
    public function getHolidays(string $begin, string $end): array
    {
        $records = $this->dao->select('*')->from(TABLE_HOLIDAY)
            ->where('type')->eq('holiday')
            ->andWhere('begin')->ge($begin)
            ->andWhere('end')->le($end)
            ->fetchAll('id');

        $naturalDays = $this->getDaysBetween($begin, $end);

        $holidays = array();
        foreach($records as $record)
        {
            $dates    = $this->getDaysBetween($record->begin, $record->end);
            $holidays = array_merge($holidays, $dates);
        }

        return array_intersect($naturalDays, $holidays);
    }

    /**
     * 获取工作日。
     * Get working days.
     *
     * @param  string $begin
     * @param  string $end
     * @access public
     * @return array
     */
     public function getWorkingDays(string $begin = '', string $end = ''): array
     {
         $records = $this->dao->select('*')->from(TABLE_HOLIDAY)
             ->where('type')->eq('working')
             ->andWhere('begin')->ge($begin)
             ->andWhere('end')->le($end)
             ->fetchAll('id');

         $workingDays = array();
         foreach($records as $record)
         {
             $dates       = $this->getDaysBetween($record->begin, $record->end);
             $workingDays = array_merge($workingDays, $dates);
         }

         return $workingDays;
     }

    /**
     * 获取实际工作日。
     * Get actual working days.
     *
     * @param  string $begin
     * @param  string $end
     * @access public
     * @return array
     */
     public function getActualWorkingDays(string $begin, string $end): array
     {
         if(empty($begin) || empty($end) || $begin == '0000-00-00' || $end == '0000-00-00') return array();

         /* Get holidays, working days and weekend days .*/
         $holidays    = $this->getHolidays($begin, $end);
         $workingDays = $this->getWorkingDays($begin, $end);
         $weekend     = isset($this->config->project->weekend) ? $this->config->project->weekend : 2;

         /* When the start date and end date are the same. */
         $actualDays = array();
         if($begin == $end)
         {
             if(in_array($begin, $workingDays)) return array($begin);
             if(in_array($begin, $holidays))    return array();

             $w = date('w', strtotime($begin));
             if($w == 0 || ($weekend == 2 && $w == 6)) return array();

             return array($begin);
         }

         /* Process actual working days. */
         for($i = 0, $currentDay = $begin; $currentDay < $end; $i ++)
         {
             $currentDay = date('Y-m-d', strtotime("{$begin} + {$i} days"));
             $w          = date('w', strtotime($currentDay));

             if(in_array($currentDay, $workingDays))
             {
                 $actualDays[] = $currentDay;
                 continue;
             }

             if(in_array($currentDay, $holidays)) continue;
             if($w == 0 || ($weekend == 2 && $w == 6)) continue;

             $actualDays[] = $currentDay;
         }

         return $actualDays;
     }

    /**
     * 获取开始和结束日期间的日期。
     * Get the dates between the begin and end.
     *
     * @param  string  $begin
     * @param  string  $end
     * @access public
     * @return array
     */
     public function getDaysBetween(string $begin, string $end): array
     {
         $beginTime = strtotime($begin);
         $endTime   = strtotime($end);
         $days      = ($endTime - $beginTime) / 86400;

         $dateList  = array();
         for($i = 0; $i <= $days; $i ++) $dateList[] = date('Y-m-d', strtotime("+{$i} days", $beginTime));

         return $dateList;
     }

    /**
     * 判断一天是否是节假日。
     * Judge if is holiday.
     *
     * @param  string $date
     * @access public
     * @return bool
     */
    public function isHoliday(string $date): bool
    {
        $record = $this->dao->select('*')->from(TABLE_HOLIDAY)
            ->where('type')->eq('holiday')
            ->andWhere('begin')->le($date)
            ->andWhere('end')->ge($date)
            ->fetch();
        return !empty($record);
    }

    /**
     * 判断一天是否是工作日。
     * Judge if is working days.
     *
     * @param  string $date
     * @access public
     * @return bool
     */
    public function isWorkingDay(string $date): bool
    {
        $record = $this->dao->select('*')->from(TABLE_HOLIDAY)
            ->where('type')->eq('working')
            ->andWhere('begin')->le($date)
            ->andWhere('end')->ge($date)
            ->fetch();
        return !empty($record);
    }

    /**
     * 更新项目的工期。
     * Update project duration.
     *
     * @param  string $beginDate
     * @param  string $endDate
     * @access public
     * @return void
     */
    public function updateProgramPlanDuration(string $beginDate, string $endDate): void
    {
        $updateProjectList = $this->dao->select('id, begin, end')
            ->from(TABLE_PROJECT)
            ->where('status')->ne('done')
            ->andWhere('type')->ne('program')
            ->andWhere('end')->ne(LONG_TIME)
            ->andWhere('begin', true)->between($beginDate, $endDate)
            ->orWhere('end')->between($beginDate, $endDate)
            ->orWhere("(begin < '{$beginDate}' AND end > '{$endDate}')")
            ->markRight(1)
            ->fetchAll();

        foreach($updateProjectList as $project)
        {
            $realDuration = $this->getActualWorkingDays($project->begin, $project->end);
            $realDuration = count($realDuration);

            $this->dao->update(TABLE_PROJECT)->set('planDuration')->eq($realDuration)->where('id')->eq($project->id)->exec();
        }
    }

    /**
     * 测试更新项目实际工期。
     * Update project real duration.
     *
     * @param  string $beginDate
     * @param  string $endDate
     * @access public
     * @return void
     */
    public function updateProjectRealDuration(string $beginDate, string $endDate): void
    {
        $updateProjectList = $this->dao->select('id, realBegan, realEnd')
            ->from(TABLE_PROJECT)
            ->where('status')->ne('done')
            ->andWhere('type')->ne('program')
            ->andwhere('realBegan', true)->between($beginDate, $endDate)
            ->orWhere('realEnd')->between($beginDate, $endDate)
            ->orWhere("(realBegan < '{$beginDate}' AND realEnd > '{$endDate}')")
            ->markRight(1)
            ->fetchAll();

        foreach($updateProjectList as $project)
        {
            $realDuration = $this->getActualWorkingDays($project->realBegan, $project->realEnd);
            $realDuration = count($realDuration);

            $this->dao->update(TABLE_PROJECT)->set('realDuration')->eq($realDuration)->where('id')->eq($project->id)->exec();
        }
    }

    /**
     * 更新任务的计划工期。
     * Update task plan duration.
     *
     * @param  string $beginDate
     * @param  string $endDate
     * @access public
     * @return void
     */
    public function updateTaskPlanDuration(string $beginDate, string $endDate): void
    {
        $updateTaskList = $this->dao->select('id, estStarted, deadline')
            ->from(TABLE_TASK)
            ->where('estStarted')->between($beginDate, $endDate)
            ->orWhere('deadline')->between($beginDate, $endDate)
            ->orWhere("(estStarted < '{$beginDate}' AND deadline > '{$endDate}')")
            ->andWhere('status') ->ne('done')
            ->fetchAll();

        foreach($updateTaskList as $task)
        {
            $planduration = $this->getActualWorkingDays($task->estStarted, $task->deadline);
            $planduration = count($planduration);

            $this->dao->update(TABLE_TASK)->set('planduration')->eq($planduration)->where('id')->eq($task->id)->exec();
        }
    }

    /**
     * 更新任务的实际工期。
     * Update task real duration.
     *
     * @param  string $beginDate
     * @param  string $endDate
     * @access public
     * @return void
     */
    public function updateTaskRealDuration(string $beginDate, string $endDate): void
    {
        $updateTaskList = $this->dao->select('id, realStarted, finishedDate')
            ->from(TABLE_TASK)
            ->where('realStarted')->between($beginDate, $endDate)
            ->orWhere("date_format(finishedDate,'%Y-%m-%d')")->between($beginDate, $endDate)
            ->orWhere("(realStarted < '$beginDate' AND date_format(finishedDate,'%Y-%m-%d') > '$endDate')")
            ->andWhere('status')->ne('done')
            ->fetchAll();

        foreach($updateTaskList as $task)
        {
            $realDuration = $this->getActualWorkingDays($task->realStarted, date('Y-m-d', $task->finishedDate ? strtotime($task->finishedDate) : time()));
            $realDuration = count($realDuration);

            $this->dao->update(TABLE_TASK)->set('realDuration')->eq($realDuration)->where('id')->eq($task->id)->exec();
        }
    }

    /**
     * Get holidays by api.
     *
     * @param  string $year
     * @access public
     * @return array
     */
    public function getHolidayByAPI($year = '')
    {
        if(empty($year)) $year = date('Y');
        $apiRoot = sprintf($this->config->holiday->apiRoot, $year);
        $data    = json_decode(common::http($apiRoot));
        $days    = isset($data->days) ? (array)$data->days : array();

        $holidays   = array();
        $privDay    = 0;
        $prevDayOff = true;
        $daysIndex  = count($days) - 1;
        foreach($days as $index => $day)
        {
            if($index < $daysIndex and (strtotime($day->date) - $privDay > 86400 or $day->isOffDay != $prevDayOff))
            {
                $holiday = new stdClass();
                $holiday->type  = $day->isOffDay ? 'holiday' : 'working';
                $holiday->name  = $day->name . zget($this->lang->holiday->typeList, $holiday->type);
                $holiday->begin = $day->date;
                $holiday->end   = '';
                $holidays[] = $holiday;
            }

            if(isset($holiday) or $index == $daysIndex)
            {
                $holidayNum = count($holidays);
                if($holidayNum > 1)
                {
                    if(isset($holiday))
                    {
                        $holidays[$holidayNum - 2]->end = date('Y-m-d', $privDay);
                    }
                    else
                    {
                        $holidays[$holidayNum - 1]->end = $day->date;
                    }
                }
                if(isset($holiday)) unset($holiday);
            }

            $privDay    = strtotime($day->date);
            $prevDayOff = $day->isOffDay;
        }
        return $holidays;
    }

    /**
     * Batch create holiday.
     *
     * @param  array  $holidays
     * @access public
     * @return int|bool
     */
    public function batchCreate($holidays)
    {
        foreach($holidays as $holiday)
        {
            $this->dao->insert(TABLE_HOLIDAY)->data($holiday)
                ->autoCheck()
                ->batchCheck($this->config->holiday->require->create, 'notempty')
                ->check('end', 'ge', $holiday->begin)
                ->exec();
        }
        if(dao::isError()) return false;

        $lastInsertID = $this->dao->lastInsertID();
        return $lastInsertID;
    }
}
