<?php
/**
 * 按系统统计的年度完成项目中延期完成项目数。
 * Count of delayed finished project which annual finished.
 *
 * 范围：system
 * 对象：project
 * 目的：scale
 * 度量名称：按系统统计的年度完成项目中延期完成项目数
 * 单位：个
 * 描述：按系统统计的年度完成项目中延期完成项目数是指在某年度完成的项目中超过预定计划时间关闭的项目数量。这个度量项可以帮助团队评估某年度项目的时间管理和执行能力，并识别延期原因并采取适当措施。较高的延期关闭项目数可能需要团队关注项目计划和资源安排的问题。
 * 定义：复用：;按系统统计的年度关闭项目数;按系统统计的每年完成项目中按期完成项目数;公式：;按系统统计的年度延期完成项目数=按系统统计的年度关闭项目数-按系统统计的每年完成项目中按期完成项目数;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_delayed_finished_project_which_annual_finished extends baseCalc
{
    public $dataset = 'getAllProjects';

    public $fieldList = array('t1.status', 't1.closedDate', 't1.realEnd', 't1.firstEnd');

    public $result = array();

    public function calculate($row)
    {
        if(empty($row->closedDate) || empty($row->firstEnd) || empty($row->realEnd)) return false;

        $finishedYear = substr($row->realEnd, 0, 4);
        $firstEndYear = substr($row->firstEnd, 0, 4);
        if($finishedYear == '0000' || $firstEndYear == '0000') return false;

        if($row->status == 'closed' && $row->realEnd > $row->firstEnd)
        {
            if(!isset($this->result[$finishedYear])) $this->result[$finishedYear] = 0;
            $this->result[$finishedYear] ++;
        }
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('year', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
