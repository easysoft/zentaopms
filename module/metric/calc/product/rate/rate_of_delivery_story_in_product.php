<?php
/**
 * 按产品统计的研发需求交付率。
 * Rate of delivery story in product.
 *
 * 范围：product
 * 对象：story
 * 目的：rate
 * 度量名称：按产品统计的研发需求交付率
 * 单位：%
 * 描述：按产品统计的研发需求交付率表示按产品统计的已交付的研发需求数相对于按产品统计的有效研发需求数。这个度量项衡量了产品团队按时交付需求的能力。交付率越高，代表产品团队能够将更多的需求交付给用户。
 * 定义：复用：;按产品统计的已交付研发需求数;按产品统计的无效研发需求数;按产品统计的研发需求总数;公式：;按产品统计的研发需求完成率=按产品统计的已交付研发需求数/（按产品统计的研发需求总数-按产品统计的无效研发需求数）*100%;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class rate_of_delivery_story_in_product extends baseCalc
{
    public $dataset = 'getDevStories';

    public $fieldList = array('t1.product', 't1.closedReason', 't1.stage');

    public $result = array();

    public function calculate($row)
    {
        if(!isset($this->result[$row->product])) $this->result[$row->product] = array('delivered' => 0, 'valid' => 0);

        if($row->stage == 'released' || $row->closedReason == 'done')                            $this->result[$row->product]['delivered'] ++;
        if(!in_array($row->closedReason, array('duplicate', 'willnotdo', 'bydesign', 'cancel'))) $this->result[$row->product]['valid'] ++;
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $productID => $storyInfo)
        {
            $records[] = array(
                'product' => $productID,
                'value'   => $storyInfo['valid'] ? round($storyInfo['delivered'] / $storyInfo['valid'], 4) : 0,
            );
        }

        return $this->filterByOptions($records, $options);

    }
}
