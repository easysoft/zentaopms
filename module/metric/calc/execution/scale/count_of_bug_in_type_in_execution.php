<?php
/**
 * 按类型统计的执行下的Bug数。
 * Count of bug in type in execution.
 *
 * 范围：type
 * 对象：bug
 * 目的：scale
 * 度量名称：按类型统计的执行下的Bug数
 * 单位：个
 * 描述：按类型统计的执行下的Bug数
 * 定义：按类型统计的执行下的Bug数
 *
 * @copyright Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    Zemei Wang <wangzemei@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_bug_in_type_in_execution extends baseCalc
{
    public $dataset = 'getExecutionBugs';

    public $fieldList = array();

    public $result = array();

    public function calculate($row)
    {
        if(!isset($this->result[$row->type])) $this->result[$row->type] = array();
        $this->result[$row->type][$row->id] = $row->id;
    }

    public function getResult($options = array())
    {
        foreach($this->result as $type => $bugs)
        {
            if(!is_array($bugs)) continue;

            $this->result[$type] = count($bugs);
        }

        $records = $this->getRecords(array('type', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
