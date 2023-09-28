<?php
/**
 * 按系统统计的年度关闭计划数。
 * Count of annual closed productplan.
 *
 * 范围：system
 * 对象：productplan
 * 目的：scale
 * 度量名称：按系统统计的年度关闭计划数
 * 单位：个
 * 描述：按系统统计的年度关闭计划数反映了组织在某年度内关闭的计划数量，用于评估组织的计划管理效能、资源优化和成本控制，并提供学习机会和产品组合优化的参考。
 * 定义：所有的计划个数求和;关闭时间为某年;过滤已删除的计划;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_annual_closed_productplan extends baseCalc
{
    public $dataset = 'getPlans';

    public $fieldList = array('t1.closedDate');

    public $result = array();

    public function calculate($data)
    {
        $closedDate = $data->closedDate;
        if(empty($closedDate)) return false;

        $year = substr($closedDate, 0, 4);
        if($year == '0000') return false;

        if(!isset($this->result[$year])) $this->result[$year] = 0;

        $this->result[$year] += 1;
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $year => $value)
        {
            $records[] = array('year' => $year, 'value' => $value);
        }
        return $this->filterByOptions($records, $options);
    }
}
