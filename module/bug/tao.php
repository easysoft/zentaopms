<?php
declare(strict_types=1);
class bugTao extends bugModel
{
    /**
     * Get bug details, including all contents of the TABLE_BUG, execution name, associated story name, associated story status, associated story version, associated task name, and associated plan name.
     * 获取bug的详情，包含bug表的所有内容、所属执行名称、关联需求名称、关联需求状态、关联需求版本、关联任务名称、关联计划名称
     *
     * @param  int   $bugID
     * @access public
     * @return object|false
     */
    protected function fetchBugInfo(int $bugID): object|false
    {
        return $this->dao->select('t1.*, t2.name AS executionName, t3.title AS storyTitle, t3.status AS storyStatus, t3.version AS latestStoryVersion, t4.name AS taskName, t5.title AS planName')
            ->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.execution = t2.id')
            ->leftJoin(TABLE_STORY)->alias('t3')->on('t1.story = t3.id')
            ->leftJoin(TABLE_TASK)->alias('t4')->on('t1.task = t4.id')
            ->leftJoin(TABLE_PRODUCTPLAN)->alias('t5')->on('t1.plan = t5.id')
            ->where('t1.id')->eq((int)$bugID)->fetch();
    }

    /**
     * Get cases created by bug.
     * 获取bug建的用例.
     *
     * @param  int    $bugID
     * @access public
     * @return array
     */
    protected function getCasesFromBug(int $bugID): array
    {
        return $this->dao->select('id, title')->from(TABLE_CASE)->where('`fromBug`')->eq($bugID)->fetchPairs();
    }

    /**
     * Get an array of id and title pairs by buglist.
     * 传入一个buglist，获得bug的id和title键值对数组.
     *
     * @param  int    $bugList
     * @access public
     * @return array
     */
    protected function getBugPairsByList(string|array $bugList): array
    {
        return $this->dao->select('id,title')->from(TABLE_BUG)->where('id')->in($bugList)->fetchPairs();
    }

    /**
     * Get object title/name base on the params.
     * 根据传入的参数，获取对象名称.
     *
     * @param  int    $objectID
     * @param  string $table
     * @param  string $field
     * @access public
     * @return string
     */
    protected function getNameFromTable(int $objectID, string $table, string $field): string
    {
        return $this->dao->findById($objectID)->from($table)->fields($field)->fetch($field);
    }

    /**
     * Call checkDelayBug in foreach to check if the bug is delay.
     * 循环调用checkDelayBug，检查bug是否延期
     *
     * @param  array  $bugs
     * @access public
     * @return array
     */
    protected function checkDelayedBugs(array $bugs): array
    {
        foreach ($bugs as $bug) $bug = $this->checkDelayBug($bug);

        return $bugs;
    }

    /**
     * If the bug is delayed, add the bug->delay field to show the delay time (day).
     * 如果bug延期，添加bug->delay字段，内容为延期的时长（天）
     *
     * @param  object $bug
     * @access public
     * @return object
     */
    protected function checkDelayBug(object $bug): object
    {
        /* Delayed or not? */
        if(!helper::isZeroDate($bug->deadline))
        {
            if($bug->resolvedDate and !helper::isZeroDate($bug->resolvedDate))
            {
                $delay = helper::diffDate(substr($bug->resolvedDate, 0, 10), $bug->deadline);
            }
            elseif($bug->status == 'active')
            {
                $delay = helper::diffDate(helper::today(), $bug->deadline);
            }

            if(isset($delay) and $delay > 0) $bug->delay = $delay;
        }

        return $bug;
    }
}
