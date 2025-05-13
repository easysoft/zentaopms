<?php
/**
 * 按系统统计的任务进度。
 * Rate of task process.
 *
 * 范围：system
 * 对象：task
 * 目的：rate
 * 度量名称：按系统统计的任务进度
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
class rate_of_hour_process_task extends baseCalc
{
    public $dataset = '';

    public $fieldList = array();

    public $result = array('consumed' => 0, 'left' => 0);

    public function calculate($row)
    {
        if($row->isParent) return;

        $this->result['consumed'] += $row->consumed;
        $this->result['left']     += $row->left;
    }

    public function getResult($options = array())
    {
        $consumed = $this->result['consumed'];
        $left     = $this->result['left'];
        $rate     = $consumed || $left ? round($consumed / ($consumed + $left), 4) : 0;

        $records = array(array('value' => $rate));
        return $this->filterByOptions($records, $options);
    }
}
