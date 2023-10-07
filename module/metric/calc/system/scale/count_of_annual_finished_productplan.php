<?php
/**
 * 按系统统计的年度完成计划数。
 * Count of annual finished productplan.
 *
 * 范围：system
 * 对象：productplan
 * 目的：scale
 * 度量名称：按系统统计的年度完成计划数
 * 单位：个
 * 描述：按系统统计的年度完成计划数反映了组织在某年度内实际完成的计划数量，用于评估绩效、生产效率和客户满意度，并用于规划和资源优化。
 * 定义：所有的计划个数求和;完成时间为某年;过滤已删除的计划;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_annual_finished_productplan extends baseCalc
{
    public $dataset = 'getPlans';

    public $fieldList = array('t1.finishedDate');

    public $result = array();

    public function calculate($data)
    {
        $finishedDate = $data->finishedDate;
        if(empty($finishedDate)) return false;

        $year = substr($finishedDate, 0, 4);
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
