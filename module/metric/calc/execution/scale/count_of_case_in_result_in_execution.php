<?php
/**
 * 按结果统计的执行下的用例数。
 * Count of case in result in execution.
 *
 * 范围：lastRunResult
 * 对象：case
 * 目的：scale
 * 度量名称：按结果统计的执行下的用例数
 * 单位：个
 * 描述：按结果统计的执行下的用例数
 * 定义：按结果统计的执行下的用例数
 *
 * @copyright Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    Zemei Wang <wangzemei@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_case_in_result_in_execution extends baseCalc
{
    public $dataset = '';

    public $fieldList = array();

    public $result = array();

    public function calculate($row)
    {
        if(!isset($this->result[$row->lastRunResult])) $this->result[$row->lastRunResult] = array();
        $this->result[$row->lastRunResult][$row->id] = $row->id;
    }

    public function getResult($options = array())
    {
        foreach($this->result as $lastRunResult => $cases)
        {
            if(!is_array($cases))
            {
                unset($this->result[$lastRunResult]);
                continue;
            }
            $this->result[$lastRunResult] = count($cases);
        }

        $records = $this->getRecords(array('result', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
