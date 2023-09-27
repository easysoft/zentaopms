<?php
/**
 * 按系统统计的年度新增项目数。
 * Count of annual created project.
 *
 * 范围：system
 * 对象：project
 * 目的：scale
 * 度量名称：按系统统计的年度新增项目数
 * 单位：个
 * 描述：按系统统计的年度新增项目数是指某年度新创建的项目数量。这个度量项可以帮助团队了解某年度的项目规模和工作负荷，以及项目管理和资源分配的需求。较高的年度新增项目数可能需要团队根据资源和能力进行优先级和规划管理。
 * 定义：所有的项目个数求和;创建时间为某年;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_annual_created_project extends baseCalc
{
    public $dataset = 'getAllProjects';

    public $fieldList = array('t1.openedDate');

    public $result = array();

    public function calculate($row)
    {
        if(empty($row->openedDate)) return false;

        $year = substr($row->openedDate, 0, 4);
        if($year == '0000') return false;

        if(!isset($this->result[$year])) $this->result[$year] = 0;

        $this->result[$year] += 1;
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $year => $value) $records[] = array('year' => $year, 'value' => $value);
        return $this->filterByOptions($records, $options);
    }
}
