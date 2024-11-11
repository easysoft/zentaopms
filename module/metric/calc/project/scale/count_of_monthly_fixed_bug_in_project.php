<?php
/**
 * 按项目统计的月度修复Bug数。
 * Count of monthly fixed bug in project.
 *
 * 范围：project
 * 对象：bug
 * 目的：scale
 * 度量名称：按项目统计的月度修复Bug数
 * 单位：个
 * 描述：按产品统计的月度修复Bug数是指每天在产品开发过程中被解决并关闭的Bug的数量。该度量项可以帮助我们了解开发团队解决Bug的速度和效率。
 * 定义：项目中Bug的个数求和\n关闭时间为某年某月\n解决方案为已解决\n过滤已删除的Bug\n过滤已删除的项目\n
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_monthly_fixed_bug_in_project extends baseCalc
{
    public $dataset = 'getProjectBugs';

    public $fieldList = array('t1.id', 't1.project', 't1.resolution', 't1.closedDate');

    public $result = array();

    public function calculate($data)
    {
        $project    = $data->project;
        $resolution = $data->resolution;
        $closedDate = $data->closedDate;

        $year = $this->getYear($closedDate);
        if(!$year) return false;

        if($resolution != 'fixed') return false;

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
