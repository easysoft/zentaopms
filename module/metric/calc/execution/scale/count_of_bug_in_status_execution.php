<?php
/**
 * 按状态统计的Bug数。
 * Count of bug in status.
 *
 * 范围：status
 * 对象：bug
 * 目的：scale
 * 度量名称：按状态统计的Bug数
 * 单位：个
 * 描述：按状态统计的Bug数
 * 定义：按状态统计的Bug数
 *
 * @copyright Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    Zemei Wang <wangzemei@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_bug_in_status_execution extends baseCalc
{
    public $dataset = '';

    public $fieldList = array();

    public $result = array();

    public function calculate($row)
    {
        if(!isset($this->result[$row->status])) $this->result[$row->status] = array();
        $this->result[$row->status][$row->id] = $row->id;
    }

    public function getResult($options = array())
    {
        foreach($this->result as $status => $tasks)
        {
            if(!is_array($tasks))
            {
                unset($this->result[$status]);
                continue;
            }

            $this->result[$status] = count($tasks);
        }

        $records = $this->getRecords(array('status', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
