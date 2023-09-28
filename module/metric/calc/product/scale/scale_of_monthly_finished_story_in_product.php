<?php
/**
 * 按产品统计的月度完成研发需求规模数。
 * Scale of monthly finished story in product.
 *
 * 范围：product
 * 对象：story
 * 目的：scale
 * 度量名称：按产品统计的月度完成研发需求规模数
 * 单位：个
 * 描述：按产品统计的月度完成研发需求规模数表示每月完成的研发需求的规模。该度量项反映了产品团队每月完成的研发需求规模，可以用于评估产品团队的研发需求完成情况和效率。
 * 定义：产品中关闭时间为某年某月且关闭原因为已完成的研发需求的规模数求和;过滤父需求;过滤已删除的研发需求;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class scale_of_monthly_finished_story_in_product extends baseCalc
{
    public $dataset = 'getDevStories';

    public $fieldList = array('t1.product', 't1.closedDate', 't1.closedReason', 't1.estimate', 't1.parent');

    public $result = array();

    public function calculate($data)
    {
        $product      = $data->product;
        $closedDate   = $data->closedDate;
        $closedReason = $data->closedReason;
        $estimate     = $data->estimate;
        $parent       = $data->parent;

        if($parent == '-1') return false;

        if($closedReason != 'done') return false;

        if(empty($closedDate)) return false;

        $year = substr($closedDate, 0, 4);

        if($year == '0000') return false;

        $month = substr($closedDate, 5, 2);

        if(!isset($this->result[$product])) $this->result[$product] = array();
        if(!isset($this->result[$product][$year])) $this->result[$product][$year] = array();
        if(!isset($this->result[$product][$year][$month])) $this->result[$product][$year][$month] = 0;

        $this->result[$product][$year][$month] += $estimate;
    }

    public function getResult($options = null)
    {
        $records = $this->getRecords(array('product', 'year', 'month', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
