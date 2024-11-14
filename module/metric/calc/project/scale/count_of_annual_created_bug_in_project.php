<?php
/**
 * 按项目统计的年度新增Bug数。
 * Count of annual created bug in project.
 *
 * 范围：project
 * 对象：bug
 * 目的：scale
 * 度量名称：按项目统计的年度新增Bug数
 * 单位：个
 * 描述：按项目统计的年度新增Bug数是指项目在某年度新发现的Bug数量。这个度量项反映了项目在某年度出现的新问题数量。年度新增Bug数越多可能意味着质量控制存在问题，需要及时进行处理和改进。
 * 定义：项目中Bug的个数求和，创建时间为某年，过滤已删除的Bug，过滤已删除的项目
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    songchenxuan <songchenxuan@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_annual_created_bug_in_project extends baseCalc
{
    public $dataset = 'getProjectBugs';

    public $fieldList = array('t1.openedDate', 't1.project');

    public $result = array();

    public function calculate($data)
    {
        $project    = $data->project;
        $openedDate = $data->openedDate;

        $year = $this->getYear($openedDate);
        if(!$year) return false;

        if(!isset($this->result[$project])) $this->result[$project] = array();
        if(!isset($this->result[$project][$year])) $this->result[$project][$year] = 0;
        $this->result[$project][$year] += 1;
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $project => $years)
        {
            foreach($years as $year => $value)
            {
                $records[] = array('project' => $project, 'year' => $year, 'value' => $value);
            }
        }

        return $this->filterByOptions($records, $options);
    }
}
