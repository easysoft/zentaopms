<?php
/**
 * 按项目统计的每日新增Bug数。
 * Count of daily created bug in project.
 *
 * 范围：project
 * 对象：bug
 * 目的：scale
 * 度量名称：按项目统计的每日新增Bug数
 * 单位：个
 * 描述：按项目统计的每日新增Bug数是指在每天的项目开发过程中新发现并记录的Bug数量。该度量项可以体现项目开发过程中Bug的发现速度和趋势，较高的新增Bug数可能意味着存在较多的问题需要解决，同时也可以帮助识别项目开发过程中的瓶颈和潜在的质量风险。
 * 定义：项目中Bug数求和，创建时间为某日，过滤已删除的Bug，过滤已删除的项目。
 *
 * @copyright Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    songchenxuan <songchenxuan@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_daily_created_bug_in_project extends baseCalc
{
    public $dataset = 'getProjectBugs';

    public $fieldList = array('t1.project', 't1.openedDate');

    public $result = array();

    public function calculate($row)
    {
        $year = $this->getYear($row->openedDate);
        if(!$year) return false;

        $date = substr($row->openedDate, 0, 10);
        list($year, $month, $day) = explode('-', $date);

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
