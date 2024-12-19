<?php
/**
 * 按系统统计的流水线执行成功率。
 * Rate of success pipeline.
 *
 * 范围：system
 * 对象：pipeline
 * 目的：rate
 * 度量名称：按系统统计的流水线执行成功率
 * 单位：%
 * 描述：按系统统计的流水线执行成功率是指在一定时间内的流水线执行成功数量/流水线执行数量，反映了自动化构建和部署过程的稳定性与可靠性。
 * 定义：系统的流水线执行成功数量/流水线执行数量   不统计已删除代码库 不统计已删除流水线
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    liyang <liyang@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class rate_of_success_pipeline extends baseCalc
{
    public $dataset = 'getCompile';

    public $fieldList = array('t1.status', 't1.createdDate');

    public $result = array();

    public $successCompile = array();

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

        $this->result[$year][$month][$day] += 1;

        $date = substr($createdDate, 0, 10);
        if(!isset($this->successCompile[$date]))  $this->successCompile[$date] = 0;
        if($row->status == 'success') $this->successCompile[$date] += 1;
    }

    public function getResult($options = array())
    {
        foreach($this->successCompile as $date => $successCount)
        {
            list($year, $month, $day) = explode('-', $date);
            $count = $this->result[$year][$month][$day];
            $this->result[$year][$month][$day] = $count ? round($successCount / $count, 4) : 0;
        }

        $records = $this->getRecords(array('year', 'month', 'day', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
