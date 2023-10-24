<?php
/**
 * 按产品统计的年度完成研发需求数。
 * Count of annual finished story in product.
 *
 * 范围：product
 * 对象：story
 * 目的：scale
 * 度量名称：按产品统计的年度完成研发需求数
 * 单位：个
 * 描述：按产品统计的年度完成研发需求数是指产品在某年度已关闭且关闭原因为已完成的研发需求数量。这个度量项可以反映产品团队在一年时间内的开发效率和成果。完成研发需求数量的增加说明产品团队在该年度内取得了更多的开发成果和交付物。
 * 定义：产品中关闭时间在某年且关闭原因为已完成的研发需求的个数求和;过滤已删除的研发需求;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_annual_finished_story_in_product extends baseCalc
{
    public $dataset = 'getDevStories';

    public $fieldList = array('t1.product', 't1.closedDate', 't1.status', 't1.closedReason');

    public $result = array();

    public function calculate($row)
    {
        if($row->status != 'closed' or $row->closedReason != 'done') return false;

        $product    = $row->product;
        $closedDate = $row->closedDate;

        if(empty($closedDate)) return false;

        $year = substr($closedDate, 0, 4);

        if(empty($year) || $year == '0000') return false;

        if(!isset($this->result[$product])) $this->result[$product] = array();
        if(!isset($this->result[$product][$year])) $this->result[$product][$year] = 0;
        $this->result[$product][$year] += 1;
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $product => $years)
        {
            foreach($years as $year => $value)
            {
                $records[] = array(
                    'product' => $product,
                    'year'    => $year,
                    'value'   => $value,
                );
            }
        }

        return $this->filterByOptions($records, $options);
    }
}
