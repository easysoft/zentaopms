<?php
/**
 * 按全局统计的年度交付研发需求数。
 * Count of annual delivered story.
 *
 * 范围：global
 * 对象：story
 * 目的：scale
 * 度量名称：按全局统计的年度交付研发需求数
 * 单位：个
 * 描述：按全局统计的年度交付研发需求数表示每年交付的研发需求的数量。该度量项反映了组织每年交付给用户的研发需求数量，可以用于评估组织的研发需求交付效能和效果。
 * 定义：所有的所处阶段为已发布且发布时间为某年或关闭原因为已完成且关闭时间为某年的研发需求个数求和;过滤已删除的研发需求;
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
class count_of_annual_delivered_story extends baseCalc
{
    public $dataset = 'getDevStories';

    public $fieldList = array('t1.stage', 't1.releasedDate', 't1.closedReason', 't1.closedDate');

    public function calculate($data)
    {
        $stage        = $data->stage;
        $closedReason = $data->closedReason;
        $releasedDate = $data->releasedDate;
        $closedDate   = $data->closedDate;

        $date = null;
        if($closedReason == 'done') $date = $closedDate;
        if($stage == 'released' && !empty($closedDate)) $date = $releasedDate;

        if($date === null) return false;

        $year = substr($date, 0, 4);
        if($year == '0000') return false;

        if(!isset($this->result[$year])) $this->result[$year] = 0;

        $this->result[$year] += 1;
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $year => $value)
        {
            $records[] = array('year' => $year, 'value' => $value);
        }
        return $this->filterByOptions($records, $options);
    }
}
