<?php
/**
 * 按产品统计的月度交付研发需求数。
 * Count of monthly delivered story in product.
 *
 * 范围：product
 * 对象：story
 * 目的：scale
 * 度量名称：按产品统计的月度交付研发需求数
 * 单位：个
 * 描述：按产品统计的月度交付研发需求数表示每月完成或关联到发布的研发需求的数量。该度量项反映了产品团队每月交付给用户的研发需求数量，可以用于评估产品团队的研发需求交付效能。
 * 定义：产品中研发需求个数求和;所处阶段为已发布且发布时间为某年某月或关闭原因为已完成且关闭时间为某年某月;过滤已删除的研发需求;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_monthly_delivered_story_in_product extends baseCalc
{
    public $dataset = 'getDevStories';

    public $fieldList = array('t1.product', 't1.stage', 't1.releasedDate', 't1.closedReason', 't1.closedDate');

    public $result = array();

    public function calculate($row)
    {
        $product      = $row->product;
        $stage        = $row->stage;
        $releasedDate = $row->releasedDate;
        $closedReason = $row->closedReason;
        $closedDate   = $row->closedDate;

        $year  = null;
        $month = null;
        if($stage == 'released')
        {
            if(empty($releasedDate)) return false;
            $year = substr($releasedDate, 0, 4);
            if($year == '0000') return false;
            $month = substr($releasedDate, 5, 2);
        }

        if($closedReason == 'done')
        {
            if(empty($closedDate)) return false;
            $year = substr($closedDate, 0, 4);
            if($year == '0000') return false;
            $month = substr($closedDate, 5, 2);
        }

        if(empty($year)) return false;

        if(!isset($this->result[$product])) $this->result[$product] = array();
        if(!isset($this->result[$product][$year])) $this->result[$product][$year] = array();
        if(!isset($this->result[$product][$year][$month])) $this->result[$product][$year][$month] = 0;
        $this->result[$product][$year][$month] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('product', 'year', 'month', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
