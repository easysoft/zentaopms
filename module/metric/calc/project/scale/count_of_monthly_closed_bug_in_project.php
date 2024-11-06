<?php
/**
 * 按项目统计的月度关闭Bug数。
 * Count of monthly closed bug in project.
 *
 * 范围：project
 * 对象：bug
 * 目的：scale
 * 度量名称：按项目统计的月度关闭Bug数
 * 单位：个
 * 描述：按项目统计的月度关闭Bug数是指在某月度关闭的Bug数量。这个度量项反映了产品开发过程中每月被确认并关闭的Bug的数量。该度量项可以帮助我们了解开发团队对Bug进行确认与关闭的速度和效率。
 * 定义：项目中关闭时间在某年某月的Bug个数求和;过滤已删除的Bug;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_monthly_closed_bug_in_project extends baseCalc
{
    public $dataset = 'getBugs';

    public $fieldList = array('t1.project', 't1.closedDate');

    public $result = array();

    public function calculate($row)
    {
        $project    = $row->project;
        $closedDate = $row->closedDate;

        $year = $this->getYear($closedDate);
        if(!$year) return false;

        $month = substr($closedDate, 5, 2);

        if(!isset($this->result[$project])) $this->result[$project] = array();
        if(!isset($this->result[$project][$year])) $this->result[$project][$year] = array();
        if(!isset($this->result[$project][$year][$month])) $this->result[$project][$year][$month] = 0;

        $this->result[$project][$year][$month] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('project', 'year', 'month', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
