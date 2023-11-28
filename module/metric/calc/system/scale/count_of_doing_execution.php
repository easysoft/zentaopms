<?php
/**
 * 按系统统计的进行中执行数。
 * Count of doing execution.
 *
 * 范围：system
 * 对象：execution
 * 目的：scale
 * 度量名称：按系统统计的进行中执行数
 * 单位：个
 * 描述：按系统统计的进行中执行数表示在整个系统中正在进行中的执行项的数量，可以用来了解当前正在进行的任务数量，反映团队的工作进展。
 * 定义：所有的执行个数求和;状态为进行中;过滤已删除的执行;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_doing_execution extends baseCalc
{
    public $dataset = 'getExecutions';

    public $fieldList = array('t1.status');

    public $result = 0;

    public function calculate($row)
    {
        if($row->status == 'doing') $this->result += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('value'));
        return $this->filterByOptions($records, $options);
    }
}
