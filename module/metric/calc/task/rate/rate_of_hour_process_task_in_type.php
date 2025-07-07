<?php
/**
 * 按任务类型统计的任务进度。
 * Rate of task process by type.
 *
 * 范围：task
 * 对象：task
 * 目的：rate
 * 度量名称：按任务类型统计的任务进度
 * 单位：%
 * 描述：按系统统计的任务进度是指已消耗工时占相对于已消耗工时数+剩余工时数的比例。
 * 定义：按系统统计的已消耗工时数;按系统统计的剩余工时数;公式：已消耗工时数÷（已消耗工时数+剩余工时数）;
 *
 * @copyright Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    Yanyi Cao <caoyanyi@chandao.com>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class rate_of_hour_process_task_in_type extends baseCalc
{
    public $dataset = '';

    public $fieldList = array();

    public $result = array();

    public function calculate($row)
    {
        if($row->isParent) return;
        if(!isset($this->result[$row->type])) $this->result[$row->type] = array('consumed' => 0, 'left' => 0, 'type' => $row->type, 'rate' => 0);

        $this->result[$row->type]['consumed'] += $row->consumed;
        $this->result[$row->type]['left']     += $row->left;
    }

    public function getResult($options = array())
    {
        foreach($this->result as $type => $result)
        {
            $consumed = $result['consumed'];
            $left     = $result['left'];
            $rate     = $consumed || $left ? round($consumed / ($consumed + $left), 4) : 0;

            $this->result[$type]['rate'] = $rate;
        }

        return $this->filterByOptions($this->result, $options);
    }
}
