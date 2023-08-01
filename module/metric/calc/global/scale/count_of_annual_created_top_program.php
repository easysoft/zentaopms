<?php
/**
 * 按全局统计的年度新增一级项目集数。
 * Count of annual created top program.
 *
 * 范围：global
 * 对象：program
 * 目的：scale
 * 度量名称：按全局统计的年度新增一级项目集数
 * 单位：个
 * 描述：按全局统计的年度新增一级项目集数表示每年新建立的一级项目集的数量。此度量项反映了组织每年新开展的重要项目集数量，可以用于评估组织的项目管理的扩张和战略规划情况。
 * 定义：所有的一级项目集的个数求和;创建时间为某年;过滤已删除的项目集;
 * 度量库：
 * 收集方式：realtime
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_annual_created_top_program extends baseCalc
{
    public $dataset = 'getTopPrograms';

    public $fieldList = array('openedDate');

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
