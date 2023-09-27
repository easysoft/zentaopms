<?php
/**
 * 按系统统计的年度完成任务数。
 * Count of annual finished task.
 *
 * 范围：system
 * 对象：task
 * 目的：scale
 * 度量名称：按系统统计的年度完成任务数
 * 单位：个
 * 描述：按系统统计的年度完成任务数是指某年内已经完成的任务总量。该度量项可以用来评估团队或组织在某年内的工作效率和完成能力。较高的年度完成任务数表示团队或组织在项目执行方面表现出较好的效率。
 * 定义：所有的任务个数求和;完成时间为某年;过滤已删除的任务;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_annual_finished_task extends baseCalc
{
    public $dataset = 'getTasks';

    public $fieldList = array('t1.status', 't1.finishedDate');

    public $result = array();

    public function calculate($data)
    {
        if(empty($data->finishedDate) or $data->status != 'done') return false;

        $finishedDate = $data->finishedDate;

        if(empty($finishedDate)) return false;
        $year = substr($finishedDate, 0, 4);
        if($year == '0000') return false;

        if(!isset($this->result[$year])) $this->result[$year] = 0;

        $this->result[$year] += 1;
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $year => $value) $records[] = array('year' => $year, 'value' => $value);
        return $this->filterByOptions($records, $options);
    }
}
