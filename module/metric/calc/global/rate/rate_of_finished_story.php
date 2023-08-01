<?php
/**
 * 按全局统计的研发需求完成率。
 * Rate of finished story.
 *
 * 范围：global
 * 对象：story
 * 目的：rate
 * 度量名称：按全局统计的研发需求完成率
 * 单位：%
 * 描述：按全局统计的研发需求完成率表示按全局统计的已完成的研发需求数相对于按全局统计的有效研发需求数。这个指标反映了整体研发团队在完成研发需求方面的效率和质量。完成率越高，说明研发团队能够按时交付需求，并且需求达到预期的质量标准。
 * 定义：复用：;按全局统计的完成研发需求数;按全局统计的有效研发需求数;公式：;按全局统计的研发需求完成率=按全局统计的已完成研发需求数/按全局统计的有效研发需求数*100%;
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
class rate_of_finished_story extends baseCalc
{
    public $dataset = 'getDevStories';

    public $fieldList = array('t1.status', 't1.closedReason');

    public function calculate($row)
    {
        if(!isset($this->result['finished'])) $this->result['finished'] = 0;
        if(!isset($this->result['valid']))    $this->result['valid'] = 0;

        if($row->status == 'closed' and $row->closedReason == 'done')                            $this->result['finished'] ++;
        if(!in_array($row->closedReason, array('duplicate', 'willnotdo', 'bydesign', 'cancel'))) $this->result['valid'] ++;
    }

    public function getResult($options = array())
    {
        $this->result = $this->result['valid'] ? round($this->result['finished'] / $this->result['valid'], 4) : 0;
        $records = array(array('value' => $this->result));
        return $this->filterByOptions($records, $options);

    }
}
