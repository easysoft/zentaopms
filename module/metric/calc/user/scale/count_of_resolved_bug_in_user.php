<?php
/**
 * 按人员统计的解决Bug数。
 * Count of resolved bug in user.
 *
 * 范围：user
 * 对象：bug
 * 目的：scale
 * 度量名称：按人员统计的解决Bug数
 * 单位：个
 * 描述：按人员统计的解决Bug数是指每个人解决修复Bug总量。该度量项可以帮助我们了解每个人对已解决的Bug进行确认与关闭的速度和效率。
 * 定义：截止当前时间;统计每个人解决Bug数的求和;过滤已删除的Bug;过滤已删除的产品;
 * 度量库：
 * 收集方式：realtime
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    Zemei Wang <wangzemei@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_resolved_bug_in_user extends baseCalc
{
    public $dataset = '';

    public $fieldList = array();

    public $result = array();

    public function calculate($row)
    {
        if(!isset($this->result[$row->resolvedBy])) $this->result[$row->resolvedBy] = array();
        $this->result[$row->resolvedBy][$row->id] = $row->id;
    }

    public function getResult($options = array())
    {
        foreach($this->result as $resolvedBy => $bugs)
        {
            if(!is_array($bugs))
            {
                unset($this->result[$resolvedBy]);
                continue;
            }

            $this->result[$resolvedBy] = count($bugs);
        }

        $records = $this->getRecords(array('user', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
