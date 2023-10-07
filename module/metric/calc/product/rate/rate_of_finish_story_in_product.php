<?php
/**
 * 按产品统计的研发需求完成率。
 * Rate of finish story in product.
 *
 * 范围：product
 * 对象：story
 * 目的：rate
 * 度量名称：按产品统计的研发需求完成率
 * 单位：%
 * 描述：按产品统计的研发需求交付率表示按产品统计的已完成的研发需求规数相对于按产品统计的有效研发需求数。这个度量项衡量了研发团队完成需求的能力。完成率越高，代表研发团队有更多研发成果，保证产品的正常发布。
 * 定义：复用：;按产品统计的已完成研发需求数;按产品统计的无效研发需求数;按产品统计的研发需求总数;公式：;按产品统计的研发需求完成率=按产品统计的已完成研发需求数/（按产品统计的研发需求总数-按产品统计的无效研发需求数）*100%;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class rate_of_finish_story_in_product extends baseCalc
{
    public $dataset = 'getDevStories';

    public $fieldList = array('t1.product', 't1.id', 't1.status', 't1.closedReason');

    public $result = array();

    public function calculate($row)
    {
        if(!isset($this->result[$row->product])) $this->result[$row->product] = array('finished' => 0, 'total' => 0, 'invalid' => 0);

        $this->result[$row->product]['total'] ++;
        if($row->status == 'closed' and $row->closedReason == 'done') $this->result[$row->product]['finished'] ++;
        if($row->status == 'closed' and in_array($row->closedReason, array('duplicate', 'willnotdo', 'bydesign', 'cancel'))) $this->result[$row->product]['invalid'] ++;
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $productID => $storyInfo)
        {
            $records[] = array(
                'product' => $productID,
                'value'   => $storyInfo['total'] ? round($storyInfo['finished'] / ($storyInfo['total'] - $storyInfo['invalid']), 4) : 0,
            );
        }

        return $this->filterByOptions($records, $options);
    }
}
