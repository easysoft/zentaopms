<?php
/**
 * 按系统统计的流水线执行数。
 * Count of compile pipeline.
 *
 * 范围：system
 * 对象：pipeline
 * 目的：rate
 * 度量名称：按系统统计的流水线执行数
 * 单位：个
 * 描述：按系统统计的流水线执行数是指在一定时间内的流水线执行的数量，反映了团队的开发效率和响应能力。较高的流水线执行数通常意味着团队能够快速地将代码变更集成到主分支，并及时交付新功能或修复。监控这一指标有助于团队优化开发流程，确保高效、稳定的交付。
 * 定义：系统的流水线执行数量   不统计已删除代码库 不统计已删除流水线
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    liyang <liyang@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_compile_pipeline extends baseCalc
{
    public $dataset = 'getCompile';

    public $fieldList = array('t1.createdDate');

    public $result = array();

    public function calculate($row)
    {
        $createdDate = $row->createdDate;

        $year = $this->getYear($createdDate);
        if(!$year) return false;

        list($year, $month, $day) = explode('-', $createdDate);

        $day = substr($day, 0, 2);

        if(!isset($this->result[$year]))               $this->result[$year] = array();
        if(!isset($this->result[$year][$month]))       $this->result[$year][$month] = array();
        if(!isset($this->result[$year][$month][$day])) $this->result[$year][$month][$day] = 0;

        $this->result[$year][$month][$day] ++;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('year', 'month', 'day', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
