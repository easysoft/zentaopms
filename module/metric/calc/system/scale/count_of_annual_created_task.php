<?php
/**
 * 按系统统计的年度新增任务数。
 * Count of annual created task.
 *
 * 范围：system
 * 对象：task
 * 目的：scale
 * 度量名称：按系统统计的年度新增任务数
 * 单位：个
 * 描述：按系统统计的年度新增任务数是指一年内新添加的任务总量。该度量项可以用来衡量团队或组织在某年内所承担的新增工作量。较高的年度新增任务数可能需要额外的资源和计划调整来满足需求。
 * 定义：所有的任务个数求和;创建时间为某年;过滤已删除的任务;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_annual_created_task extends baseCalc
{
    public $dataset = 'getTasks';

    public $fieldList = array('t1.openedDate');

    public $result = array();

    public function calculate($data)
    {
        $openedDate = $data->openedDate;

        if(empty($openedDate)) return false;

        $year = substr($openedDate, 0, 4);
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
