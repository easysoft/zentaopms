<?php
/**
 * 按产品统计的研发完毕的研发需求规模数。
 * Scale of developed story in product.
 *
 * 范围：product
 * 对象：story
 * 目的：scale
 * 度量名称：按产品统计的研发完毕的研发需求规模数
 * 单位：个
 * 描述：按产品统计的研发完毕的研发需求规模数是指产品中阶段为研发完毕及以后的研发需求的规模。这个度量项可以反映产品在研发过程中的进展和成就。研发完毕的研发需求规模数越多，说明产品取得了更多的研发成果。
 * 定义：产品中研发需求规模数求和;阶段为（研发完毕、测试中、测试完毕、已验收、交付中、已交付、已发布）或关闭原因为已完成的;过滤已删除的研发需求;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class scale_of_developed_story_in_product extends baseCalc
{
    public $dataset = 'getDevStories';

    public $fieldList = array('t1.estimate', 't1.product', 't1.stage', 't1.closedReason');

    public $result = array();

    public function calculate($row)
    {
        $stage        = $row->stage;
        $product      = $row->product;
        $closedReason = $row->closedReason;
        $estimate     = $row->estimate;

        if(!in_array($stage, array('developed', 'testing', 'tested', 'verified', 'delivering', 'delivered', 'released')) && $closedReason != 'done') return false;

        if(!isset($this->result[$row->product])) $this->result[$row->product] = 0;
        $this->result[$row->product] += $estimate;
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $product => $value) $records[] = array('product' => $product, 'value' => $value);
        return $this->filterByOptions($records, $options);
    }
}
