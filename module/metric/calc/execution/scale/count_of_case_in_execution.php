<?php
/**
 * 按执行统计的测试用例数
 * Count of case in execution.
 *
 * 范围：execution
 * 对象：case
 * 目的：scale
 * 度量名称：按执行统计的测试用例数
 * 单位：个
 * 描述：按执行统计的测试用例数是指执行下的测试用例个数的求和，可以帮助团队评估需求测试用例的覆盖程度。
 * 定义：执行中满足以下条件的测试用例个数的求和，执行用例列表中的用例，过滤已删除的用例，过滤已删除的执行，过滤已删除的项目。
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@chandao.com>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_case_in_execution extends baseCalc
{
    public $dataset = 'getExecutionCases';

    public $fieldList = array('t4.id as execution');

    public $result = array();

    public function calculate($row)
    {
        if(!isset($this->result[$row->execution])) $this->result[$row->execution] = 0;
        $this->result[$row->execution] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('execution', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
