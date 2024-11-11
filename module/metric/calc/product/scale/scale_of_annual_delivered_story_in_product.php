<?php
/**
 * 按产品统计的年度交付研发需求规模数。
 * Scale of annual delivered story in product.
 *
 * 范围：product
 * 对象：story
 * 目的：scale
 * 度量名称：按产品统计的年度交付研发需求规模数
 * 单位：工时
 * 描述：按产品统计的年度交付研发需求数是指产品在某年度内已经成功交付给用户的研发需求规模数。这个度量项可以反映产品团队在开发过程中的交付能力和协作能力，可以用于评估产品的研发需求交付效能和效果。已交付的研发需求规模数越多可能说明产品团队在该年度内的交付成果越多。
 * 定义：产品中研发需求规模数求和;所处阶段为已发布且发布时间为某年某月或关闭原因为已完成且关闭时间为某年某月;过滤父研发需求;过滤已删除的研发需求;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class scale_of_annual_delivered_story_in_product extends baseCalc
{
    public $dataset = 'getDevStories';

    public $fieldList = array('t1.product', 't1.stage', 't1.releasedDate', 't1.closedReason', 't1.closedDate', 't1.estimate', 't1.parent');

    public $result = array();

    public function calculate($row)
    {
        $product      = $row->product;
        $stage        = $row->stage;
        $releasedDate = $row->releasedDate;
        $closedReason = $row->closedReason;
        $closedDate   = $row->closedDate;
        $estimate     = $row->estimate;
        $parent       = $row->parent;

        if($parent == '-1') return false;

        $year = null;
        if($stage == 'released')
        {
            $year = $this->getYear($releasedDate);
            if(!$year) return false;
        }

        if($closedReason == 'done')
        {
            $year = $this->getYear($closedDate);
            if(!$year) return false;
        }

        if(empty($year)) return false;

        if(!isset($this->result[$product])) $this->result[$product] = array();
        if(!isset($this->result[$product][$year])) $this->result[$product][$year] = 0;
        $this->result[$product][$year] += $estimate;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('product', 'year', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
