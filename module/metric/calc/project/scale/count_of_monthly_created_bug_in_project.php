<?php
/**
 * 按项目统计的月度新增Bug数。
 * Count of monthly created bug in project.
 *
 * 范围：project
 * 对象：bug
 * 目的：scale
 * 度量名称：按项目统计的月度新增Bug数
 * 单位：个
 * 描述：按项目统计的月度新增Bug数是指在某年度新发现的Bug数量。这个度量项反映了系统或项目在某月度出现的新问题数量。月度新增Bug数的增加可能意味着质量控制存在问题，需要及时进行处理和改进。
 * 定义：项目中创建时间在某年某月的Bug个数求和，过滤已删除的Bug，过滤已删除的项目。
 *
 * @copyright Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    songchenxuan <songchenxuan@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_monthly_created_bug_in_project extends baseCalc
{
    public $dataset = 'getProjectBugs';

    public $fieldList = array('t1.project', 't1.openedDate');

    public $result = array();

    public function calculate($row)
    {
        $project    = $row->project;
        $openedDate = $row->openedDate;

        $year = $this->getYear($openedDate);
        if(!$year) return false;

        $month = substr($openedDate, 5, 2);

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
