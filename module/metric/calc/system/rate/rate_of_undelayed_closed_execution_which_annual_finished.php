<?php
/**
 * 按系统统计的年度完成执行中执行的按期关闭率。
 * Rate of undelayed closed execution which annual finished.
 *
 * 范围：system
 * 对象：execution
 * 目的：rate
 * 度量名称：按系统统计的年度完成执行中执行的按期关闭率
 * 单位：%
 * 描述：按系统统计的年度完成执行中执行的按期关闭率是指某年度按预定计划时间关闭的执行数量与某年度关闭执行执行数量之比。这个度量项可以帮助团队评估某年度执行按期关闭的能力和效果，并作为执行管理的绩效指标之一。较高的执行按期关闭率表示团队能够按时完成执行和项目。
 * 定义：复用：;按系统统计的年度关闭执行数;按系统统计的年度完成执行中按期完成执行数;公式：;按系统统计的年度完成执行中执行的按期关闭率=按系统统计的年度完成执行中按期完成执行数/按系统统计的年度关闭执行数;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class rate_of_undelayed_closed_execution_which_annual_finished extends baseCalc
{
    public $dataset = 'getExecutions';

    public $fieldList = array('t1.status', 't1.closedDate', 't1.firstEnd');

    public $result = array();

    public function calculate($row)
    {
        if(empty($row->closedDate)) return false;

        $year = substr($row->closedDate, 0, 4);
        if($year == '0000') return false;

        if(!isset($this->result[$year])) $this->result[$year] = array('closed' => 0, 'undelayed' => 0);
        if($row->status == 'closed') $this->result[$year]['closed'] ++;
        if($row->status == 'closed' and $row->closedDate <= $row->firstEnd) $this->result[$year]['undelayed'] ++;
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $year => $result) $this->result[$year] = $result['closed'] ? round($result['undelayed'] / $result['closed'], 4) : 0;
        return $this->filterByOptions($records, $options);
    }
}
