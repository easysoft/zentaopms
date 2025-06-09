<?php
/**
 * 按执行统计的执行用例产生的Bug总数。
 * Count of bug of executing use cases in execution.
 *
 * 范围：execution
 * 对象：bug
 * 目的：scale
 * 度量名称：按执行统计的执行用例产生的Bug总数
 * 单位：个
 * 描述：按执行统计的执行用例产生的Bug总数是指在执行中发现的有效Bug的数量。这个度量项反映了执行的质量情况。执行用例产生的Bug数越多可能代表执行的代码质量存在的问题越多，需要进行进一步的解决和改进。
 * 定义：执行中新增Bug个数求和;解决方案为已解决、延期处理和不予解决或状态为激活;过滤已删除的执行;过滤已删除的项目;过滤已删除的产品。
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    Zemei Wang <wangzemei@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_case_bug_in_execution extends baseCalc
{
    public $dataset = 'getExecutionBugs';

    public $fieldList = array('t1.execution', 't1.case');

    public $result = array();

    public function calculate($row)
    {
        if(!empty($row->case))
        {
            if(!isset($this->result[$row->execution])) $this->result[$row->execution] = 0;
            $this->result[$row->execution] ++;
        }
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('execution', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
