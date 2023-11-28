<?php
/**
 * 按系统统计的已挂起执行数。
 * Count of suspended execution.
 *
 * 范围：system
 * 对象：execution
 * 目的：scale
 * 度量名称：按系统统计的已挂起执行数
 * 单位：个
 * 描述：按系统统计的已挂起执行数表示在整个系统中已被挂起的执行项的数量，可以用来了解暂停的任务数量，可能是由于需求不明确或其他原因导致。
 * 定义：所有的执行个数求和;状态为已挂起;过滤已删除的执行;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_suspended_execution extends baseCalc
{
    public $dataset = 'getExecutions';

    public $fieldList = array('t1.status');

    public $result = 0;

    public function calculate($row)
    {
        if($row->status == 'suspended') $this->result += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('value'));
        return $this->filterByOptions($records, $options);
    }
}
