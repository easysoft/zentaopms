<?php
/**
 * 按全局统计的月度完成研发需求数。
 * Count of monthly finished story.
 *
 * 范围：global
 * 对象：story
 * 目的：scale
 * 度量名称：按全局统计的月度完成研发需求数
 * 单位：个
 * 描述：按全局统计的月度完成研发需求数表示每月完成的研发需求的数量。该度量项反映了组织每月完成的研发需求数量，可以用于评估组织的研发需求完成情况和效率。
 * 定义：所有的研发需求个数求和;关闭时间为某年某月;关闭原因为已完成;过滤已删除的研发需求;
 * 度量库：
 * 收集方式：realtime
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_monthly_finished_story extends baseCalc
{
    public $dataset = 'getDevStories';

    public $fieldList = array('t1.closedDate', 't1.closedReason');

    public function calculate($data)
    {
        $closedDate   = $data->closedDate;
        $closedReason = $data->closedReason;

        if(empty($closedDate)) return false;

        $year  = substr($closedDate, 0, 4);
        $month = substr($closedDate, 5, 2);

        if($year == '0000' || $closedReason != 'done') return false;

        if(!isset($this->result[$year])) $this->result[$year] = array();
        if(!isset($this->result[$year][$month])) $this->result[$year][$month] = 0;

        $this->result[$year][$month] += 1;
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $year => $months)
        {
            foreach($months as $month => $value)
            {
                $records[] = array('year' => $year, 'month' => $month, 'value' => $value);
            }
        }
        return $this->filterByOptions($records, $options);
    }
}
