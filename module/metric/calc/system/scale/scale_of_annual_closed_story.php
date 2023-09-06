<?php
/**
 * 按全局统计的年度关闭研发需求规模数。
 * Scale of annual closed story.
 *
 * 范围：global
 * 对象：story
 * 目的：scale
 * 度量名称：按全局统计的年度关闭研发需求规模数
 * 单位：个
 * 描述：按全局统计的年度关闭研发需求规模数表示某年度关闭的研发需求的规模总数。该度量项反映了组织每年关闭的研发需求的规模总数，可以用于评估组织的研发需求规模管理和调整情况。
 * 定义：所有的研发需求规模数求和;关闭时间为某年;过滤已删除的研发需求;
 * 度量库：
 * 收集方式：realtime
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class scale_of_annual_closed_story extends baseCalc
{
    public $dataset = 'getDevStories';

    public $fieldList = array('t1.estimate', 't1.status', 't1.closedDate');

    public function calculate($row)
    {
        if(empty($row->closedDate) or !isset($row->estimate)) return false;

        $year = $this->getYear($row->closedDate);
        if($year == '0000') return false;

        if($row->status == 'closed')
        {
            if(!isset($this->result[$year])) $this->result[$year] = 0;
            $this->result[$year] += $row->estimate;
        }
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('year', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
