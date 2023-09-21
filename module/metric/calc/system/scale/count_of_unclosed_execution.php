<?php
/**
 * 按全局统计的未关闭执行数。
 * Count of unclosed execution.
 *
 * 范围：global
 * 对象：execution
 * 目的：scale
 * 度量名称：按全局统计的未关闭执行数
 * 单位：个
 * 描述：按全局统计的未关闭执行数表示在整个系统中未关闭的执行项的数量，可以用来了解未关闭的执行数量。
 * 定义：复用：;按全局统计的执行总数;按全局统计的已关闭执行数;公式：;按全局统计的未关闭执行数=安全局统计的执行总数-按全局统计的已关闭执行数;
 * 度量库：
 * 收集方式：realtime
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_unclosed_execution extends baseCalc
{
    public $dataset = 'getExecutions';

    public $fieldList = array('t1.status');

    public $result = 0;

    public function calculate($row)
    {
        if($row->status != 'closed') $this->result += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('value'));
        return $this->filterByOptions($records, $options);
    }
}
