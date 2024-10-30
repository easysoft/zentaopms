<?php
/**
 * 按项目统计的每日解决Bug数。
 * Count of daily resolved bug in project.
 *
 * 范围：project
 * 对象：bug
 * 目的：scale
 * 度量名称：按项目统计的每日解决Bug数
 * 单位：个
 * 描述：按项目统计的每日解决Bug数是指项目每日解决的Bug的数量。该度量项可以帮助我们了解开发团队解决Bug的速度和效率。
 * 定义：项目中Bug数求和，解决日期为某日，过滤已删除的Bug，过滤已删除的项目。
 *
 * @copyright Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    songchenxuan <songchenxuan@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_daily_resolved_bug_in_project extends baseCalc
{
    public $dataset = 'getProjectBugs';

    public $fieldList = array('t1.project', 't1.status', 't1.resolvedDate');

    public $result = array();

    public function calculate($row)
    {
        if($row->status == 'active' || empty($row->resolvedDate)) return false;

        $date = substr($row->resolvedDate, 0, 10);
        list($year, $month, $day) = explode('-', $date);
        if($year == '0000') return false;

        if(!isset($this->result[$row->project]))                      $this->result[$row->project] = array();
        if(!isset($this->result[$row->project][$year]))               $this->result[$row->project][$year] = array();
        if(!isset($this->result[$row->project][$year][$month]))       $this->result[$row->project][$year][$month] = array();
        if(!isset($this->result[$row->project][$year][$month][$day])) $this->result[$row->project][$year][$month][$day] = 0;

        $this->result[$row->project][$year][$month][$day] ++;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('project', 'year', 'month', 'day', 'value'));

        return $this->filterByOptions($records, $options);
    }
}
