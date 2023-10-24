<?php
/**
 * 按系统统计的年度完成项目中项目的延期完成率。
 * Rate of delayed finished project which annual finished.
 *
 * 范围：system
 * 对象：project
 * 目的：rate
 * 度量名称：按系统统计的年度完成项目中项目的延期完成率
 * 单位：个
 * 描述：按系统统计的年度完成项目中项目的延期完成率是指按系统统计的年度完成项目中延期完成项目数与关闭项目数之比。这个度量项可以帮助团队评估某年度项目按期关闭的能力和效果，并作为项目管理的绩效指标之一。较高的延期完成率可能需要团队关注项目计划和资源安排的问题。
 * 定义：复用：;按系统统计的年度关闭项目数;按系统统计的年度延期关闭项目数;公式：;按系统统计的年度项目延期关闭率=按系统统计的年度延期关闭项目数/按系统统计的年度关闭项目数;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class rate_of_delayed_finished_project_which_annual_finished extends baseCalc
{
    public $dataset = 'getAllProjects';

    public $fieldList = array('t1.status', 't1.closedDate', 't1.realEnd', 't1.firstEnd');

    public $result = array();

    public function calculate($row)
    {
        if(empty($row->closedDate)) return false;

        $closedYear = substr($row->closedDate, 0, 4);

        if(!isset($this->result[$closedYear])) $this->result[$closedYear] = array('closed' => 0, 'delayed' => 0);
        if($row->status == 'closed') $this->result[$closedYear]['closed'] ++;
        if($row->status == 'closed' && $row->realEnd > $row->firstEnd) $this->result[$closedYear]['delayed'] ++;
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $year => $result)
        {
            $rate = $result['closed'] ? round($result['delayed'] / $result['closed'], 4) : 0;
            $records[] = array('year' => $year, 'value' => $rate);
        }
        return $this->filterByOptions($records, $options);
    }
}
