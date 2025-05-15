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
class count_of_task_in_status_type extends baseCalc
{
    public $dataset = '';

    public $fieldList = array();

    public $result = array();

    public function calculate($row)
    {
        if(!isset($this->result[$row->status])) $this->result[$row->status] = array();
        if(!isset($this->result[$row->status][$row->type])) $this->result[$row->status][$row->type] = 0;

        $this->result[$row->status][$row->type]++;
    }

    public function getResult($options = array())
    {
        return $this->filterByOptions($this->result, $options);
    }
}
