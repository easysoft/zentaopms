<?php
/**
 * 按优先级统计的任务数。
 * Count of task in pri.
 *
 * 范围：pri
 * 对象：task
 * 目的：scale
 * 度量名称：按优先级统计的任务数
 * 单位：个
 * 描述：按优先级统计的任务数
 * 定义：按优先级统计的任务数
 *
 * @copyright Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    Yanyi Cao <caoyanyi@chandao.com>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_task_in_pri extends baseCalc
{
    public $dataset = '';

    public $fieldList = array();

    public $result = array();

    public function calculate($row)
    {
        if(!isset($this->result[$row->pri])) $this->result[$row->pri] = array();
        $this->result[$row->pri][$row->id] = $row->id;
    }

    public function getResult($options = array())
    {
        foreach($this->result as $pri => $tasks)
        {
            if(!is_array($tasks)) continue;

            $this->result[$pri] = count($tasks);
        }

        $records = $this->getRecords(array('pri', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
