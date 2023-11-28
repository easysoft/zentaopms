<?php
/**
 * 按系统统计的年度关闭一级项目集数。
 * Count of annual closed top program.
 *
 * 范围：system
 * 对象：program
 * 目的：scale
 * 度量名称：按系统统计的年度关闭一级项目集数
 * 单位：个
 * 描述：按系统统计的年度关闭一级项目集数反映了系统中每年结束的不同战略目标的项目集数量及情况，用于评估组织的战略目标管理的绩效和成果。
 * 定义：所有的一级项目集的个数求和;关闭时间为某年;状态为已关闭;过滤已删除的项目集;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_annual_closed_top_program extends baseCalc
{
    public $dataset = 'getTopPrograms';

    public $fieldList = array('status', 'closedDate');

    public $result = array();

    public function calculate($row)
    {
        if(empty($row->closedDate) or $row->status != 'closed') return false;

        $year = substr($row->closedDate, 0, 4);
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
