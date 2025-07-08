<?php
/**
 * 按任务类型和状态统计的任务进度。
 * Rate of task process by type and status.
 *
 * 范围：task
 * 对象：task
 * 目的：rate
 * 度量名称：按任务类型和状态统计的任务进度
 * 单位：%
 * 描述：按任务类型和状态统计的任务进度。
 * 定义：按任务类型和状态统计的任务进度。
 *
 * @copyright Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    Yanyi Cao <caoyanyi@chandao.com>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_task_in_type_status extends baseCalc
{
    public $dataset = '';

    public $fieldList = array();

    public $result = array();

    public function calculate($row)
    {
        if(!isset($this->result[$row->type])) $this->result[$row->type] = array();
        if(!isset($this->result[$row->type][$row->status])) $this->result[$row->type][$row->status] = 0;

        $this->result[$row->type][$row->status]++;
    }

    public function getResult($options = array())
    {
        return $this->filterByOptions($this->result, $options);
    }
}
