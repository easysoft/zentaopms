<?php
/**
 * 按项目统计的年度修复Bug数。
 * Count of annual fixed bug in project.
 *
 * 范围：project
 * 对象：bug
 * 目的：scale
 * 度量名称：按项目统计的年度修复Bug数
 * 单位：个
 * 描述：按项目统计的年度修复Bug数是指在某年度解决并关闭的Bug数量。这个度量项反映了项目在某年度解决的问题数量。年度修复Bug数越多可能说明开发团队在Bug解决方面的工作效率较高。
 * 定义：项目中Bug的个数求和，关闭时间为某年，解决方案为已解决，过滤已删除的Bug，过滤已删除的项目。
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_annual_fixed_bug_in_project extends baseCalc
{
    public $dataset = 'getProjectBugs';

    public $fieldList = array('t1.project', 't1.resolution', 't1.closedDate');

    public $result = array();

    public function calculate($data)
    {
        $project    = $data->project;
        $resolution = $data->resolution;
        $closedDate = $data->closedDate;

        $year = $this->getYear($closedDate);
        if(!$year) return false;

        if($resolution != 'fixed') return false;

        if(!isset($this->result[$project])) $this->result[$project] = array();
        if(!isset($this->result[$project][$year])) $this->result[$project][$year] = 0;

        $this->result[$project][$year] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('project', 'year', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
