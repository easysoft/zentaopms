<?php
/**
 * 按人员统计的创建用例数。
 * Count of created case in user.
 *
 * 范围：user
 * 对象：case
 * 目的：scale
 * 度量名称：按人员统计的创建用例数
 * 单位：个
 * 描述：按人员统计的创建用例数是指每个人创建修复用例总量。该度量项可以帮助我们了解每个人对已解决的用例进行确认与关闭的速度和效率。
 * 定义：截止当前时间;统计每个人创建用例数的求和;过滤已删除的用例;过滤已删除的产品;
 * 度量库：
 * 收集方式：realtime
 *
 */
class count_of_created_case_in_user extends baseCalc
{
    public $dataset = '';

    public $fieldList = array();

    public $result = array();

    public function calculate($row)
    {
        if(!isset($this->result[$row->openedBy])) $this->result[$row->openedBy] = array();
        $this->result[$row->openedBy][$row->id] = $row->id;
    }

    public function getResult($options = array())
    {
        foreach($this->result as $openedBy => $cases)
        {
            if(!is_array($cases))
            {
                unset($this->result[$openedBy]);
                continue;
            }
            $this->result[$openedBy] = count($cases);
        }

        $records = $this->getRecords(array('user', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
